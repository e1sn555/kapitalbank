<?php

namespace Kapitalbank;

use Illuminate\Support\Facades\Facade;

class KapitalbankFacade extends Facade
{
    /**
     * @return string
     */
    public static function getFacadeAccessor(): string
    {
        return 'kapitalbank';
    }
}
