<?php

namespace App\Validators;

class EventValidator
{
    /**
     * Return the validation rules for an event.
     *
     * @return array
     */
    public static function rules(): array
    {
        return [
            'event_name'         => 'required|min_length[3]|max_length[255]',
            'organizer_name'     => 'required|min_length[3]|max_length[100]',
            'organizer_surname'  => 'required|min_length[3]|max_length[100]',
            'organizer_phone'    => 'required|regex_match[/^\+?[0-9]{10,15}$/]',
            'organizer_email'    => 'required|valid_email|max_length[150]',
            'social_links'       => 'permit_empty|valid_multiple_urls',
        ];
    }

    /**
     * Return custom validation messages for event validation rules.
     *
     * @return array
     */
    public static function messages(): array
    {
        return [
            'social_links.valid_multiple_urls' => 'The social links field must contain valid URLs separated by commas.',
        ];
    }
}
