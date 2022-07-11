<?php

namespace Kapitalbank;

use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use SimpleXMLElement;

class Kapitalbank implements KapitalbankContract
{
    use Purchase, Refund, CancelPreAuth, CompletePreAuth;

    /**
     * @var PendingRequest
     */
    private PendingRequest $service;

    protected $response;

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

    /**
     * @return bool
     */
    public function failed(): bool
    {
        return $this->response->failed();
    }

    public function errors(callable $callback)
    {
        return $this->response->throw($callback);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function toArray(): array
    {
        return collect(new SimpleXMLElement($this->response))->toArray();
    }
}
