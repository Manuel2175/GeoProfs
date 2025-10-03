<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerlofAanvraag extends Model
{
    protected $fillable = [
        'user_id',
        'reden',
        'startdatum',
        'einddatum',
        'status',
        'afkeuringsreden',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
