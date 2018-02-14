<?php

use Illuminate\Database\Seeder;

/**
 * Created by PhpStorm.
 * User: andersonaltissimo
 * Date: 1/6/18
 * Time: 13:28
 */

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        factory(\App\Models\User::class)->create([
           'email' => 'ander.altissimo@gmail.com'
        ]);
    }
}