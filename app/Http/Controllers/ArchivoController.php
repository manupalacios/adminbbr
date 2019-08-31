<?php

namespace App\Http\Controllers;

use App\Models\Archivo;
use App\Models\LiquidacionDelMes;
use App\Models\LiquidacionMes;
use App\Models\LiquidacionMesDetalle;
use App\Models\Parametros;
use App\Http\Requests\ArchivoRequest;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Storage;

class ArchivoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('archivo.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('archivo.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ArchivoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArchivoRequest $request) {
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
    public function show(Archivo $archivo) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Archivo  $archivo
     * @return \Illuminate\Http\Response
     */
    public function edit(Archivo $archivo) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Archivo  $archivo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Archivo $archivo) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Archivo  $archivo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Archivo $archivo) {
        //
    }

    /**
     * Devuelve un string del tipo de archivo
     *
     * @param  integer $tipo Id del tipo de archivo 1 = normal, 2 = adicional, 3 = sac
     * @return string       Tipo en string
     */
    private function getTipoToString($tipo) {
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
    public function getArchivos(ArchivoRequest $request) {
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

    /**
     * Importa el archivo a la base de datos.
     *
     * @param  \App\Models\Archivo  $archivo
     *
     * @return \Illuminate\Http\Response
     */
    public function import(Archivo $archivo) {
        $tipo = $this->getTipoToString($archivo->tipo_id);
        $grupo = 'planta';
        $nivel = $this->getNivelToString($archivo->nivel_id);

        $periodo_id =$archivo->anio. str_pad( $archivo->mes, 2, '0', STR_PAD_LEFT );
        $periodo = Parametros::find( $periodo_id );

        if( $periodo ){
            $fecha_liq = $periodo->ParFecLiq;
        } else {
            $fecha_liq = '1901-01-01';
        }

        $path = $tipo.'/'.$grupo.'/'.$nivel.'/'.$archivo->anio.'/'.$archivo->mes.'/'.$archivo->numero.'/'.$archivo->archivo;
    	$parser = new Parser();
    	$path = Storage::disk('liquidaciones')->path($path);
        $pdf = $parser->parseFile($path);
        $pages  = $pdf->getPages();

        $data['periodo'] = array(
            'tipo' => $archivo->tipo_id,
            'nivel' => $archivo->nivel_id,
            'grupo' => $archivo->grupo_id,
            'anio' => $archivo->anio,
            'mes' => $archivo->mes,
            'numero' => $archivo->numero,
            'fecha' => $fecha_liq
        );

        /* elimino las liquidaciones existentes */
        /* detalles */
        $liq_tipo = $archivo->grupo_id == 0 ? 2 : $archivo->grupo_id;
        $detalles = LiquidacionMesDetalle::join('liquidacionmes', 'liquidacionmes.LiqMesID', '=', 'liqmesdetalle.LMDLiqMes')
            ->where('liquidacionmes.LiqMesAnio', '=', $archivo->anio )->where('liquidacionmes.LiqMesMes', '=', $archivo->mes )
            ->where('liquidacionmes.LiqMesTipo', '=', $liq_tipo )->delete();
        /* liquidacionmes */
        $liquidaciones_mes = LiquidacionMes::where('LiqMesAnio', '=', $archivo->anio )
            ->where('LiqMesMes', '=', $archivo->mes )->where('LiqMesTipo', '=', $liq_tipo )->delete();
        /* liqdelmes */
        $liquidaciones_del_mes = LiquidacionDelMes::where('LDMYear', '=', $archivo->anio )
            ->where('LDMMes', '=', $archivo->mes )->where('LDMTipo', '=', $archivo->grupo_id )->where('LDMNro', '=', $archivo->numero )->delete();

        $pages_array = array();
		foreach ($pages as $key => $page) {
            $lines = preg_split('/\r\n|\r|\n/', $page->getText());
            array_push( $pages_array, $lines );
        }

        $data['pages'] = $pages_array;

        return view('archivo.import', $data);
    }
}
