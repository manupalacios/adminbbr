<?php

namespace App\Http\Controllers;

use App\Models\Liquidacion;
use App\Http\Requests\LiquidacionRequest;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Storage;

class LiquidacionController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @param bool $saved Si una liquidacion fue guardada será true
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('liquidacion.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('liquidacion.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LiquidacionRequest $request)
    {
        $tipo = $this->getTipoToString($request->tipo);
        $grupo = 'planta';
        $nivel = $this->getNivelToString($request->nivel);
        $numero = ($request->has('numero')) ? 1 : $request->numero ;
        $file = $request->file('archivo');

        $where = array(
            'tipo_id' => $request->tipo,
            'grupo_id' => $request->grupo,
            'nivel_id' => $request->nivel,
            'anio' => $request->anio,
            'mes' => $request->mes,
            'numero' => $numero
        );

        $liquidacion = Liquidacion::where($where)->first();

        if (!$request->has('sobreescribir') && !empty($liquidacion) && $liquidacion->exists) {
            $request->session()->flash('status', 'Ya existe un archivo para estos parámetros. ¿Desea sobrescribirlo?');
            return redirect()->back()->withInput();
        }

        if ($request->has('sobreescribir') && $request->sobreescribir) {
            $archivo = Liquidacion::where($where)->update(['archivo' => $file->getClientOriginalName()]);
        } else {
            $archivo = Liquidacion::create([
                'tipo_id' => $request->tipo,
                'grupo_id' => $request->grupo,
                'nivel_id' => $request->nivel,
                'anio' => $request->anio,
                'mes' => $request->mes,
                'numero' => $numero,
                'archivo' => $file->getClientOriginalName()
            ]);
        }

        $path = $tipo.'/'.$grupo.'/'.$nivel.'/'.$request->anio.'/'.$request->mes.'/'.$numero;
        $path = $file->storeAs($path, $file->getClientOriginalName(),'liquidaciones');

        $request->session()->flash('status', 'El archivo se ha agregado correctamente');

        return redirect()->route('liquidacion.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Liquidacion  $liquidacion
     * @return \Illuminate\Http\Response
     */
    public function show(Liquidacion $liquidacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Liquidacion  $liquidacion
     * @return \Illuminate\Http\Response
     */
    public function edit(Liquidacion $liquidacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Liquidacion  $liquidacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Liquidacion $liquidacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Liquidacion  $liquidacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Liquidacion $liquidacion)
    {
        //
    }

    /**
     * Devuelve un string del tipo de liquidacion
     * 
     * @param  integer $tipo Id del tipo de liquidacion 1 = normal, 2 = adicional, 3 = sac
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
     * Devuelve un string del tipo de liquidacion
     * 
     * @param  integer $nivel Id del nivel de liquidacion 0 = sin_nivel, 1 = inicial, 2 = primario, 3 = medio, 4 = superior
     * @return string       Nivel en string en snake case y lower case
     */
    private function getNivelToString($nivel)
    {
        switch ($nivel) {
            case 0:
                $nivel = "sin_nivel";
                break;
            case 1:
                $nivel = "inicial";
                break;
            case 2:
                $nivel = "primario";
                break;
            case 3:
                $nivel = "medio";
                break;
            case 4:
                $nivel = "superior";
                break;
        }
        return $nivel;
    }
}
