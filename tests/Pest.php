<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// Apply TestCase globally for Feature and Integration tests
uses(TestCase::class)->in('Feature', 'Integration');

// Include RefreshDatabase only for tests that interact with the database
uses(RefreshDatabase::class)->in('Feature', 'Integration');

// Optionally apply TestCase to Unit tests if needed
// Note: Unit tests typically don't require Laravel's application context.
uses(TestCase::class)->in('Unit');
