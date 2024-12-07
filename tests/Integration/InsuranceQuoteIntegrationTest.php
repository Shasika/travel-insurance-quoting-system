<?php

use Livewire\Livewire;
use App\Models\InsuranceQuote;

/**
 * Test the entire flow of the insurance quoting system.
 */
it('handles the complete flow of insurance quoting', function () {
    // Simulate user inputs
    $response = Livewire::test(\App\Livewire\TravelQuote::class)
        ->set('destination', 'America')
        ->set('startDate', '2025-10-01')
        ->set('endDate', '2025-10-15')
        ->set('coverageOptions', ['Medical Expenses', 'Trip Cancellation'])
        ->set('numberOfTravelers', 2)
        ->call('calculateQuote');

    // Assert database contains the saved quote (non-JSON columns)
    $this->assertDatabaseHas('insurance_quotes', [
        'destination' => 'America',
        'start_date' => '2025-10-01',
        'end_date' => '2025-10-15',
        'number_of_travelers' => 2,
    ]);

    // Assert the JSON column contains the correct values
    $this->assertTrue(
        DB::table('insurance_quotes')
            ->where('destination', 'America')
            ->whereJsonContains('coverage_options', 'Medical Expenses')
            ->whereJsonContains('coverage_options', 'Trip Cancellation')
            ->exists(),
        'Failed asserting that the database contains the correct JSON values in the coverage_options column.'
    );

    // Fetch the saved quote
    $quote = InsuranceQuote::first();

    // Assert that the calculated price is correct (handle decimal comparison)
    expect((float) $quote->price)->toBe(160.00); // Cast to float for precise comparison

    // Verify the output of the Livewire component
    $response->assertSee('America')
        ->assertSee('2025-10-01')
        ->assertSee('2025-10-15')
        ->assertSee('Medical Expenses, Trip Cancellation')
        ->assertSee('$160'); // Total price
});
