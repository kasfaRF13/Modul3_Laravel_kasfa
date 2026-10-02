<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'code'        => ['required', 'string', 'unique:activities,code'],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status'      => ['required', 'in:Draft,Published,Completed,Cancelled'],
            'start_at'    => ['required', 'date'],
            'capacity'    => ['required', 'integer', 'min:1'],
        ];
    }
}