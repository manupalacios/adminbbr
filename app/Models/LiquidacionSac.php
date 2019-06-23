<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiquidacionSac extends Model
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
    protected $table = 'liquidacionsac';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'LiqSACLiq', 'LiqSACSem', 'LiqSACAnio', 'LiqSACRemunera', 'LiqSACNoRemunera', 'LiqSACDescuento', 'LiqSACSalario', 'LiqSACNivel', 'LiqSACSit', 'LiqSACFec'
    ];

    /**
     * La cable primaria de este model
     * 
     * @var string
     */
    protected $primaryKey = 'LiqSacID';

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
