<?php

use App\Models\User;
use App\Models\VerlofAanvraag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);
it('verlofaanvraag wordt goedgekeurd en opgeslagen', function () {
    $admin = User::create([
        'name' => 'Admin',
        'surname' => 'User',
        'verlofsaldo' => 20,
        'role' => 'admin',
        'password' => Hash::make('password'),
    ]);

    // Maak gewone gebruiker
    $user = User::create([
        'name' => 'Test',
        'surname' => 'User',
        'verlofsaldo' => 5,
        'role' => 'gebruiker',
        'password' => bcrypt('password'),
    ]);

    // Maak verlofaanvraag
    $aanvraag = VerlofAanvraag::create([
        'user_id' => $user->id,
        'startdatum' => '2025-12-04',
        'einddatum' => '2025-12-24',
        'reden' => 'ziekte',
        'status' => 'aangevraagd',
    ]);

    // Log in als admin
    $this->actingAs($admin, 'sanctum');

    // API call
    $response = $this->putJson("/verlofaanvraag/{$aanvraag->id}/approve", [
        'status' => 'goedgekeurd',
    ]);

    // Check status
    $response->assertStatus(200);

    // Check database
    $this->assertDatabaseHas('verlof_aanvraags', [
        'id' => $aanvraag->id,
        'status' => 'goedgekeurd',
    ]);

    // Check dat saldo van gebruiker is verlaagd
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'verlofsaldo' => 4,
    ]);
});


