<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Apply the Laravel TestCase class to Unit, Feature and Integration tests.
 * This ensures Laravel's testing infrastructure is available for these test types.
 */
uses(TestCase::class)->in('Unit', 'Feature', 'Integration');

/**
 * Include RefreshDatabase trait for Feature and Integration tests.
 * This ensures a clean database state before each test by rolling back migrations.
 */
uses(RefreshDatabase::class)->in('Feature', 'Integration');
