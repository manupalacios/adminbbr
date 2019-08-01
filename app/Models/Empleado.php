<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model {

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
        'EmpCUIL', 'EmpTipDoc', 'EmpNroDoc', 'EmpSexo', 'EmpApellido', 'EmpNombre', 'EmpNacio', 'EmpEstCivil', 'EmpDomicilio', 'EmpFecNac', 'EmpFecAlta', 'EmpFecBaja', 'EmpFecPsico', 'EmpFecEscalafon', 'EmpObSocial', 'EmpCta', 'EmpSuc', 'EmpBco', 'EmpCP', 'EmpFecAnti', 'EmpFecAdmin', 'EmpFaseAlta', 'EmpCodArea', 'EmpTel', 'EmpEmail', 'EmpLegajo', 'EmpCBU', 'EmpTipoCta'
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
    public $incrementing = false;

    /**
     * El tipo de dato de la clave primaria
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * @method genCUIL
     * Genera un CUIL a partir de un DNI
     *
     * @param string    $dni        DNI a partir del cual se va a generar el CUIL
     * @param string    $genero     1 = hombre, 2 = mujer
     *
     * @return string   El CUIL generado
     */
    public static function genCUIL( $dni, $genero ) {

        if ( $genero == 1 ) {
            $tipo = "20";
        } else {
            $tipo = "27";
        }

        $str = $tipo . $dni;
        $suma = 0;

        $suma += intval( substr( $str, 0, 1) ) * 5;
        $suma += intval( substr( $str, 1, 1) ) * 4;
        $suma += intval( substr( $str, 2, 1) ) * 3;
        $suma += intval( substr( $str, 3, 1) ) * 2;
        $suma += intval( substr( $str, 4, 1) ) * 7;
        $suma += intval( substr( $str, 5, 1) ) * 6;
        $suma += intval( substr( $str, 6, 1) ) * 5;
        $suma += intval( substr( $str, 7, 1) ) * 4;
        $suma += intval( substr( $str, 8, 1) ) * 3;
        $suma += intval( substr( $str, 9, 1) ) * 2;
        $resto = $suma % 11;
        $digito = 11 - $resto;

        switch ( $digito ){
            case 11:
                $digito = "0";
                break;
            case 10:
                $tipo = "23";
                if ( $genero == 1 ) {
                    $digito = "9";
                } else {
                    $digito = "4";
                }
                break;
        }

        return $tipo .'-'. $dni .'-'. $digito;
    }

}
