<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class RoleDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (!app()->environment(['local', 'testing'])) {
            throw new \RuntimeException('Akun demo hanya untuk local/testing.');
        }
        foreach ([['ADM001', 'admin', 'Admin Praktikum', 'admin'],
            ['ADM002', 'admin2', 'Admin Kedua', 'admin'],
            ['STF001', 'staff', 'Staff Praktikum', 'staff']] as [$id, $username, $name, $role]) {
            if (User::find($id)) continue;
            $user = new User(['id_user' => $id, 'username' => $username, 'nama_lengkap' => $name,
                'email' => $username.'@example.test', 'password' => 'PraktikumW07!',
                'no_hp' => '080000000000', 'alamat' => 'Alamat dummy praktikum']);
            $user->role = $role;
            $user->save();
        }
    }
}
