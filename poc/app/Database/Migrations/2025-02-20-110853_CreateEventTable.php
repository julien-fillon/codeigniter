<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEventTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'event_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'organizer_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'organizer_surname' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'organizer_phone' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'organizer_email' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true,
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'shorturl' => [
                'type'       => 'VARCHAR',
                'constraint' => '7',
                'null'       => true,
            ],
            'qrcode' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'social_links' => [
                'type'       => 'TEXT',
                'null'       => true,
                'comment'    => 'JSON string containing links to social media, e.g., {"facebook":"url", "twitter":"url"}',
            ],
            'created_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'updated_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('id', true); // Primary Key
        $this->forge->createTable('events');
    }

    public function down()
    {
        $this->forge->dropTable('events');
    }
}
