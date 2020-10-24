<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DirectionsRequest extends FormRequest
{
    public function rules()
    {
        return [
            'start' => 'required',
            'destination' => 'required',
            'hours' => 'required|numeric|in:0,1,2,3,4',
            'minutes' => 'required|numeric|in:0,15,30,45',
        ];
    }

    public function authorize()
    {
        return true;
    }
}