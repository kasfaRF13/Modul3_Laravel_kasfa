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
    // Mengambil ID activity yang sedang di-update agar unique code mengabaikan dirinya sendiri
        $activityId = $this->route('activity') ? $this->route('activity')->id : null;

        return [
            'category_id' => ['required', 'exists:categories,id'],
            'code'        => ['required', 'string', 'unique:activities,code,' . $activityId],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status'      => ['required', 'in:Draft,Published,Completed,Cancelled'],
            'start_at'    => ['required', 'date'],
            'capacity'    => ['required', 'integer', 'min:1'],
        ];
    }
}