<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class validateUpdateScholarsRequest extends FormRequest
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
            'scholar_id'=> 'required',
            'student_id_number' => 'required',
            'lastname' => 'required',
            'firstname' => 'required',
            'middlename' => 'required',
            'addressId' => 'required',
            'date_of_birth' => 'required|min:10|max:10',
            'age' => 'required|min:2|max:2',
            'gender' => 'required|min:4|max:6',
            'schoolId' => 'required',
            'courseId' => 'required',
            'section' => 'required',
            'year_level' => 'required',
            'IP' => 'required'
        ];
    }
}
