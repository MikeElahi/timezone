<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListAllTimeZonesControllerTest extends TestCase
{
    /**
     * Test that upon calling "/timezones" the response is a JSON object
     * Test that the response contains the key "data" with an array of timezones with at least 1 timezone
     * Test that each time zone has a "name" like "Europe/Amsterdam" and an offset like "+01:00"
     * Test that each time zone has a "is_dst" key with a boolean value
     */
    public function test_list_all_time_zones()
    {
        $response = $this->getJson('/timezones');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'name',
                        'offset',
                        'is_dst',
                    ]
                ]
            ]);
        $response->assertJsonFragment([
            'name' => 'UTC',
            'offset' => '+00:00',
            'is_dst' => false,
        ]);
    }

}
