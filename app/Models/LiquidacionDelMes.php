<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiquidacionDelMes extends Model
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
    protected $table = 'liqdelmes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'LDMID', 'LDMLiqId', 'LDMYear', 'LDMMes', 'LDMTipo', 'LDMNro', 'LDMNivel', 'LDMClase'
    ];

    /**
     * La cable primaria de este model
     *
     * @var string
     */
    protected $primaryKey = 'LDMID';

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
     * Genera el id de la liquidacion
     * EMP_CUIL + ROL(3) + ANIO + MES(2) + GRUPO
     *
     * @param string    $empleado   CUIL del empleado
     * @param string    $rol        Rol de la liquidacion
     * @param string    $anio       Rol de la liquidacion
     * @param string    $mes        Rol de la liquidacion
     * @param string    $grupo      Rol de la liquidacion
     *
     * @return string       El Id de la liquidacion
     */
    public static function genId( $empleado, $rol, $anio, $mes, $grupo ) {
        $rol = str_pad( $rol, 3, '0', STR_PAD_LEFT);
        $mes = str_pad( $mes, 2, '0', STR_PAD_LEFT);

        return $empleado.$rol.$anio.$mes.$grupo;
    }


}
