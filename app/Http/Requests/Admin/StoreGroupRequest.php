<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreGroupRequest extends FormRequest
{
    /**
     * Tentukan apakah user berhak membuat request ini.
     */
    public function authorize(): bool
    {
        // Kita izinkan karena sudah dilindungi oleh middleware role:Admin di route
        return true;
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk request ini.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:groups,email',
            'parent_id' => 'nullable|exists:groups,id'
        ];
    }
}
