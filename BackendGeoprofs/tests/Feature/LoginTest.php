<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

it('logs in the user successfully', function () {
    Auth::shouldReceive('attempt')
        ->once()
        ->andReturn(true);

    Auth::shouldReceive('user')
        ->once()
        ->andReturn((object)['name' => 'Testuser']);

    // Act
    $response = $this->postJson('api/auth/request', [
        'name' => 'Testuser',
        'password' => 'Password123',
    ]);

    // Assert
    $response->assertStatus(200)
        ->assertJsonFragment([
            'name' => 'Testuser',
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
