<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TicketsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'cliente'   => 'Juan Pérez',
                'problema'  => 'No puede acceder al sistema',
                'fechaHora' => '2024-10-15 09:30:00',
                'status'    => 'pendiente'
            ],
            [
                'cliente'   => 'María García',
                'problema'  => 'Error en la facturación',
                'fechaHora' => '2024-10-16 14:15:00',
                'status'    => 'solucionado'
            ],
            [
                'cliente'   => 'Carlos López',
                'problema'  => 'Problema con la conexión a internet',
                'fechaHora' => '2024-10-17 11:45:00',
                'status'    => 'pendiente'
            ]
        ];

        // Insertar los datos en la tabla averias
        $this->db->table('averias')->insertBatch($data);
    }
}
