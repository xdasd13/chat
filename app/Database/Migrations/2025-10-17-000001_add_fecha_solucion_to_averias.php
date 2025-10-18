<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFechaSolucionToAverias extends Migration
{
    public function up()
    {
        $this->forge->addColumn('averias', [
            'fecha_solucion' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'after'      => 'status'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('averias', 'fecha_solucion');
    }
}