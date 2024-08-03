<?php

if (!function_exists('formatPrice')) {
    /**
     * formatPrice
     *
     * @param  int|float $harga
     * @param  string $mataUang (default: IDR)
     * @return string
     */
    function formatPrice($harga, $mataUang = 'IDR')
    {
        $supportedCurrencies = [
            'IDR' => 'Rp.',
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'JPY' => '¥',
        ];

        if (!is_numeric($harga)) {
            throw new InvalidArgumentException('Harga harus berupa angka');
        }

        if (!array_key_exists($mataUang, $supportedCurrencies)) {
            throw new InvalidArgumentException('Mata uang tidak didukung');
        }

        return $supportedCurrencies[$mataUang] . ' ' . number_format($harga, 0, ',', '.');
    }
}
