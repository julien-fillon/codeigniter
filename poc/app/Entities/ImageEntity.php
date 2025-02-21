<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class ImageEntity extends Entity
{

    public const CATEGORY_EVENT = "event";
    public const CATEGORY_DATE = "date";

    protected $attributes = [
        'id'         => null,
        'category' => null,
        'name' => null,
        'path' => null,
        'size' => null,
        'width' => null,
        'height' => null,
        'type' => null,
        'created_at' => null,
        'updated_at' => null,
    ];

    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];
}
