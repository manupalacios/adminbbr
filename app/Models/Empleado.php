<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
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
    protected $table = 'empleado';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'EmpTipoDoc', 'EmpNroDoc', 'EmpSexo', 'EmpApellido', 'EmpNombre', 'EmpNacio', 'EmpEstCivil', 'EmpDomicilio', 'EmpFecNac', 'EmpFecAlta', 'EmpFecBaja', 'EmpFecPsico', 'EmpFecEscalafon', 'EmpObSocial', 'EmpCta', 'EmpSuc', 'EmpBco', 'EmpCP', 'EmpFecAnti', 'EmpFecAdmin', 'EmpFaseAlta', 'EmpCodArea', 'EmpTel', 'EmpEmail', 'EmpLegajo', 'EmpCBU', 'EmpTipoCta'
    ];

    /**
     * La cable primaria de este model
     * 
     * @var string
     */
    protected $primaryKey = 'EmpCUIL';

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
