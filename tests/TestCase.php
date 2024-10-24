<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Begin transaction before each test
        DB::beginTransaction();
    }

    protected function tearDown(): void
    {
        // Rollback any database changes made during the test
        DB::rollBack();

        parent::tearDown();
    }
}
