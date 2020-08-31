<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDirectorioRequest extends FormRequest
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
        //dd($this->route('directorio')->id);

        $id = ($this->route('directorio')->id);
        return [
            'name' => 'required|min:5|max:100',
            'email' => 'required|email|unique:directorios,email,'. $id,
            'phone' => 'required|unique:directorios,phone,'. $id
        ];
    }
}
