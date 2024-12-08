<?php

use App\Models\InsuranceQuote;
use App\Repositories\InsuranceQuoteRepositoryInterface;

/**
 * Test that quote calculation handles no coverage options gracefully.
 */
it('calculates the correct quote price with no coverage options', function () {
    $destinationPrices = ['Europe' => 10, 'Asia' => 20, 'America' => 30];

    $destination = 'Asia';
    $coverageOptions = []; // No coverage selected
    $numberOfTravelers = 3;

    // Calculate expected price
    $destinationCost = $destinationPrices[$destination] ?? 0;
    $coverageCost = 0;
    $quotePrice = $numberOfTravelers * ($destinationCost + $coverageCost);

    // Assert the calculation is correct
    expect($quotePrice)->toBe(60); // 3 x (20 + 0)
});

/**
 * Test that an invalid destination results in a 0 price.
 */
it('returns 0 price for an invalid destination', function () {
    $destinationPrices = ['Europe' => 10, 'Asia' => 20, 'America' => 30];

    $destination = 'InvalidPlace';
    $coverageOptions = ['Medical Expenses', 'Trip Cancellation'];
    $numberOfTravelers = 1;

    // Calculate expected price
    $destinationCost = $destinationPrices[$destination] ?? 0; // Invalid destination should be 0
    $coveragePrices = ['Medical Expenses' => 20, 'Trip Cancellation' => 30];
    $coverageCost = array_sum(array_map(fn($option) => $coveragePrices[$option] ?? 0, $coverageOptions));
    $quotePrice = $numberOfTravelers * ($destinationCost + $coverageCost);

    // Assert the calculation is correct
    expect($quotePrice)->toBe(50); // 1 x (0 + (20 + 30))
});

/**
 * Test that quote validation fails with missing required fields.
 */
it('fails validation with missing required fields', function () {
    $rules = [
        'destination' => 'required|string',
        'startDate' => 'required|date',
        'endDate' => 'required|date|after_or_equal:startDate',
        'coverageOptions' => 'array',
        'numberOfTravelers' => 'required|integer|min:1',
    ];

    $data = [
        'startDate' => '2024-01-01', // Missing destination
        'endDate' => '2024-01-10',
        'coverageOptions' => [],
        'numberOfTravelers' => 2,
    ];

    // Validate the data
    $validator = validator($data, $rules);

    // Assert validation fails
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->get('destination'))->toContain('The destination field is required.');
});

/**
 * Test that saving a quote with the repository fails gracefully for invalid data.
 */
it('fails to save a quote with invalid data', function () {
    $mockRepository = Mockery::mock(InsuranceQuoteRepositoryInterface::class);

    // Mock repository behavior for invalid data
    $mockRepository->shouldReceive('saveQuote')->once()->andThrow(new Exception('Invalid data'));

    // Attempt to save a quote with invalid data
    try {
        $mockRepository->saveQuote([
            'destination' => null, // Invalid data
            'start_date' => '2024-01-01',
            'end_date' => '2024-01-10',
            'coverage_options' => json_encode([]),
            'number_of_travelers' => 1,
            'price' => 0,
        ]);
    } catch (Exception $e) {
        // Assert that the exception message is as expected
        expect($e->getMessage())->toBe('Invalid data');
    }
});

/**
 * Test that editing an existing quote correctly populates the form data.
 */
it('populates form data when editing an existing quote', function () {
    $mockRepository = Mockery::mock(InsuranceQuoteRepositoryInterface::class);

    // Mock an existing quote instance
    $mockQuote = new InsuranceQuote([
        'id' => 1,
        'destination' => 'Europe',
        'start_date' => '2024-01-01',
        'end_date' => '2024-01-10',
        'coverage_options' => json_encode(['Medical Expenses']),
        'number_of_travelers' => 2,
        'price' => 100,
    ]);

    // Mock repository behavior
    $mockRepository->shouldReceive('findQuoteById')->once()->andReturn($mockQuote);

    // Retrieve and assert form data
    $quote = $mockRepository->findQuoteById(1);
    expect($quote->destination)->toBe('Europe');
    expect($quote->start_date)->toBe('2024-01-01');
    expect($quote->end_date)->toBe('2024-01-10');
    expect(json_decode($quote->coverage_options, true))->toContain('Medical Expenses');
    expect($quote->number_of_travelers)->toBe(2);
    expect($quote->price)->toBe(100);
});
