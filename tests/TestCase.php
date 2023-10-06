<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use App\Models\Pharmacy;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $owner;
    protected $user;
    protected $pharmacy;
    protected static $setUpHasRunOnce = false;
    protected function setUp(): void
    {

        parent::setUp();
        if (!static::$setUpHasRunOnce)
        {
            Artisan::call('migrate:fresh');
            Artisan::call(
                'db:seed',
                ['--class' => 'TestSeeder']
            );
            static::$setUpHasRunOnce = true;
        }

        $this->pharmacy = $this->create_pharmacy();
        $this->owner = $this->create_user(1, $this->pharmacy);
        $this->user = $this->create_user(0, $this->pharmacy);
    }
    protected function create_pharmacy()
    {
        $instance = Pharmacy::factory()->create();
        return $instance;
    }
    protected function create_user($isOwner, $pharmacy)
    {
        $instance = User::factory()->for($pharmacy)->create(['isOwner' => $isOwner]);
        return $instance;
    }
}
