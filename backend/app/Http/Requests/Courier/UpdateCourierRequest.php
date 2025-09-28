<?php

declare(strict_types=1);

namespace App\Http\Requests\Courier;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourierRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'sometimes',
                'string',
                'min:3',
                'max:100'
            ],
            'phone' => [
                'sometimes',
                'string',
                'max:16',
                'unique:couriers,phone,'.$this->user?->id,
                'regex:/^\+[1-9]\d{1,14}$/', // E.164: +1234567890
            ],
        ];
    }
}
