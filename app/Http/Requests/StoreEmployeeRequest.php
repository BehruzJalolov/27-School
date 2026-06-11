<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name_uz' => 'required|string|max:255',
            'name_ru' => 'required|string|max:255',
            'phone'   => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'work_time'   => 'required|string|max:255',
            'category_id' => 'required|exists:emp_categories,id',
            'position_id' => 'required|exists:positions,id',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}
