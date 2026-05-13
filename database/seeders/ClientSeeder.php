<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            [
                'full_name' => 'Общество с ограниченной ответственностью «ТехИнвестСтрой»',
                'short_name' => 'ООО «ТехИнвестСтрой»',
                'inn' => '771234567890',
                'ogrn' => '1234567890123',
                'ownership_form' => 'ООО',
                'legal_address' => '125315, г. Москва, ул. Балтийская, д. 12, стр. 1, офис 305',
                'phone' => '+7 (495) 123-45-67',
                'contact_person' => 'Иванов П.А.',
                'status' => 'active',
            ],
            [
                'full_name' => 'Акционерное общество «Агроресурс»',
                'short_name' => 'АО «Агроресурс»',
                'inn' => '231011111111',
                'ogrn' => '1234567890124',
                'ownership_form' => 'АО',
                'legal_address' => '350000, г. Краснодар, Западный пр. 8',
                'phone' => '+7 (861) 234-56-78',
                'contact_person' => 'Петрова Е.В.',
                'status' => 'active',
            ],
            [
                'full_name' => 'Общество с ограниченной ответственностью «Логистик-Сервис»',
                'short_name' => 'ООО «Логистик-Сервис»',
                'inn' => '781011111112',
                'ogrn' => '1234567890125',
                'ownership_form' => 'ООО',
                'legal_address' => '191023, г. Санкт-Петербург, наб. Фонтанки 21',
                'phone' => '+7 (812) 345-67-89',
                'contact_person' => 'Смирнов Д.О.',
                'status' => 'active',
            ],
            [
                'full_name' => 'Публичное акционерное общество «ЭнергоСбыт»',
                'short_name' => 'ПАО «ЭнергоСбыт»',
                'inn' => '661011111113',
                'ogrn' => '1234567890126',
                'ownership_form' => 'ПАО',
                'legal_address' => '620014, г. Екатеринбург, ул. Малышева 45',
                'phone' => '+7 (343) 456-78-90',
                'contact_person' => 'Кузнецова Л.Н.',
                'status' => 'active',
            ],
            [
                'full_name' => 'Общество с ограниченной ответственностью «НПО Цифровые решения»',
                'short_name' => 'ООО «НПО Цифровые решения»',
                'inn' => '541011111114',
                'ogrn' => '1234567890127',
                'ownership_form' => 'ООО',
                'legal_address' => '630090, г. Новосибирск, пр. Лаврентьева 6',
                'phone' => '+7 (383) 567-89-01',
                'contact_person' => 'Тимофеев А.И.',
                'status' => 'active',
            ],
        ];

        foreach ($clients as $client) {
            Client::create($client);
        }
    }
}