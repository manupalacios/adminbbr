<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiquidacionMesDetalle extends Model
{

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'liqmesdetalle';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'LMDID', 'LMDLiqMes', 'LMDLiqConcep', 'LMDMonto', 'LMDOrden', 'LMDRemun'
    ];

    /**
     * La cable primaria de este model
     *
     * @var string
     */
    protected $primaryKey = 'LMDID';

    /**
     * Si la clave primaria es o no autoincrementable
     *
     * @var boolean
     */
    public $incrementing = false;

    /**
     * El tipo de dato de la clave primaria
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * @method genId
     * Genera el id del detalle
     * EMP_CUIL + ROL(3) + ANIO + MES(2) + CONCEPTO(8)
     *
     * @param string    $empleado   CUIL del empleado
     * @param string    $rol        rol de la liquidacion
     * @param string    $anio       anio de la liquidacion
     * @param string    $mes        mes de la liquidacion
     * @param string    $concepto   id del concepto
     *
     * @return string       El Id del detalle
     */
    public static function genId( $empleado, $rol, $anio, $mes, $concepto ) {

        $rol = str_pad( $rol, 3, '0', STR_PAD_LEFT);
        $mes = str_pad( $mes, 2, '0', STR_PAD_LEFT);
        $concepto = str_pad( $concepto, 8, '0', STR_PAD_LEFT);

        return $empleado.$rol.$anio.$mes.$concepto;
    }
}
