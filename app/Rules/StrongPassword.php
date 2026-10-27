<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StrongPassword implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)
            || mb_strlen($value) < 8
            || !preg_match('/\p{L}/u', $value)
            || !preg_match('/\p{N}/u', $value)
            || !preg_match('/[^\p{L}\p{N}]/u', $value)) {
            $fail('Mật khẩu phải có ít nhất 8 ký tự, gồm chữ cái, chữ số và ký tự đặc biệt.');
        }
    }
}
