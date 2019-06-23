<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConceptoAntiguedad extends Model
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
    protected $table = 'conceptoantiguedad';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ConcepAntCon', 'ConcepAntTipoLq'
    ];

    /**
     * La cable primaria de este model
     * 
     * @var string
     */
    protected $primaryKey = 'ConcepAntID';

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
