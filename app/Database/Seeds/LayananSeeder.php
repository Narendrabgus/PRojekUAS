<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LayananSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama' => 'Servis Rutin Motor Bebek',
                'harga' => 75000,
                'estimasi_menit' => 45,
                'foto' => 'servis_rutin.jpg',
                'created_at' => date("Y-m-d H:i:s"),
            ],
            [
                'nama' => 'Ganti Oli Motor Matic',
                'harga' => 50000,
                'estimasi_menit' => 15,
                'foto' => 'ganti_oli.jpg',
                'created_at' => date("Y-m-d H:i:s"),
            ],
            [
                'nama' => 'Tambal Ban Tubeless',
                'harga' => 25000,
                'estimasi_menit' => 10,
                'foto' => 'tambal_ban.jpg',
                'created_at' => date("Y-m-d H:i:s"),
            ]
        ];
        foreach ($data as $item) {
            $this->db->table('layanan')->insert($item);
        }
    }
}
