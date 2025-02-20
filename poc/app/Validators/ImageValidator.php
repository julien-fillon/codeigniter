<?php

namespace App\Validators;

class ImageValidator
{
    public static function rules(): array
    {
        return [
            'image' => [
                'uploaded[image]',
                'max_size[image,2048]', // 2MB max
                'is_image[image]',
                'mime_in[image,image/png,image/jpeg,image/jpg,image/gif]',
                'ext_in[image,png,jpg,jpeg,gif]'
            ],
            'name' => [
                'required',
                'min_length[3]',
                'max_length[255]',
                'regex_match[/^[a-zA-Z0-9\s_-]+$/]' //Authorizes letters, numbers, spaces, underscores and dashes
            ],
            'category' => [
                'required',
                'min_length[3]',
                'max_length[100]',
                'regex_match[/^[a-zA-Z0-9\s_-]+$/]'
            ]
        ];
    }
}
