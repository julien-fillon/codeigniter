<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSessionTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'        => [
                'type'       => 'VARCHAR',
                'constraint' => 128,
                'null'       => false,
            ],
            'ip_address'         => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => false,
            ],
            'timestamp'      => [
                'type'       => 'INT',
                'null'       => false,
                'default'    => 0,
            ],
            'data' => [
                'type'       => 'BLOB',
                'null'       => false,
            ],
        ]);

        // Define the primary key
        $this->forge->addKey(['id', 'ip_address'], true);
        $this->forge->addKey('timestamp', false, false, 'timestamp_idx');

        // Create the table
        $this->forge->createTable('ci_sessions');
    }

    public function down()
    {
        // Delete the table
        $this->forge->dropTable('ci_sessions');
    }
}
