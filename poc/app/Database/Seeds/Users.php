<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Users extends Seeder
{
    public function run()
    {
        // Exemple de génération de données pour 3 utilisateurs
        $data = [
            [
                'name'       => 'Julien Fillon',
                'email'      => 'jfillon@netinvestissement.fr',
                'password'   => password_hash(env('DEFAULT_PASSWORD', 'fallbackPassword'), PASSWORD_BCRYPT),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
