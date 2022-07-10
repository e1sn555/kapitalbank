<?php

namespace Kapitalbank;

interface KapitalbankContract
{
    public function purchase(float $amount, string $description, int $currency = 944, string $language = 'AZ'): array;
}
