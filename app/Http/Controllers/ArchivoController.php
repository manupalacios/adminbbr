<?php

namespace App\Http\Controllers;

use App\Models\Archivo;
use App\Http\Requests\ArchivoRequest;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Storage;

class ArchivoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param bool $saved Si un archivo fue guardada será true
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('archivo.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('archivo.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ArchivoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArchivoRequest $request)
    {
        $tipo = $this->getTipoToString($request->tipo);
        $grupo = 'planta';
        $nivel = $this->getNivelToString($request->nivel);
        $numero = (!empty($request->numero)) ? $request->numero : 1 ;
        $file = $request->file('archivo');

        $where = array(
            'tipo_id' => $request->tipo,
            'grupo_id' => $request->grupo,
            'nivel_id' => $request->nivel,
            'anio' => $request->anio,
            'mes' => $request->mes,
            'numero' => $numero
        );

        $archivo = Archivo::where($where)->first();

        if (!$request->has('sobreescribir') && !empty($archivo) && $archivo->exists) {
            $request->session()->flash('status', 'Ya existe un archivo para estos parámetros. ¿Desea sobrescribirlo?');
            return redirect()->back()->withInput();
        }

        $path = $tipo.'/'.$grupo.'/'.$nivel.'/'.$request->anio.'/'.$request->mes.'/'.$numero;

        if ($request->has('sobreescribir') && $request->sobreescribir) {
            Storage::disk('liquidaciones')->delete($path.'/'.$archivo->archivo);
            $archivo = Archivo::where($where)->update(['archivo' => $file->getClientOriginalName()]);
        } else {
            $archivo = Archivo::create([
                'tipo_id' => $request->tipo,
                'grupo_id' => $request->grupo,
                'nivel_id' => $request->nivel,
                'anio' => $request->anio,
                'mes' => $request->mes,
                'numero' => $numero,
                'archivo' => $file->getClientOriginalName()
            ]);
        }

        $path = $file->storeAs($path, $file->getClientOriginalName(),'liquidaciones');

        $request->session()->flash('status', 'El archivo se ha agregado correctamente');

        return redirect()->route('archivo.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Archivo  $archivo
     * @return \Illuminate\Http\Response
     */
    public function show(Archivo $archivo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Archivo  $archivo
     * @return \Illuminate\Http\Response
     */
    public function edit(Archivo $archivo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Archivo  $archivo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Archivo $archivo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Archivo  $archivo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Archivo $archivo)
    {
        //
    }

    /**
     * Devuelve un string del tipo de archivo
     *
     * @param  integer $tipo Id del tipo de archivo 1 = normal, 2 = adicional, 3 = sac
     * @return string       Tipo en string
     */
    private function getTipoToString($tipo)
    {
        switch ($tipo) {
            case 1:
                $tipo = "normal";
                break;
            case 2:
                $tipo = "adicional";
                break;
            case 3:
                $tipo = "sac";
                break;
        }
        return $tipo;
    }

    /**
     * Devuelve un string del tipo de archivo
     *
     * @param  integer $nivel Id del nivel de archivo 0 = sin_nivel, 1 = inicial, 2 = primario, 3 = medio, 4 = superior
     * @return string       Nivel en string en snake case y lower case
     */
    private function getNivelToString($nivel) {
        switch ($nivel) {
            case 0:
                $nivel = "sin";
                break;
            case 1:
                $nivel = "ini";
                break;
            case 2:
                $nivel = "pri";
                break;
            case 3:
                $nivel = "med";
                break;
            case 4:
                $nivel = "sup";
                break;
        }
        return $nivel;
    }

    /**
     * Devuelve los archivos de un año y grupo via ajax request
     *
     * @param  \App\Http\Requests\ArchivoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function getArchivos(ArchivoRequest $request)
    {
        if ($request->ajax()) {
            $where = array(
                'anio' => $request->anio,
                'grupo_id' => $request->grupo
            );
            $archivos = Archivo::where($where)
                                ->orderBy('mes', 'desc')
                                ->orderBy('tipo_id', 'asc')
                                ->orderBy('numero', 'asc')
                                ->get();
            return response()->json($archivos);
        }
    }
}
