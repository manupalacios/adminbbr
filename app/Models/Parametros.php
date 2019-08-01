<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parametros extends Model
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
    protected $table = 'parametros';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ParId', 'ParFecLiq', 'ParAnio', 'ParMes'
    ];

    /**
     * La cable primaria de este model
     *
     * @var string
     */
    protected $primaryKey = 'ParId';

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

}
