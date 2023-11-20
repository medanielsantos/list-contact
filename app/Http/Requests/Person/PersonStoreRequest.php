<?php

namespace App\Http\Requests\Person;

use Illuminate\Foundation\Http\FormRequest;

class PersonStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255|unique:people,name',
            'is_favorite' => 'nullable|boolean',
            'is_active'   => 'nullable|boolean',
        ];
    }
}
