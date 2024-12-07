<?php

use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class); // Ensures a fresh database state for every test

/**
 * Test the submission of the insurance quote form.
 */
it('submits the insurance quote form and stores the data', function () {
    Livewire::test(\App\Livewire\TravelQuote::class)
        ->set('destination', 'Asia')
        ->set('startDate', '2024-01-01')
        ->set('endDate', '2024-01-10')
        ->set('coverageOptions', ['Medical Expenses'])
        ->set('numberOfTravelers', 1)
        ->call('calculateQuote');

    // Assert that non-JSON data is saved correctly
    $this->assertDatabaseHas('insurance_quotes', [
        'destination' => 'Asia',
        'start_date' => '2024-01-01',
        'end_date' => '2024-01-10',
        'number_of_travelers' => 1,
    ]);

    // Assert that JSON column contains the expected value
    $this->assertTrue(
        DB::table('insurance_quotes')
            ->where('destination', 'Asia')
            ->whereJsonContains('coverage_options', 'Medical Expenses')
            ->exists(),
        'Failed asserting that the database has the correct JSON value in the coverage_options column.'
    );
});

/**
 * Test that the quote summary is displayed correctly.
 */
it('displays the quote summary correctly', function () {
    Livewire::test(\App\Livewire\TravelQuote::class)
        ->set('destination', 'America')
        ->set('startDate', '2024-01-01')
        ->set('endDate', '2024-01-10')
        ->set('coverageOptions', ['Medical Expenses', 'Trip Cancellation'])
        ->set('numberOfTravelers', 2)
        ->call('calculateQuote')
        ->assertSee('America')
        ->assertSee('2024-01-01')
        ->assertSee('2024-01-10')
        ->assertSee('Medical Expenses, Trip Cancellation')
        ->assertSee('$160'); // Total price
});

/**
 * Test that validation fails when startDate is after endDate.
 */
it('fails validation when startDate is after endDate', function () {
    Livewire::test(\App\Livewire\TravelQuote::class)
        ->set('destination', 'Europe')
        ->set('startDate', '2024-01-10') // Invalid: Start date is after end date
        ->set('endDate', '2024-01-01')   // Invalid: End date is before start date
        ->call('calculateQuote')
        ->assertHasErrors(['endDate' => 'after_or_equal']);
});

/**
 * Test the quote calculation with no coverage options selected.
 */
it('calculates the correct quote price with no coverage options', function () {
    Livewire::test(\App\Livewire\TravelQuote::class)
        ->set('destination', 'Europe')
        ->set('startDate', '2024-01-01')
        ->set('endDate', '2024-01-10')
        ->set('coverageOptions', []) // No coverage selected
        ->set('numberOfTravelers', 1)
        ->call('calculateQuote')
        ->assertSee('$10'); // 1 traveler x $10 (destination only)
});
