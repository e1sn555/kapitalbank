<?php

namespace Kapitalbank;

use Exception;

interface KapitalbankContract
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
    public function createOrder(float $amount, string $description, int $currency = 944, string $language = 'AZ', string $order_type = 'Purchase', string|null $approve_url = null, string $cancel_url = null, string|null $decline_url = null): array;

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
    public function refund(float $amount, string $session_id, string $order_id, string $description, int $currency = 944, string $language = 'AZ'): array;
}
