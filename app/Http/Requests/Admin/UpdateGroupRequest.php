<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // <-- Jangan lupa import

class UpdateGroupRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            // 'email' harus unik, tapi abaikan email dari grup yang sedang kita edit
            'email' => ['nullable', 'email', Rule::unique('groups')->ignore($this->group)],
            'parent_id' => 'nullable|exists:groups,id'
        ];
    }
}
