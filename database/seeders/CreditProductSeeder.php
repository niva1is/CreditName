<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CreditProduct;

class CreditProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'code' => 'inv',
                'name' => 'Инвестиционный кредит',
                'base_rate' => 9.20,
                'purpose' => 'Финансирование капитальных вложений, модернизация производства',
                'is_active' => true,
            ],
            [
                'code' => 'oborot',
                'name' => 'Оборотный кредит',
                'base_rate' => 11.50,
                'purpose' => 'Пополнение оборотных средств, текущая деятельность',
                'is_active' => true,
            ],
            [
                'code' => 'overdraft',
                'name' => 'Овердрафт',
                'base_rate' => 14.00,
                'purpose' => 'Покрытие кассовых разрывов по расчетному счету',
                'is_active' => true,
            ],
            [
                'code' => 'proj',
                'name' => 'Проектное финансирование',
                'base_rate' => 8.70,
                'purpose' => 'Крупные инвестиционные проекты с поэтапным финансированием',
                'is_active' => true,
            ],
            [
                'code' => 'sezon',
                'name' => 'Сезонный кредит',
                'base_rate' => 12.30,
                'purpose' => 'Финансирование сезонных закупок и пиков активности',
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            CreditProduct::create($product);
        }
    }
}