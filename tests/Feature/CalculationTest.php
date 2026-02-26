<?php

use App\Models\Calculations;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can visit the homepage', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('can fetch all calculations', function () {
    Calculations::factory()->count(3)->create();

    $response = $this->getJson('/api/calculations');

    $response->assertStatus(200);
    $response->assertJsonCount(3);
});

test('can store a calculator', function () {
    $response = $this->postJson('/api/calculations', [
        'expression' => '9 + 3 = 12',
        'result' => 12,
    ]);

    $response->assertStatus(201);
    $response->assertJsonFragment(['expression' => '9 + 3 = 12']);
});
