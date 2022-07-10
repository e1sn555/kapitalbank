<?php

namespace Kapitalbank;

interface KapitalbankContract
{
    /**
     * @param float $amount
     * @param string $description
     * @param int $currency
     * @param string $language
     * @return array
     */
    public function purchase(float $amount, string $description, int $currency = 944, string $language = 'AZ'): array;

    /**
     * @param float $amount
     * @param string $session_id
     * @param string $order_id
     * @param string $description
     * @param int $currency
     * @param string $language
     * @return array
     */
    public function refund(float $amount, string $session_id, string $order_id, string $description, int $currency = 944, string $language = 'AZ'): array;
}
