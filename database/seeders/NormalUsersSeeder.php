<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\NormalUser;

class NormalUsersSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('normal_users')) {
            return;
        }

        $users = User::where('user_type', 'normal')->get();
        foreach ($users as $user) {
            NormalUser::firstOrCreate(['user_id' => $user->id]);
        }
    }
}