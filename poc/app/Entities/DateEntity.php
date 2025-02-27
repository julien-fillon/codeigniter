<?php

namespace App\Entities;

use App\Repositories\ImageRepository;
use CodeIgniter\Entity\Entity;

class DateEntity extends Entity
{

    protected $attributes = [
        'id'       => null,
        'date'     => null,
        'location' => null,
        'image_id'    => null,
        'event_id'    => null,
        'created_at' => null,
        'updated_at' => null,
    ];

    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];

    /**
     * Return the details of the associated image.
     *
     * @return array|null
     */
    public function getImage(): ?array
    {
        if (empty($this->attributes['image_id'])) {
            return null;
        }

        $imageRepo = new ImageRepository();
        $image = $imageRepo->findById($this->attributes['image_id']);
        return ($image) ? $image->toArray() : null;
    }
}
