<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $activityId = $this->route('activity') ? $this->route('activity')->id : null;

        return [
            'category_id' => ['required', 'exists:categories,id'],
            'code'        => ['required', 'string', Rule::unique('activities', 'code')->ignore($activityId)],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            // PERBAIKAN: Diubah jadi huruf kecil semua
            'status'      => ['required', 'in:draft,planned,ongoing,done'],
            'start_at'    => ['required', 'date'],
            'end_at'      => ['required', 'date', 'after_or_equal:start_at'],
            'location'    => ['nullable', 'string'],
            'quota'       => ['required', 'integer', 'min:1', 'max:500'],
        ];
    }
}