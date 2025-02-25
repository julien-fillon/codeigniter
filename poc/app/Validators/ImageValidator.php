<?php

namespace App\Validators;

class ImageValidator
{

    /**
     *  Return the validation rules for an image.
     *
     * @param  boolean $isUpdate
     * @return array
     */
    public static function rules(bool $isUpdate = false): array
    {
        $rules = [
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

        if (!$isUpdate) { // If it is an addition, the image is compulsory
            $rules['image'] = [
                'uploaded[image]',
                'max_size[image,2048]', // 2MB max
                'is_image[image]',
                'mime_in[image,image/png,image/jpeg,image/jpg,image/gif]',
                'ext_in[image,png,jpg,jpeg,gif]'
            ];
        } else { // If it is an edition, the image is optional
            $rules['image'] = [
                'max_size[image,2048]',
                'is_image[image]',
                'mime_in[image,image/png,image/jpeg,image/jpg,image/gif]',
                'ext_in[image,png,jpg,jpeg,gif]'
            ];
        }

        return $rules;
    }
}
