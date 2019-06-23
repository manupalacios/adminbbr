<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiquidacionMes extends Model
{
    
    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'created';

    /**
     * The name of the "updated at" column.
     *
     * @var string|boolean
     */
    const UPDATED_AT = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'liquidacionmes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'LiqMesLiq', 'LiMesMes', 'LiqMesAnio', 'LiqMesLiquido', 'LiqMesRemunera', 'LiqMesNoRemunera', 'LiqMesDescuento', 'LiqMesSalario', 'LiqMesNivel', 'LiqMesSit', 'LiqMesFec', 'LiqMesEmp', 'LiqMesTipo', 'LiqMesDias', 'LiqMesHoras'
    ];

    /**
     * La cable primaria de este model
     * 
     * @var string
     */
    protected $primaryKey = 'LiqMesID';

    /**
     * Si la clave primaria es o no autoincrementable
     * 
     * @var boolean
     */
    protected $incrementing = false;

    /**
     * El tipo de dato de la clave primaria
     * 
     * @var string
     */
    protected $keyType = 'string';

}
