<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table = 'menu';
    protected $primaryKey = 'menu_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['item_name', 'item_category', 'item_price','item_image'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = ''; // Set if you want to track updates
    protected $deletedField = '';

    // Validation
    protected $validationRules = [
        'item_name' => 'required|min_length[2]',
        'item_category' => 'required',
        'item_price' => 'required|numeric', // Ensure it's numeric
    ];

    protected $validationMessages = [
        'item_name' => [
            'required' => 'Item name is required.',
            'min_length' => 'Item name must be at least 2 characters long.',
        ],
        'item_category' => [
            'required' => 'Item category is required.',
        ],
        'item_price' => [
            'required' => 'Item price is required.',
            'numeric' => 'Item price must be a number.',
        ],
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];
}
