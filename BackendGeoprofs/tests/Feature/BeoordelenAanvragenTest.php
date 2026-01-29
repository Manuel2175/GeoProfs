<?php

use App\Models\User;
use App\Models\VerlofAanvraag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);
it('verlofaanvraag wordt goedgekeurd en opgeslagen', function () {
    $admin = User::factory()->create([
        'name' => 'Admin',
        'surname' => 'User',
        'verlofsaldo' => 20,
        'role' => 'manager',
        'password' => bcrypt('secret'),
    ]);

    // Maak gewone gebruiker
    $user = User::factory()->create([
        'name' => 'Test',
        'surname' => 'User',
        'verlofsaldo' => 5,
        'role' => 'worker',
        'password' => bcrypt('secret'),
    ]);

    // Maak verlofaanvraag
    $aanvraag = VerlofAanvraag::create([
        'user_id' => $user->id,
        'startdatum' => '2026-12-04',
        'einddatum' => '2026-12-24',
        'reden' => 'ziekte',
        'status' => 'aangevraagd',
    ]);

    // Log in als admin
    $this->actingAs($admin, 'sanctum');

    // API call
    $response = $this->putJson("/api/user/{$user->id}/verlofaanvraag/{$aanvraag->id}/approve", [
        'status' => 'Goedgekeurd',
    ]);

    // Check status
    $response->assertStatus(200);

    // Check database
    $this->assertDatabaseHas('verlof_aanvraags', [
        'id' => $aanvraag->id,
        'status' => 'Goedgekeurd',
    ]);

    // Check dat saldo van gebruiker is verlaagd
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'verlofsaldo' => 4,
    ]);
});
