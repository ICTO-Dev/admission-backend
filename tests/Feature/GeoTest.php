<?php

namespace Tests\Feature;

use Tests\TestCase;

class GeoTest extends TestCase
{
    public function test_can_fetch_regions(): void
    {
        $response = $this->getJson('/api/geo/regions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name'],
                ],
            ]);
    }
}
