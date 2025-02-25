<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEventImagesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'event_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'image_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'created_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'default'    => null,
            ],
        ]);

        $this->forge->addKey('event_id', true);
        $this->forge->addKey('image_id', true);
        $this->forge->addForeignKey('event_id', 'events', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('image_id', 'images', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('event_images');
    }

    public function down()
    {
        $this->forge->dropTable('event_images');
    }
}
