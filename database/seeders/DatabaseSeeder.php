<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        echo "=== Заполнение базы данных ===\n\n";
        
        // Порядок ОЧЕНЬ важен!
        $this->call([
            RoleSeeder::class,               // 1. Сначала роли
            UserSeeder::class,               // 2. Потом пользователи
            ClientSeeder::class,             // 3. Клиенты
            CreditProductSeeder::class,      // 4. Кредитные продукты
            LoanApplicationSeeder::class,    // 5. Кредитные заявки
        ]);
        
        echo "\n=== База данных заполнена! ===\n";
    }
}