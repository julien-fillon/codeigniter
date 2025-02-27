<?php

namespace App\Validators;

class DateValidator
{
    /**
     * Return the validation rules for a date.
     *
     * @return array
     */
    public static function rules(): array
    {
        return [
            'date'     => 'required|valid_date',
            'location' => 'required|max_length[255]',
            'image_id' => 'permit_empty|is_natural_no_zero',
            'event_id' => 'permit_empty|is_natural_no_zero',
        ];
    }
}
