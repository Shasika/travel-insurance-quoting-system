<?php

namespace App\Livewire;

use App\Repositories\InsuranceQuoteRepositoryInterface;
use Livewire\Component;

class TravelQuote extends Component
{
    public $destination;
    public $startDate;
    public $endDate;
    public $coverageOptions = [];
    public $numberOfTravelers = 1;
    public $quotePrice = 0;
    public $quoteId = null; // Holds the ID of the current quote for editing

    protected $rules = [
        'destination' => 'required|string',
        'startDate' => 'required|date',
        'endDate' => 'required|date|after_or_equal:startDate',
        'coverageOptions' => 'array',
        'numberOfTravelers' => 'required|integer|min:1',
    ];

    public function calculateQuote(InsuranceQuoteRepositoryInterface $repository)
    {
        $validatedData = $this->validate();

        // Calculate quote price
        $destinationPrices = ['Europe' => 10, 'Asia' => 20, 'America' => 30];
        $coveragePrices = ['Medical Expenses' => 20, 'Trip Cancellation' => 30];

        $destinationCost = $destinationPrices[$this->destination] ?? 0;
        $coverageCost = array_sum(array_map(fn($option) => $coveragePrices[$option] ?? 0, $this->coverageOptions));

        $this->quotePrice = $this->numberOfTravelers * ($destinationCost + $coverageCost);

        // Save or update the quote in the database
        if ($this->quoteId) {
            $repository->updateQuote($this->quoteId, [
                'destination' => $this->destination,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
                'coverage_options' => json_encode($this->coverageOptions),
                'number_of_travelers' => $this->numberOfTravelers,
                'price' => $this->quotePrice,
            ]);
        } else {
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

    public function editQuote($id, InsuranceQuoteRepositoryInterface $repository)
    {
        $quote = $repository->findQuoteById($id);

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

    public function render()
    {
        return view('livewire.travel-quote')
            ->extends('layouts.app')
            ->section('content');
    }
}
