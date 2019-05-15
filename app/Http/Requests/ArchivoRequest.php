<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArchivoRequest extends FormRequest
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
        $today = today();
        return [
            'anio' => 'required|numeric|max:' . $today->year,
            'mes' => 'required|numeric|min:1|max:12',
            'nivel' => 'required|numeric|min:0|max:4',
            'tipo' => 'required|numeric|min:1|max:3',
            'numero' => $this->numero != null ? 'numeric' : '',
            'grupo' => 'required|numeric',
            'archivo' => 'required|mimes:pdf,xls,xlxs|max:15360'
        ];
    }
}
