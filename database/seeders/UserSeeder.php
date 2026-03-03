<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Dr. Murhaban, ST., M.Cs',
            'email' => 'murhabani@gmail.com',
            'password' => bcrypt('dosenutu'),
        ]);

        $user->markEmailAsVerified();
    }
}
