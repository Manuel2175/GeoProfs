<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

uses( RefreshDatabase::class);
it('logs in the user successfully', function () {
    $user = User::factory()->create([
        'name' => 'Testuser',
        'password' => Hash::make('Password123'),
    ]);

    $response = $this->postJson('api/auth/request', [
        'name' => 'Testuser',
        'password' => 'Password123',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'user' => ['id', 'name'],
            'token',
        ]);
});
it('tries to log in the user with an wrong password', function () {
    Auth::shouldReceive('attempt')
        ->once()
        ->andReturn(false);
    // Act
    $response = $this->postJson('api/auth/request', [
        'name' => 'Testuser',
        'password' => 'WrongPassword123',
    ]);

    // Assert
    $response->assertStatus(401)
        ->assertJson([
            'message' => 'Invalid username or password'
        ]);
});
