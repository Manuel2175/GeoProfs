<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rooster_week extends Model
{
    protected $table = 'rooster_weeks';
    protected $fillable = ['weeknummer'];
    public function dagen()
    {
        return $this->hasMany(Rooster_dag::class, 'rooster_weeks_id');
    }
    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'rooster_week_user',
            'rooster_week_id',
            'user_id'
        );
    }
}
