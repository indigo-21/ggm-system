<?php

namespace App\Http\Requests;

class StoreQuoteRequest extends QuoteRequest
{
    protected function customerIdForContacts(): ?int
    {
        // New quote: contacts are brand new, nothing to ignore.
        return null;
    }
}
