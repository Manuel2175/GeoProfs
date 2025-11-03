    <?php
    use \App\Models\VerlofAanvraag;
    use Laravel\Sanctum\HasApiTokens;
    use App\Models\User;
    use Illuminate\Foundation\Testing\RefreshDatabase;

    uses( RefreshDatabase::class);
    test('user can create a VerlofAanvraag', function () {
        // Create a test user
        $user = User::factory()->create();

        // Authenticate as that user
        $this->actingAs($user, 'sanctum');

        // Send POST to the correct route
        $response = $this->postJson("/api/user/{$user->id}/verlofaanvraag", [
            'startdatum' => '2025-12-04',
            'einddatum' => '2025-12-24',
            'reden' => 'ziekte',
            'status'=> 'aangevraagd',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('verlof_aanvraags', [
            'user_id' => $user->id,
            'startdatum' => '2025-12-04',
            'einddatum' => '2025-12-24',
            'reden' => 'ziekte',
            'status'=> 'aangevraagd',
        ]);
    });
