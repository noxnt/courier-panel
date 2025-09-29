<?php

declare(strict_types=1);

namespace App\Http\Requests\CourierLocation;

use App\Rules\StrictInteger;
use Illuminate\Foundation\Http\FormRequest;

class StoreCourierLocationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'courier_id' => [
                'required',
                'exists:couriers,id',
                new StrictInteger(),
            ],
            'lat' => [
                'required',
                'numeric',
                'between:-90,90',
            ],
            'lng' => [
                'required',
                'numeric',
                'between:-180,180',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'lat.required' => 'Latitude is required.',
            'lat.numeric' => 'Latitude must be a valid number.',
            'lat.between' => 'Latitude must be between -90 and 90.',

            'lng.required' => 'Longitude is required.',
            'lng.numeric' => 'Longitude must be a valid number.',
            'lng.between' => 'Longitude must be between -180 and 180.',
        ];
    }
}
