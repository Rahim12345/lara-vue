<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
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
            'name'=>'required|max:25',
            'lastname'=>'required|max:25',
            'fatherName'=>'required|max:25',
        ];
    }

    public function attributes()
    {
        return[
            'name'=>'AD',
            'lastname'=>'SOYAD',
            'fatherName'=>'ATA ADI',
        ];
    }
}
