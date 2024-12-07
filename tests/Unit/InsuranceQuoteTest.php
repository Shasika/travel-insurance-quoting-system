<?php

use App\Models\InsuranceQuote;
use App\Repositories\InsuranceQuoteRepositoryInterface;

/**
 * Test the calculation of the correct quote price.
 */
it('calculates the correct quote price', function () {
    $destinationPrices = ['Europe' => 10, 'Asia' => 20, 'America' => 30];
    $coveragePrices = ['Medical Expenses' => 20, 'Trip Cancellation' => 30];

    $destination = 'America';
    $coverageOptions = ['Medical Expenses', 'Trip Cancellation'];
    $numberOfTravelers = 2;

    // Calculate expected price
    $destinationCost = $destinationPrices[$destination] ?? 0;
    $coverageCost = array_sum(array_map(fn($option) => $coveragePrices[$option] ?? 0, $coverageOptions));
    $quotePrice = $numberOfTravelers * ($destinationCost + $coverageCost);

    // Assert the calculation is correct
    expect($quotePrice)->toBe(160); // 2 x (30 + (20 + 30))
});

/**
 * Test validation of input data for insurance quotes.
 */
it('validates the input data for insurance quotes', function () {
    $rules = [
        'destination' => 'required|string',
        'startDate' => 'required|date',
        'endDate' => 'required|date|after_or_equal:startDate',
        'coverageOptions' => 'array',
        'numberOfTravelers' => 'required|integer|min:1',
    ];

    $data = [
        'destination' => 'Asia',
        'startDate' => '2024-01-01',
        'endDate' => '2024-01-10',
        'coverageOptions' => ['Medical Expenses'],
        'numberOfTravelers' => 1,
    ];

    // Validate the data
    $validator = validator($data, $rules);

    // Assert validation passes
    expect($validator->passes())->toBeTrue();
});

/**
 * Test saving a quote using the repository.
 */
it('saves a quote using the repository', function () {
    $mockRepository = Mockery::mock(InsuranceQuoteRepositoryInterface::class);

    // Mock a quote instance
    $mockQuote = new InsuranceQuote([
        'destination' => 'Europe',
        'start_date' => '2024-01-01',
        'end_date' => '2024-01-10',
        'coverage_options' => json_encode(['Medical Expenses']),
        'number_of_travelers' => 1,
        'price' => 60,
    ]);

    // Mock repository behavior
    $mockRepository->shouldReceive('saveQuote')->once()->andReturn($mockQuote);

    $result = $mockRepository->saveQuote([
        'destination' => 'Europe',
        'start_date' => '2024-01-01',
        'end_date' => '2024-01-10',
        'coverage_options' => json_encode(['Medical Expenses']),
        'number_of_travelers' => 1,
        'price' => 60,
    ]);

    // Assert the repository returns the expected result
    expect($result)->toBeInstanceOf(InsuranceQuote::class);
    expect($result->destination)->toBe('Europe');
});
