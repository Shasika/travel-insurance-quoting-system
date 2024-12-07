<?php

namespace App\Repositories;

use App\Models\InsuranceQuote;

/**
 * Interface for Insurance Quote Repository.
 *
 * Defines the methods required for managing insurance quotes, including saving,
 * retrieving, and updating quotes in the database.
 */
interface InsuranceQuoteRepositoryInterface
{
    /**
     * Save a new insurance quote to the database.
     *
     * @param array $data An array of data for the insurance quote.
     * @return InsuranceQuote The created InsuranceQuote model instance.
     */
    public function saveQuote(array $data): InsuranceQuote;

    /**
     * Find an insurance quote by its ID.
     *
     * @param int $id The ID of the insurance quote to find.
     * @return InsuranceQuote|null The found InsuranceQuote instance or null if not found.
     */
    public function findQuoteById(int $id);

    /**
     * Update an existing insurance quote in the database.
     *
     * @param int $id The ID of the insurance quote to update.
     * @param array $data An array of data to update the insurance quote.
     * @return InsuranceQuote|null The updated InsuranceQuote model instance or null if not found.
     */
    public function updateQuote(int $id, array $data);
}
