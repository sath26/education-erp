<?php

use App\Models\User;
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
        factory(User::class)->create([
            'name' => 'Anderson Altissimo',
            'email' => 'ander.altissimo@gmail.com',
            'enrolment' => '100000'
        ])->each(function (User $user) {
            User::assignRole($user, User::ROLE_ADMIN);
            $user->save();
        });

        factory(User::class, 3)->create()->each(function (User $user) {
            if (!$user->entity) {
                User::assignRole($user, User::ROLE_PROFESSOR);
                User::assignEnrolment($user, User::ROLE_PROFESSOR);
                $user->save();
            }
        });

        factory(User::class, 30)->create()->each(function (User $user) {
            if (!$user->entity) {
                User::assignRole($user, User::ROLE_STUDENT);
                User::assignEnrolment($user, User::ROLE_STUDENT);
                $user->save();
            }
        });
    }
}