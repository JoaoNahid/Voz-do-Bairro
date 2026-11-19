<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CacheTableTest extends TestCase
{
    use RefreshDatabase;

    public function test_cache_and_cache_locks_tables_exist(): void
    {
        $this->assertTrue(Schema::hasTable('cache'));
        $this->assertTrue(Schema::hasTable('cache_locks'));
    }

    public function test_rate_limiter_can_store_and_clear_throttle_keys(): void
    {
        $key = 'test_rate_limiter_key|127.0.0.1';

        RateLimiter::clear($key);
        $this->assertFalse(RateLimiter::tooManyAttempts($key, 5));

        RateLimiter::hit($key, 60);
        $this->assertEquals(1, RateLimiter::attempts($key));

        RateLimiter::clear($key);
        $this->assertEquals(0, RateLimiter::attempts($key));
    }
}
