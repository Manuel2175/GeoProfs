<?php

use App\Models\User;
use App\Models\VerlofAanvraag;
use App\Notifications\VerlofAangevraagdNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

it('stuurt een notificatie naar een manager/admin wanneer verlof wordt aangevraagd', function () {

    // Fake notifications (belangrijk!)
    Notification::fake();

    // Admin (ontvanger)
    $admin = User::create([
        'name' => 'Admin',
        'surname' => 'User',
        'verlofsaldo' => 20,
        'role' => 'admin',
        'password' => bcrypt('password'),
    ]);

    // Gewone gebruiker
    $user = User::create([
        'name' => 'Test',
        'surname' => 'User',
        'verlofsaldo' => 5,
        'role' => 'gebruiker',
        'password' => bcrypt('password'),
    ]);

    // Verlofaanvraag
    $verlofAanvraag = VerlofAanvraag::create([
        'user_id' => $user->id,
        'startdatum' => '2025-12-04',
        'einddatum' => '2025-12-05',
        'reden' => 'ziekte',
        'status' => 'aangevraagd',
    ]);

    // Act: verstuur notificatie
    $admin->notify(new VerlofAangevraagdNotification($verlofAanvraag));

    // Assert: notificatie is verstuurd naar admin
    Notification::assertSentTo(
        $admin,
        VerlofAangevraagdNotification::class,
        function ($notification) use ($verlofAanvraag) {
            return $notification->verlofAanvraag->id === $verlofAanvraag->id;
        }
    );
});
