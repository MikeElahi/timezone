<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Test cases for TimeZoneCompatibilityController.
 * In this test case, we will test the compatibility of one time in timezone A with working hours of
 * timezone B, C, and D.
 *
 * Working hours are defined as 0900 to 1700 in the respective timezones. However, they can be
 * overridden by providing a "working_hours_start" and "working_hours_end" in the request.
 *
 * The request will accept an array of "destination_timezones" which will contain the timezones
 *
 * The request will result in 422 Unprocessable Entity if the payload is empty
 * The request will result in 422 Unprocessable Entity if the payload is missing the "time" key
 * The request will result in 422 Unprocessable Entity if the payload is missing the "timezone" key
 * The request will result in 422 Unprocessable Entity if the payload has invalid "time" key like "28:00"
 * The request will result in 422 Unprocessable Entity if the payload has invalid "timezone" key like "Asia/Invalid"
 * The request will result in 422 Unprocessable Entity if the payload has invalid "working_hours_start" key like "28:00"
 * The request will result in 422 Unprocessable Entity if the payload has invalid "working_hours_end" key like "28:00"
 * The request will result in 200 OK if the payload has valid "time" and "timezone" keys
 * The request will result in 200 OK if the payload has valid "time", "timezone", "working_hours_start", and "working_hours_end" keys
 *
 * The response will contain the general key "valid" which will be true if the time is within the working hours of ALL time zones
 * The response will contain the general key "valid" which will be false if the time is not within the working hours of all time zones
 * The response will contain the timezones key which will contain the timezones and the given time in those timezones
 */
class TimeZoneCompatibilityControllerTest extends TestCase
{
    /**
     * Test case for checking the compatibility of one time in timezone A with working hours of timezone B, C, and D.
     *
     * @return void
     */
    public function testTimeZoneCompatibility()
    {
        $response = $this->postJson('/timezones/compatibility', [
            'time' => '13:00',
            'timezone' => 'Asia/Tehran',
            'destination_timezones' => [
                'Europe/Istanbul',
            ],
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'valid' => true,
            'timezones' => [
                'Asia/Tehran' => '13:00',
                'Europe/Istanbul' => '12:30',
            ],
        ]);
    }

    /**
     * Test case for checking the compatibility of one time in timezone A with working hours of timezone B, C, and D.
     *
     * @return void
     */
    public function testTimeZoneCompatibilityWithWorkingHours()
    {
        $response = $this->postJson('/timezones/compatibility', [
            'time' => '12:00',
            'timezone' => 'Asia/Tehran',
            'destination_timezones' => [
                'Asia/Kolkata',
            ],
            'working_hours_start' => '09:00',
            'working_hours_end' => '17:00',
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'valid' => true,
            'timezones' => [
                'Asia/Tehran' => '12:00',
                'Asia/Kolkata' => '14:00',
            ],
        ]);
    }

    /**
     * Test case for checking the compatibility of one time in timezone A with working hours of timezone B, C, and D.
     *
     * @return void
     */
    public function testTimeZoneCompatibilityWithInvalidTime()
    {
        $response = $this->postJson('/timezones/compatibility', [
            'time' => '28:00',
            'timezone' => 'Asia/Tehran',
            'destination_timezones' => [
                'Europe/London',
            ],
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test case for checking the compatibility of one time in timezone A with working hours of timezone B, C, and D.
     *
     * @return void
     */
    public function testTimeZoneCompatibilityWithInvalidTimezone()
    {
        $response = $this->postJson('/timezones/compatibility', [
            'time' => '12:00',
            'timezone' => 'Asia/Invalid',
            'destination_timezones' => [
                'Europe/London',
            ],
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test case for checking the compatibility of one time in timezone A with working hours of timezone B, C, and D.
     *
     * @return void
     */
    public function testTimeZoneCompatibilityWithInvalidWorkingHoursStart()
    {
        $response = $this->postJson('/timezones/compatibility', [
            'time' => '12:00',
            'timezone' => 'Asia/Tehran',
            'destination_timezones' => [
                'Europe/London',
            ],
            'working_hours_start' => '28:00',
            'working_hours_end' => '17:00',
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test case for checking the compatibility of one time in timezone A with working hours of timezone B, C, and D.
     *
     * @return void
     */
    public function testTimeZoneCompatibilityWithInvalidWorkingHoursEnd()
    {
        $response = $this->postJson('/timezones/compatibility', [
            'time' => '12:00',
            'timezone' => 'Asia/Tehran',
            'destination_timezones' => [
                'Europe/London',
            ],
            'working_hours_start' => '09:00',
            'working_hours_end' => '28:00',
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test case for checking the compatibility of one time in timezone A with working hours of timezone B, C, and D.
     *
     * @return void
     */
    public function testTimeZoneCompatibilityWithEmptyPayload()
    {
        $response = $this->postJson('/timezones/compatibility');

        $response->assertStatus(422);
    }

    /**
     * Test case for checking the compatibility of one time in timezone A with working hours of timezone B, C, and D.
     *
     * @return void
     */
    public function testTimeZoneCompatibilityWithMissingTime()
    {
        $response = $this->postJson('/timezones/compatibility', [
            'timezone' => 'Asia/Tehran',
            'destination_timezones' => [
                'Europe/London',
            ],
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test case for checking the compatibility of one time in timezone A with working hours of timezone B, C, and D.
     *
     * @return void
     */
    public function testTimeZoneCompatibilityWithMissingTimezone()
    {
        $response = $this->postJson('/timezones/compatibility', [
            'time' => '12:00',
            'destination_timezones' => [
                'Europe/London',
            ],
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test case for checking the compatibility of one time in timezone A with working hours of timezone B, C, and D.
     *
     * @return void
     */
    public function testTimeZoneCompatibilityWithMissingDestinationTimezones()
    {
        $response = $this->postJson('/timezones/compatibility', [
            'time' => '12:00',
            'timezone' => 'Asia/Tehran',
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test case for checking the compatibility of one time in timezone A with working hours of timezone B, C, and D.
     *
     * @return void
     */
    public function testTimeZoneCompatibilityWithInvalidDestinationTimezones()
    {
        $response = $this->postJson('/timezones/compatibility', [
            'time' => '12:00',
            'timezone' => 'Asia/Tehran',
            'destination_timezones' => [
                'Europe/Invalid',
            ],
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test case for checking the compatibility of one time in timezone A with working hours of timezone B, C, and D.
     *
     * @return void
     */
    public function testTimeZoneCompatibilityWithInvalidDestinationTimezonesArray()
    {
        $response = $this->postJson('/timezones/compatibility', [
            'time' => '12:00',
            'timezone' => 'Asia/Tehran',
            'destination_timezones' => 'Europe/London',
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test case for checking the compatibility of one time in timezone A with working hours of timezone B, C, and D.
     *
     * @return void
     */
    public function testTimeZoneCompatibilityWithInvalidDestinationTimezonesArrayValues()
    {
        $response = $this->postJson('/timezones/compatibility', [
            'time' => '12:00',
            'timezone' => 'Asia/Tehran',
            'destination_timezones' => [
                'Europe/London',
                'Asia/Invalid',
            ],
        ]);

        $response->assertStatus(422);
    }
}
