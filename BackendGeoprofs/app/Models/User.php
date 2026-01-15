<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Date;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'surname',
        'password',
        'verlofsaldo'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function verlofaanvraags()
    {
        return $this->hasMany(VerlofAanvraag::class);
    }

    public function HR(){
        return $this->where('role', 'HR');
    }

    public function manager(){
        return $this->where('role', 'Manager');
    }

    public function worker()
    {
        return $this->where('role', 'worker');
    }
    public function admin()
    {
        return $this->where('role', 'admin');
    }
    public function roosters()
    {
        return $this->belongsToMany(
            Rooster_week::class,
            'rooster_week_user',
            'user_id',
            'rooster_week_id'
        );
    }
    public function vandaag()
    {
        $datum = Date::now();
        Date::setLocale('nl');
        $week = Rooster_week::where('weeknummer', $datum->isoWeek)->first();
        return $week->dagen()->where('name', $datum->translatedFormat('l'))->first();
    }
}
