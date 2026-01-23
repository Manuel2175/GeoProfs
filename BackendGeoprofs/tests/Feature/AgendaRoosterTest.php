<?php

use App\Models\Rooster_dag;
use App\Models\Rooster_week;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
uses(RefreshDatabase::class);
it('ophaalt week rooster zonder factory', function () {
    $dagen = [
        "Maandag",
        "Dinsdag",
        "Woensdag",
        "Donderdag",
        "Vrijdag",
    ];
    $user = User::factory()->create([
        'name' => 'Testuser',
        'password' => Hash::make('Password123'),
    ]);

    Sanctum::actingAs($user);

    // 2️⃣ Rooster week handmatig aanmaken
    $roosterWeek = Rooster_week::create([
        'user_id' => $user->id,
        'weeknummer' => 1,
        'jaar' => 2026,
    ]);

    $user->roosters()->attach($roosterWeek->id);
    for ($i = 0; $i < 5; $i++) {
        Rooster_dag::create([
            "name" => $dagen[$i],
            "rooster_weeks_id" => $roosterWeek->id,
        ]);
    }

    // 4️⃣ API call
    $response = $this->getJson(
        "/api/user/{$user->id}/rooster_week/{$roosterWeek->id}"
    );

    // 5️⃣ Assertions
    $response
        ->assertStatus(200)
        ->assertJsonFragment([
            'id' => $roosterWeek->id,
            'weeknummer' => 1,
        ])
        ->assertJsonCount(5, 'dagen');
});

