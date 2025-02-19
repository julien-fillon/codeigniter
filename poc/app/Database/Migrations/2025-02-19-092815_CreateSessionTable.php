<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSessionTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
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
        $this->forge->addKey('id', true);
        $this->forge->addKey('timestamp', false, false, 'timestamp_idx');

        // Create the table
        $this->forge->createTable('sessions');
    }

    public function down()
    {
        // Delete the table
        $this->forge->dropTable('sessions');
    }
}
