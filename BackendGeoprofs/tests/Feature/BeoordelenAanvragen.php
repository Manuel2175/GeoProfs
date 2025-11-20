<?php

use App\Models\User;
use App\Models\VerlofAanvraag;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);
it('verlofaanvraag wordt goedgekeurd en opgeslagen', function () {
    $user = User::factory()->create();
    $aanvraag = VerlofAanvraag::factory()->create([
        'user_id' => $user->id,
        'startdatum' => '2025-12-04',
        'einddatum' => '2025-12-24',
        'reden' => 'ziekte',
        'status' => 'aangevraagd',
    ]);
    $this->actingAs($user, 'sanctum');
    $response = $this->putJson("/verlofaanvraag/{$aanvraag->id}/approve", [
        "status" => "goedgekeurd",
    ]);
    $response->assertStatus(200);
    $this->assertDatabaseHas('verlof_aanvraags', [
        'status' => 'goedgekeurd',
    ]);
});
