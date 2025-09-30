<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StrictInteger implements Rule
{
    public function passes($attribute, $value): bool
    {
        return is_int($value);
    }

    public function message(): string
    {
        return 'The :attribute must be an integer (strict).';
    }
}
