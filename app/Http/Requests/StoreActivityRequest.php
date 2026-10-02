<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'code'        => ['required', 'string', 'max:30', 'unique:activities,code'],
            'title'       => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'start_at'    => ['required', 'date'],
            'end_at'      => ['required', 'date', 'after_or_equal:start_at'],
            'location'    => ['required', 'string', 'max:255'],
            'capacity'    => ['required', 'integer', 'min:1', 'max:500'],
        ];
    }
}