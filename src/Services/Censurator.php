<?php

namespace App\Services;

class Censurator
{

    const BAN_WORDS = ['tartine', 'con', 'horloge'];

    public function purify(string $text)
    {
        return  str_ireplace(self::BAN_WORDS, "******", $text);
    }



}