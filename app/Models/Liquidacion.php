<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Liquidacion extends Model
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
    protected $table = 'liquidacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'LiqID', 'LiqEmp', 'LiqCargo', 'LiqRol', 'LiqHoras', 'LiqFecAlta', 'LiqFecBaja', 'LiqSit', 'LiqTipo', 'LiqNivel', 'LiqFecBajaOf', 'LiqHorasEst', 'LiqFecAltaRec'
    ];

    /**
     * La cable primaria de este model
     *
     * @var string
     */
    protected $primaryKey = 'LiqID';

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
     * EMP_CUIL + ROL(3)
     *
     * @param string    $empleado   CUIL del empleado
     * @param string    $rol        Rol de la liquidacion
     *
     * @return string       El Id de la liquidacion
     */
    public static function genId( $empleado, $rol ) {
        $rol = str_pad( $rol, 3, '0', STR_PAD_LEFT);

        return $empleado . $rol;
    }

}
