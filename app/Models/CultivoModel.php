<?php

namespace App\Models;

use CodeIgniter\Model;

class CultivoModel extends Model
{
    protected $table = 'cultivos';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nombre_planta',
        'variedad',
        'fecha_siembra',
        'dias_cosecha_estimados',
        'frecuencia_riego_dias',
        'ultimo_riego',
        'estado',
    ];

    protected $useTimestamps = true;
}