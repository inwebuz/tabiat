<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        factory(User::class)->create([
            'name' => 'Admin',
            'email' => 'admin2@admin.com',
            'role_id' => Role::where('name', 'admin')->firstOrFail(),
        ]);

        factory(User::class)->create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'role_id' => Role::where('name', 'administrator')->firstOrFail(),
        ]);

        factory(User::class)->create([
            'name' => 'Test',
            'email' => 'test@test.com',
            'role_id' => Role::where('name', 'user')->firstOrFail(),
        ]);

//        factory(User::class, 100)->create();

    }
}
