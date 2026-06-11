<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStatisticRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'classesCount'   => 'required|integer',
            'studentsCount'  => 'required|integer',
            'teachersCount'  => 'required|integer',
            'graduatesCount' => 'required|integer',
        ];
    }
}
