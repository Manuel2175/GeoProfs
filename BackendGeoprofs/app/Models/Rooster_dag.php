<?php

namespace App\Models;

use Carbon\Traits\Week;
use Illuminate\Database\Eloquent\Model;

class Rooster_dag extends Model
{
    protected $table = 'rooster_dags';
    protected $fillable = ['id', 'name', 'ochtend', 'middag', 'rooster_weeks_id'];

    public function week()
    {
        return $this->belongsTo(Rooster_week::class, 'rooster_weeks_id');
    }
}
