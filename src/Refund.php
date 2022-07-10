<?php

namespace Kapitalbank;

use Exception;
use SimpleXMLElement;

trait Refund
{
    /**
     * @param float $amount
     * @param string $session_id
     * @param string $order_id
     * @param string $description
     * @param int $currency
     * @param string $language
     * @return array
     * @throws Exception
     */
    public function refund(float $amount, string $session_id, string $order_id, string $description, int $currency = 944, string $language = 'AZ'): array
    {
        return
            collect(
                new SimpleXMLElement(
                    $this->service->send('POST', '/Exec', [
                            'body' => generateXML([
                                'Request' => [
                                    'Operation' => 'Refund',
                                    'Language' => $language,
                                    'Order' => [
                                        'Merchant' => config('kapitalbank.merchant'),
                                        'OrderID' => $order_id,
                                        'Positions' => [
                                            'Position' => [
                                                'PaymentSubjectType' => 1,
                                                'Quantity' => 1,
                                                'Price' => 1,
                                                'Tax' => 1,
                                                'Text' => 'name position',
                                                'PaymentType' => 2,
                                                'PaymentMethodType' => 1,
                                            ]
                                        ]
                                    ],
                                    'Description' => $description,
                                    'SessionID' => $session_id,
                                    'Refund' => [
                                        'Amount' => $amount * 100,
                                        'Currency' => $currency,
                                        'WithFee' => 'false',
                                    ],
                                    'Source' => 1
                                ]
                            ])
                        ]
                    )
                )
            )->toArray();
    }
}
