<?php

declare(strict_types=1);

namespace App\Http\Requests\Setting;

use App\Enums\SettingEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSettingsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            '*.key' => [
                'required',
                'string',
                Rule::enum(SettingEnum::class),
            ],
            '*.value' => [
                'required',
            ],
        ];
    }
}
