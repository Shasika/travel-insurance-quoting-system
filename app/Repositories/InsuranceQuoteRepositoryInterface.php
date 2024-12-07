<?php

namespace App\Repositories;

use App\Models\InsuranceQuote;

interface InsuranceQuoteRepositoryInterface
{
    public function saveQuote(array $data): InsuranceQuote;
    public function findQuoteById(int $id);
    public function updateQuote(int $id, array $data);
}
