<?php

namespace Kapitalbank;

use Exception;
use SimpleXMLElement;

trait Purchase
{
    /**
     * @param float $amount
     * @param string $description
     * @param int $currency
     * @param string $language
     * @param string $order_type
     * @param string|null $approve_url
     * @param string|null $cancel_url
     * @param string|null $decline_url
     * @return array
     * @throws Exception
     */
    public function createOrder(float $amount, string $description, int $currency = 944, string $language = 'AZ', string $order_type = 'Purchase', string|null $approve_url = null, string $cancel_url = null, string|null $decline_url = null): array
    {
        return
            collect(
                new SimpleXMLElement(
                    $this->service->send('POST', '/Exec', [
                            'body' => generateXML([
                                'Request' => [
                                    'Operation' => 'CreateOrder',
                                    'Language' => $language,
                                    'Order' => [
                                        'OrderType' => $order_type,
                                        'Merchant' => config('kapitalbank.merchant'),
                                        'Amount' => $amount * 100,
                                        'Currency' => $currency,
                                        'Description' => $description,
                                        'ApproveURL' => $approve_url || config('kapitalbank.approve_url'),
                                        'CancelURL' => $cancel_url || config('kapitalbank.cancel_url'),
                                        'DeclineURL' => $decline_url || config('kapitalbank.decline_url')
                                    ]
                                ]
                            ])
                        ]
                    )
                )
            )->toArray();
    }
}
