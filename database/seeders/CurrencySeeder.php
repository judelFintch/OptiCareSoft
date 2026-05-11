<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            ['code' => 'CDF', 'name' => 'Franc Congolais', 'symbol' => 'FC',    'exchange_rate' => 1,        'is_default' => true],
            ['code' => 'USD', 'name' => 'Dollar Américain','symbol' => '$',     'exchange_rate' => 0.00036,  'is_default' => false],
            ['code' => 'EUR', 'name' => 'Euro',             'symbol' => '€',    'exchange_rate' => 0.00033,  'is_default' => false],
            ['code' => 'XOF', 'name' => 'Franc CFA',        'symbol' => 'FCFA', 'exchange_rate' => 0.22,     'is_default' => false],
        ];

        foreach ($currencies as $currency) {
            Currency::firstOrCreate(['code' => $currency['code']], $currency);
        }
    }
}
