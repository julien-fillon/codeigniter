<?php

namespace App\Validators;

class AuthValidator
{
    public static function loginRules(): array
    {
        return [
            'email' => [
                'required',
                'valid_email',
                'max_length[255]'
            ],
            'password' => [
                'required',
                'min_length[6]',
                'max_length[255]'
            ]
        ];
    }
}
