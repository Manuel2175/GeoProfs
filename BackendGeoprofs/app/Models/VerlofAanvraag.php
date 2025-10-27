<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerlofAanvraag extends Model
{
    //Velden die benodigd zijn voor verlofaanvraag model
    protected $fillable = [
        'user_id',
        'reden',
        'startdatum',
        'einddatum',
        'status',
        'afkeuringsreden',
    ];
    //Relatie met user de huidge verlofaanvraag moet behoren
    // tot een user daarmee kan een gebruiker die hieraan is gekoppeld makkelijk worden opgehaald
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
