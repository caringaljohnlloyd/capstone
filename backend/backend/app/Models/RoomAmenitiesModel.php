<?php

namespace App\Models;

use CodeIgniter\Model;

class RoomAmenitiesModel extends Model
{
    protected $table            = 'room_amenities';
    protected $primaryKey       = ['room_id','amenity_id']; // No primary key, it's a composite primary key
    protected $returnType       = 'array';
    protected $useAutoIncrement = false; 
    protected $protectFields    = true;
    protected $allowedFields    = ['room_id', 'amenity_id','stock'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
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
