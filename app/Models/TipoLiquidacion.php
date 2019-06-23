<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoLiquidacion extends Model
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
    protected $table = 'tipoliquidacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'TipLiqDesc', 'TipLiqShort'
    ];

    /**
     * La cable primaria de este model
     * 
     * @var string
     */
    protected $primaryKey = 'TipLiqID';

}
