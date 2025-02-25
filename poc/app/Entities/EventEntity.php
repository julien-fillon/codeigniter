<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class EventEntity extends Entity
{

    protected $attributes = [
        'id'         => null,
        'event_name' => null,
        'organizer_name' => null,
        'organizer_surname' => null,
        'organizer_phone' => null,
        'organizer_email' => null,
        'slug' => null,
        'shorturl' => null,
        'social_links' => null,
        'qrcode' => null,
        'created_at' => null,
        'updated_at' => null,
    ];

    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];

    public function getSocialLinks()
    {
        return json_decode($this->attributes['social_links'], true);
    }
}
