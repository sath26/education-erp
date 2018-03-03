<?php

namespace App\Models;

use App\Notifications\UserCreated;
use Bootstrapper\Interfaces\TableInterface;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $enrolment
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEnrolment($value)
 */
class User extends Authenticatable implements TableInterface
{
    use Notifiable;

    const ROLE_ADMIN = 1;
    const ROLE_PROFESSOR = 2;
    const ROLE_STUDENT = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'enrolment'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function entity()
    {
        return $this->morphTo();
    }

    /**
     * A list of headers to be used when a table is displayed
     *
     * @return array
     */
    public function getTableHeaders()
    {
        return [
            'Cód',
            'Nome',
            'E-mail'
        ];
    }

    /**
     * Get the value for a given header. Note that this will be the value
     * passed to any callback functions that are being used.
     *
     * @param string $header
     * @return mixed
     */
    public function getValueForHeader($header)
    {
        switch ($header) {
            case 'Cód':
                return $this->id;

            case 'Nome':
                return $this->name;

            case 'E-mail':
                return $this->email;
        }
    }

    /**
     * @param Array $data
     */
    protected static function createFully($data)
    {
        $password = str_random(6);
        $data['password'] = $password;

        /** @var User $user */
        $user = parent::create($data + ['enrolment' => str_random(6)]);

        self::assignEnrolment($user, $data['type']);
        self::assignRole($user, $data['type']);

        $user->save();

        if (isset($data['send_mail'])) {
            $token = \Password::broker()->createToken($user);
            $user->notify(new UserCreated($token));
        }

        return compact('user', 'password');
    }

    /**
     * @param User $user
     * @param $type
     */
    public static function assignEnrolment($user, $type)
    {
        $types = [
            self::ROLE_ADMIN => 100000,
            self::ROLE_PROFESSOR => 400000,
            self::ROLE_STUDENT => 700000
        ];

        $user->enrolment = $types[$type] + $user->id;
        return $user->enrolment;
    }

    /**
     * @param User $user
     * @param $type
     */
    public static function assignRole(User $user, $type)
    {
        $types = [
            self::ROLE_ADMIN => Admin::class,
            self::ROLE_PROFESSOR => Teacher::class,
            self::ROLE_STUDENT => Student::class
        ];

        $model = $types[$type];
        $model = $model::create([]);

        $user->entity()->associate($model);
    }
}
