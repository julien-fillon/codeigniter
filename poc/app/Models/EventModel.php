<?php

namespace App\Models;

use App\Entities\EventEntity;
use CodeIgniter\Model;

class EventModel extends Model
{
    protected $table            = 'events';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = EventEntity::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'event_name',
        'organizer_name',
        'organizer_surname',
        'organizer_phone',
        'organizer_email',
        'slug',
        'shorturl',
        'social_links',
        'qrcode',
        'created_at',
        'updated_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'event_name'       => 'required|max_length[255]',
        'organizer_name'   => 'required|max_length[100]',
        'organizer_surname' => 'required|max_length[100]',
        'organizer_phone'  => 'required|max_length[20]',
        'organizer_email'  => 'required|valid_email|max_length[150]',
        'social_links'     => 'permit_empty|valid_json',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
