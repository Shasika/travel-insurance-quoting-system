<?php

namespace App\Livewire;

use App\Repositories\InsuranceQuoteRepositoryInterface;
use Livewire\Component;

/**
 * Livewire Component for managing Travel Insurance Quotes.
 *
 * Handles the functionality for calculating, saving, updating, and editing
 * travel insurance quotes, as well as resetting the form.
 */
class TravelQuote extends Component
{
    // Public properties to bind to the form inputs
    public $destination; // Destination for the trip
    public $startDate; // Trip start date
    public $endDate; // Trip end date
    public $coverageOptions = []; // Selected coverage options
    public $numberOfTravelers = 1; // Number of travelers
    public $quotePrice = 0; // Calculated quote price
    public $quoteId = null; // Holds the ID of the current quote for editing

    /**
     * Validation rules for form inputs.
     *
     * @var array
     */
    protected $rules = [
        'destination' => 'required|string',
        'startDate' => 'required|date|after_or_equal:today',
        'endDate' => 'required|date|after_or_equal:startDate',
        'coverageOptions' => 'array',
        'numberOfTravelers' => 'required|integer|min:1',
    ];

    /**
     * Calculates the insurance quote and saves/updates it in the database.
     *
     * @param InsuranceQuoteRepositoryInterface $repository
     * @return void
     */
    public function calculateQuote(InsuranceQuoteRepositoryInterface $repository)
    {
        // Validate the form inputs
        $validatedData = $this->validate();

        // Pricing definitions
        $destinationPrices = ['Europe' => 10, 'Asia' => 20, 'America' => 30];
        $coveragePrices = ['Medical Expenses' => 20, 'Trip Cancellation' => 30];

        // Calculate the quote price
        $destinationCost = $destinationPrices[$this->destination] ?? 0;
        $coverageCost = array_sum(array_map(fn($option) => $coveragePrices[$option] ?? 0, $this->coverageOptions));
        $this->quotePrice = $this->numberOfTravelers * ($destinationCost + $coverageCost);

        // Save or update the quote in the database
        if ($this->quoteId) {
            // Update existing quote
            $repository->updateQuote($this->quoteId, [
                'destination' => $this->destination,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
                'coverage_options' => json_encode($this->coverageOptions),
                'number_of_travelers' => $this->numberOfTravelers,
                'price' => $this->quotePrice,
            ]);
        } else {
            // Save new quote and store its ID
            $this->quoteId = $repository->saveQuote([
                'destination' => $this->destination,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
                'coverage_options' => json_encode($this->coverageOptions),
                'number_of_travelers' => $this->numberOfTravelers,
                'price' => $this->quotePrice,
            ])->id;
        }
    }

    /**
     * Loads an existing quote into the form for editing.
     *
     * @param int $id Quote ID to edit
     * @param InsuranceQuoteRepositoryInterface $repository
     * @return void
     */
    public function editQuote($id, InsuranceQuoteRepositoryInterface $repository)
    {
        // Retrieve the quote by ID
        $quote = $repository->findQuoteById($id);

        // If the quote exists, populate the form with its data
        if ($quote) {
            $this->quoteId = $quote->id;
            $this->destination = $quote->destination;
            $this->startDate = $quote->start_date;
            $this->endDate = $quote->end_date;
            $this->coverageOptions = json_decode($quote->coverage_options, true);
            $this->numberOfTravelers = $quote->number_of_travelers;
            $this->quotePrice = $quote->price;
        }
    }

    /**
     * Resets the form inputs to their default values.
     *
     * @return void
     */
    public function resetForm()
    {
        $this->destination = null;
        $this->startDate = null;
        $this->endDate = null;
        $this->coverageOptions = [];
        $this->numberOfTravelers = 1;
        $this->quotePrice = 0;
        $this->quoteId = null;
    }

    /**
     * Renders the Livewire component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.travel-quote')
            ->extends('layouts.app') // Extend the base layout
            ->section('content'); // Define the section to render the component
    }
}
