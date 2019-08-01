<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LiquidacionMesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->ajax()) {
            $rules = array(
                'liquidacion.empleado.dni' => 'required|string|max:8',
                'liquidacion.rol' => 'required|numeric',
                'liquidacion.cargo' => 'required|string'
            );
        }
        return [];
    }
}
