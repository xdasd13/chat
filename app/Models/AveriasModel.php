<?php

namespace App\Models;

use CodeIgniter\Model;

class AveriasModel extends Model
{
    protected $table = 'averias';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['cliente', 'problema', 'fechaHora', 'status'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'cliente' => 'required|max_length[50]',
        'problema' => 'required|max_length[100]'
    ];
    protected $validationMessages = [
        'cliente' => [
            'required' => 'El nombre del cliente es obligatorio',
            'max_length' => 'El nombre del cliente no puede exceder 50 caracteres'
        ],
        'problema' => [
            'required' => 'La descripción del problema es obligatoria',
            'max_length' => 'La descripción del problema no puede exceder 100 caracteres'
        ]
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
