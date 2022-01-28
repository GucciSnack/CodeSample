<?php
declare(strict_types=1);

namespace App\Services;

class CurrencyConverterApi
{
    private static string $apiBaseUrl = 'https://free.currconv.com/api/v7/';

    public static function getConversion(string $toCurrency, string $fromCurrency = 'USD'): float
    {
        $query = strtoupper($fromCurrency . '_' . $toCurrency);

        // perform get request to obtain conversion from currency converter api.
        $conversionJson = file_get_contents(
            self::$apiBaseUrl . 'convert?q=' . $query . '&compact=ultra&apiKey=' . $_ENV['CURRENCY_CONVERTER_API']
        );

        // decode json into an array
        $convertedData = json_decode($conversionJson, true);
        $value = floatval($convertedData[$query]);

        // return conversion amount
        return $value;
    }
}