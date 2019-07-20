<?php

namespace App\Http\Controllers;

use App\Models\Concepto;
use App\Models\Empleado;
use App\Models\Liquidacion;
use App\Models\LiquidacionDelMes;
use App\Models\LiquidacionMes;
use App\Models\LiquidacionMesDetalle;
use App\Http\Requests\LiquidacionMesRequest;

class LiquidacionMesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\LiquidacionMesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LiquidacionMesRequest $request)
    {
        if ($request->ajax()) {

            $arrayLiquidacionMes = $request->all();
            $arrayLiquidacion = $arrayLiquidacionMes['liquidacion'];
            $arrayEmpleado = $arrayLiquidacion['empleado'];
            $empCUIL = Empleado::genCUIL($arrayEmpleado['dni'], $arrayEmpleado['genero']);
            $liqDetalle = $arrayLiquidacionMes['detalle'];

            $periodo = $arrayLiquidacionMes['periodo'];

            /* Si existe el empleado actualizo fecha de escalafon, si no lo creo */
            $empleado = Empleado::find($empCUIL);
            $data['empStatus'] = 'ok';
            $data['liqStatus'] = 'ok';

            $empData = array(
                'EmpFecEscalafon' => $arrayEmpleado['fechaAnti']
            );

            if ( empty($empleado ) ){
                $data['empStatus'] = 'new';
                $data['empDNI'] = $arrayEmpleado['dni'];
                $data['empNombre'] = implode(', ', array($arrayEmpleado['apellido'], $arrayEmpleado['nombre']) );

                $empData['EmpCUIL'] = $empCUIL;
                $empData['EmpNombre'] = strtoupper($arrayEmpleado['nombre']);
                $empData['EmpApellido'] = strtoupper($arrayEmpleado['apellido']);
                $empData['EmpSexo'] = $arrayEmpleado['genero'];
                $empData['EmpTipDoc'] = 1;
                $empData['EmpNacio'] = 1;
                $empData['EmpFecNac'] = $arrayEmpleado['fechaNac'];

            }

            $empleado = Empleado::updateOrCreate(
                [ 'EmpNroDoc' => $arrayEmpleado['dni'] ], $empData
            );

            if ( empty($empleado) ) {
                $data['empleado']['status'] = 'error';
                $data['empleado']['dni'] = $arrayEmpleado['dni'];
            }

            /* Si existe la liquidacion la actualizo si no la creo */
            $liqId = Liquidacion::genId( $empCUIL, $arrayLiquidacion['rol'] );
            $liquidacion = Liquidacion::find($liqId);

            if( empty($liquidacion) ){
                $data['liqStatus'] = 'new';
                $data['liqDNI'] = $arrayEmpleado['dni'];
                $data['liqNombre'] = implode(', ', array($arrayEmpleado['apellido'], $arrayEmpleado['nombre']) );
                $data['liqRol'] = $arrayLiquidacion['rol'];
            }

            $liquidacion = Liquidacion::updateOrCreate(
                [
                    'LiqID' => $liqId
                ], [
                    'LiqID' => $liqId,
                    'LiqEmp' => $empCUIL,
                    'LiqRol' => $arrayLiquidacion['rol'],
                    'LiqCargo' => $arrayLiquidacion['cargo'],
                    'LiqHoras' => $this->floatToDB($arrayLiquidacion['horas']),
                    'LiqFecAlta' => $arrayLiquidacion['fechaAlta'],
                    'LiqSit' => $arrayLiquidacion['situacionRevista'],
                    'LiqTipo' => $periodo['grupo']
                ]
            );

            if ( empty($liquidacion ) ){
                $data['liquidacion']['status'] = 'error';
                $data['liquidacion']['rol'] = $arrayLiquidacion['rol'];
                $data['liquidacion']['cargo'] = $arrayLiquidacion['cargo'];
            }

            /* Creo la liquidacionDelMes */
            $liqDelMesId = LiquidacionDelMes::genId( $empCUIL, $arrayLiquidacion['rol'], $periodo['anio'], $periodo['mes'], $periodo['grupo'] );
            $liquidacionDelMes = LiquidacionDelMes::updateOrCreate(
                [
                    'LDMID' => $liqDelMesId
                ], [
                    'LDMID' => $liqDelMesId,
                    'LDMLiqId' => $liqId,
                    'LDMYear' => $periodo['anio'],
                    'LDMMes' => $periodo['mes'],
                    'LDMTipo' => $periodo['grupo'],
                    'LDMNro' => $periodo['numero'],
                    'LDMNivel' => $periodo['nivel']
                ]
            );

            /* Actualizo o creo la liquidacionMes */
            $liqMesId = LiquidacionMes::genId( $empCUIL, $arrayLiquidacion['rol'], $periodo['anio'], $periodo['mes']);

            $liquidacionMes = LiquidacionMes::updateOrCreate(
                [
                    'LiqMesID' => $liqMesId
                ], [
                    'LiqMesID' => $liqMesId,
                    'LiqMesLiq' => $liqId,
                    'LiqMesMes' => $periodo['mes'],
                    'LiqMesAnio' => $periodo['anio'],
                    'LiqMesLiquido' => $this->floatToDB($arrayLiquidacionMes['liquido']),
                    'LiqMesRemunera' => $this->floatToDB($arrayLiquidacionMes['sujeto']),
                    'LiqMesNoRemunera' => $this->floatToDB($arrayLiquidacionMes['noSujeto']),
                    'LiqMesDescuento' => $this->floatToDB($arrayLiquidacionMes['descuentos']),
                    'LiqMesSalario' => $this->floatToDB($arrayLiquidacionMes['salario']),
                    'LiqMesSit' => $arrayLiquidacion['situacionRevista'],
                    'LiqMesEmp' => $empCUIL,
                    'LiqMesTipo' => $periodo['grupo'],
                    'LiqMesDias' => $arrayLiquidacionMes['dias'],
                    'LiqMesHoras' => $this->floatToDB( $arrayLiquidacion['horas'] )
                ]
            );

            /* Elimino todos los conceptos */
            $deletedRows = LiquidacionMesDetalle::where('LMDLiqMes', $liqMesId)->delete();

            /* Creo el detalle */
            foreach ($liqDetalle as $item) {
                /* Si no existe el concepto lo creo sino lo leo */
                $concepto = Concepto::firstOrCreate(
                    [
                        'ConcepCod' => $item['codigo']
                    ], [
                        'ConcepDesc' => $item['descripcion']
                    ]
                );

                $detalle = new LiquidacionMesDetalle;
                $detalle->LMDID = LiquidacionMesDetalle::genId($empCUIL, $arrayLiquidacion['rol'], $periodo['anio'], $periodo['mes'], $item['codigo']);
                $detalle->LMDLiqMes = $liqMesId;
                $detalle->LMDLiqConcep = $item['codigo'];
                $detalle->LMDMonto = $this->floatToDB( $item['importe'] );
                $detalle->LMDOrden = 0;
                $detalle->LMDRemun = $concepto->ConcepRemun;
                $detalle->save();
            }

            return response()->json($data);
        }
    }

    private function floatToDB( $float ){
       return str_replace(',', '.', $float);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LiquidacionMes  $liquidacionMes
     * @return \Illuminate\Http\Response
     */
    public function show(LiquidacionMes $liquidacionMes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LiquidacionMes  $liquidacionMes
     * @return \Illuminate\Http\Response
     */
    public function edit(LiquidacionMes $liquidacionMes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LiquidacionMes  $liquidacionMes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LiquidacionMes $liquidacionMes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LiquidacionMes  $liquidacionMes
     * @return \Illuminate\Http\Response
     */
    public function destroy(LiquidacionMes $liquidacionMes)
    {
        //
    }

    /**
     * Importa una liquidacion en base a los datos parseados del PDF
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request) {

    }
}
