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
            'social_links'       => 'permit_empty|valid_url',
        ];
    }
}
