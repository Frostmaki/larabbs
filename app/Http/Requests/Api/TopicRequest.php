<?php

namespace App\Http\Requests\Api;

use Dingo\Api\Http\FormRequest;

class TopicRequest extends FormRequest
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

        switch ($this->method()){
            case 'POST':
                return [

                    'title'       => 'required|min:2',
                    'body'        => 'required|min:3',
                    'category_id' => 'required|numeric',
                ];
                break;
            case 'PATCH':
                return [

                    'title'       => 'string',
                    'body'        => 'string',
                    'category_id' => 'exists:categories,id',
                ];
                break;
        }

    }
}
