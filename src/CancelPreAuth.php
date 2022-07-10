<?php

namespace Kapitalbank;

use Exception;
use SimpleXMLElement;

trait CancelPreAuth
{
    /**
     * @param string $order_id
     * @param string $session_id
     * @param string $description
     * @param int $currency
     * @param string $language
     * @return array
     * @throws Exception
     */
    public function cancelPreAuth(string $order_id, string $session_id, string $description, int $currency = 944, string $language = 'AZ'): array
    {
        return
            collect(
                new SimpleXMLElement(
                    $this->service->send('POST', '/Exec', [
                            'body' => generateXML([
                                'Request' => [
                                    'Operation' => 'Reverse',
                                    'Language' => $language,
                                    'Order' => [
                                        'Merchant' => config('kapitalbank.merchant'),
                                        'OrderID' => $order_id,
                                        'Positions' => [
                                            'Position' => [
                                                'PaymentSubjectType' => 1,
                                                'Quantity' => 1,
                                                'PaymentType' => 2,
                                                'PaymentMethodType' => 1
                                            ]
                                        ]
                                    ],
                                    'Description' => $description,
                                    'SessionID' => $session_id,
                                    'TranId' => '',
                                    'Source' => 1
                                ]
                            ])
                        ]
                    )
                )
            )->toArray();
    }
}
