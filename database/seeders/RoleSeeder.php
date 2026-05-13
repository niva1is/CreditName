<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        echo "Создаём роли...\n";
        
        $roles = [
            [
                'name' => 'Администратор системы',
                'slug' => 'admin',
                'description' => 'Полный доступ ко всем функциям системы'
            ],
            [
                'name' => 'Кредитный менеджер',
                'slug' => 'credit_manager',
                'description' => 'Создание и просмотр кредитных заявок'
            ],
            [
                'name' => 'Руководитель аналитического центра',
                'slug' => 'analyst',
                'description' => 'Просмотр аналитики и отчётов'
            ],
            [
                'name' => 'Супервайзер кредитного отдела',
                'slug' => 'supervisor',
                'description' => 'Одобрение кредитов и просмотр операций'
            ],
            [
                'name' => 'Риск-менеджер',
                'slug' => 'risk_manager',
                'description' => 'Оценка рисков кредитных заявок'
            ],
            [
                'name' => 'Клиент (юридическое лицо)',
                'slug' => 'client',
                'description' => 'Представитель компании-клиента банка'
            ],
        ];

        foreach ($roles as $role) {
            // Используем updateOrCreate чтобы не было дубликатов
            Role::updateOrCreate(
                ['slug' => $role['slug']], // Ищем по slug
                $role // Если не нашли - создаём, если нашли - обновляем
            );
            echo "✅ {$role['name']} ({$role['slug']})\n";
        }
        
        echo "✅ Обработано " . count($roles) . " ролей\n";
    }
}