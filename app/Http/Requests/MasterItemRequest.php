<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MasterItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'harga_beli' => ['required', 'integer', 'min:0'],
            'laba' => ['required', 'integer', 'min:0'],
            'supplier' => ['required', 'string'],
            'jenis' => ['required', 'string'],
            'kategori' => ['array'],
            'kategori.*' => ['integer', 'exists:kategori_items,id'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ];
    }
}