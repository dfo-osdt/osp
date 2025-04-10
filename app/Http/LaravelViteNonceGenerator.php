<?php

namespace App\Http;

use Illuminate\Support\Facades\Vite;
use Spatie\Csp\Nonce\NonceGenerator;

class LaravelViteNonceGenerator implements NonceGenerator
{
    public function generate(): string
    {
        return Vite::cspNonce();
    }
}
