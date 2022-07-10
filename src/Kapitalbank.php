<?php

namespace Kapitalbank;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class Kapitalbank implements KapitalbankContract
{
    use Purchase, Refund, CancelPreAuth;

    /**
     * @var PendingRequest
     */
    private PendingRequest $service;

    public function __construct()
    {
        $this->service = Http::baseUrl('https://e-commerce.kapitalbank.az:5443')
            ->withoutVerifying()
            ->withOptions([
                'ssl_key' => [
                    config('kapitalbank.key_path'),
                ],
                'cert' => config('kapitalbank.certificate_path')
            ])
            ->withHeaders([
                'Content-Type' => 'application/xml',
            ]);
    }
}
