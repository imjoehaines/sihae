<?php

namespace Sihae\Http\Requests;

use Sihae\Http\Requests\Request;

class NewPostRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // yolo
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
            'title' => 'required|max:140|string',
            'summary' => 'required|max:255|string',
            'body' => 'required|max:7500|string',
        ];
    }
}
