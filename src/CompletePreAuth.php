<?php

namespace Kapitalbank;

use Exception;
use SimpleXMLElement;

trait CompletePreAuth
{
    /**
     * @param float $amount
     * @param string $description
     * @param string $order_id
     * @param string $session_id
     * @param int $currency
     * @param string $language
     * @return array
     * @throws Exception
     */
    public function completePreAuth(float $amount, string $description, string $order_id, string $session_id, int $currency = 944, string $language = 'AZ'): array
    {
        return
            collect(
                new SimpleXMLElement(
                    $this->service->send('POST', '/Exec', [
                            'body' => generateXML([
                                'Request' => [
                                    'Operation' => 'Completion',
                                    'Language' => $language,
                                    'Order' => [
                                        'Merchant' => config('kapitalbank.merchant'),
                                        'OrderID' => $order_id,
                                    ],
                                    'SessionID' => $session_id,
                                    'Amount' => $amount * 100,
                                    'Description' => $description
                                ]
                            ])
                        ]
                    )
                )
            )->toArray();
    }
}
