<?php

declare(strict_types=1);

namespace App\Http\Requests\Courier;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourierRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:100',
            ],
            'phone' => [
                'required',
                'string',
                'max:16',
                'unique:couriers,phone',
                'regex:/^\+[1-9]\d{1,14}$/', // E.164: +1234567890
            ],
        ];
    }
}
