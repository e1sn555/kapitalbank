<?php

namespace Kapitalbank;

use Exception;
use SimpleXMLElement;

trait Purchase
{
    /**
     * @throws Exception
     */
    public function purchase(float $amount, string $description, int $currency = 944, string $language = 'AZ'): array
    {
        return
            collect(
                new SimpleXMLElement(
                    $this->service->send('POST', '/Exec', [
                            'body' => generateXML([
                                'Request' => [
                                    'Operation' => 'CreateOrder',
                                    'Language' => 'RU',
                                    'Order' => [
                                        'OrderType' => 'Purchase',
                                        'Merchant' => config('kapitalbank.merchant'),
                                        'Amount' => $amount * 100,
                                        'Currency' => $currency,
                                        'Description' => $description,
                                        'ApproveURL' => config('kapitalbank.approve_url'),
                                        'CancelURL' => config('kapitalbank.cancel_url'),
                                        'DeclineURL' => config('kapitalbank.decline_url')
                                    ]
                                ]
                            ])
                        ]
                    )
                )
            )->toArray();
    }
}
