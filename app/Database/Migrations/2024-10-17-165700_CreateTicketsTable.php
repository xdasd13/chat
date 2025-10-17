<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTicketsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'cliente' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
            ],
            'problema' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'fechaHora' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pendiente', 'solucionado'],
                'default'    => 'pendiente',
                'null'       => false,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('averias');
    }

    public function down()
    {
        $this->forge->dropTable('averias');
    }
}
