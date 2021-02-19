<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateAcademiSemesterYearContractRequest extends FormRequest
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
        return [
            'semester' => 'required',
            'academic_year' => 'required',
            'undergraduate_amount' => 'required',
            'masteral_doctorate_amount' => 'required',
        ];
    }
}
