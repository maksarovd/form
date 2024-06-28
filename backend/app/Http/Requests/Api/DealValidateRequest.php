<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class DealValidateRequest extends FormRequest
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
            'Deal_Name' => 'required|string',
            'Stage' => 'required|string',
            'Amount' => 'required',
            'Closing_Date' => 'required|date',
            'Account_Name.name' => 'required|string',
            'Account_Name.id' => 'required|string',
        ];
    }
}
