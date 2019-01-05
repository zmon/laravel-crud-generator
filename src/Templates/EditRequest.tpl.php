<?php

namespace [[appns]]Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class [[model_uc]]EditRequest extends FormRequest
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

        $id = $this->route('[[model_singular]]');

        return [

         //   'email' => 'required|unique:members,email,' . $id . ',id|max:255',

            [[foreach:columns]]
            '[[i.name]]' => '[[i.validation]]',
            [[endforeach]]


        ];
    }
}


