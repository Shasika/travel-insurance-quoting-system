<?php

namespace App\Repositories;

use App\Models\InsuranceQuote;

class InsuranceQuoteRepository implements InsuranceQuoteRepositoryInterface
{
    public function saveQuote(array $data): InsuranceQuote
    {
        return InsuranceQuote::create($data);
    }

    public function findQuoteById(int $id)
    {
        return InsuranceQuote::find($id);
    }

    public function updateQuote(int $id, array $data)
    {
        $quote = InsuranceQuote::find($id);
        if ($quote) {
            $quote->update($data);
            return $quote;
        }

        return null;
    }
}
