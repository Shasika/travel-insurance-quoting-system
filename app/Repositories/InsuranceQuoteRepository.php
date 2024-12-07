<?php

namespace App\Repositories;

use App\Models\InsuranceQuote;

/**
 * Repository for handling Insurance Quote data operations.
 *
 * This repository provides methods for creating, retrieving, and updating
 * insurance quotes in the database.
 */
class InsuranceQuoteRepository implements InsuranceQuoteRepositoryInterface
{
    /**
     * Save a new insurance quote to the database.
     *
     * @param array $data An array of data to be saved for the insurance quote.
     * @return InsuranceQuote The created InsuranceQuote model instance.
     */
    public function saveQuote(array $data): InsuranceQuote
    {
        // Create and return the new InsuranceQuote instance
        return InsuranceQuote::create($data);
    }

    /**
     * Find an insurance quote by its ID.
     *
     * @param int $id The ID of the insurance quote to find.
     * @return InsuranceQuote|null The found InsuranceQuote instance or null if not found.
     */
    public function findQuoteById(int $id)
    {
        // Retrieve and return the InsuranceQuote instance by ID
        return InsuranceQuote::find($id);
    }

    /**
     * Update an existing insurance quote in the database.
     *
     * @param int $id The ID of the insurance quote to update.
     * @param array $data An array of data to update the insurance quote.
     * @return InsuranceQuote|null The updated InsuranceQuote model instance or null if not found.
     */
    public function updateQuote(int $id, array $data)
    {
        // Find the quote by ID
        $quote = InsuranceQuote::find($id);

        // Update and return the quote if it exists
        if ($quote) {
            $quote->update($data);
            return $quote;
        }

        // Return null if the quote was not found
        return null;
    }
}
