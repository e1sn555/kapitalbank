<?php

function generateXML(array $data): string
{
    $xml = '<?xml version="1.0" encoding="UTF-8"?><TKKPG>';
    $xml .= arrayToXML($data);
    $xml .= '</TKKPG>';
    return $xml;
}

function arrayToXML(array $data): string
{
    $xml = '';
    foreach ($data as $key => $val) {
        $xml .= "<$key>";
        if (is_array($val)) {
            $xml .= arrayToXML($val);
        } else {
            $xml .= $val;
        }
        $xml .= "</$key>";
    }
    return $xml;
}
