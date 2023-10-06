<?php

namespace Tests\Feature;

use App\Models\Drug;
use App\Models\Pharmacy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_guest_cannot_access_homepage()
    {
        $response = $this->getJson('/api/analytical/dashboard');

        $response->assertStatus(401);
    }

    public function test_owner_can_access_homepage()
    {

        $response = $this->actingAs($this->owner)->getJson('/api/analytical/dashboard');

        $response->assertStatus(200);
    }
}
