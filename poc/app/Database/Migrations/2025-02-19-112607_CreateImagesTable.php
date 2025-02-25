<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateImagesTable extends Migration
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
            'category' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'path' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'size' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'width' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'height' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['png', 'jpeg', 'webp', 'gif', 'bmp'],
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'       => true,
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'       => true,
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('images');
    }

    public function down()
    {
        $this->forge->dropTable('images');
    }
}
