<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'email'         => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'unique'     => true,
            ],
            'password'      => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'name'          => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'created_at'    => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'updated_at'    => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ]);

        // Define the primary key
        $this->forge->addKey('id', true);

        // Create the table
        $this->forge->createTable('users');
    }

    public function down()
    {
        // Delete the table
        $this->forge->dropTable('users');
    }
}
