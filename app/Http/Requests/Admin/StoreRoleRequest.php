<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Nama role harus diisi, unik, dan maksimal 255 karakter
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array' // Permissions boleh kosong
        ];
    }
}
