<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        echo "Создаём пользователей...\n";
        
        // Администратор
        $admin = User::updateOrCreate(
            ['email' => 'admin@alfabank.ru'],
            [
                'name' => 'Администратор системы',
                'password' => bcrypt('password123'),
            ]
        );
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }
        echo "✅ admin@alfabank.ru / password123 (admin)\n";
        
        // Руководитель аналитического центра
        $analyst = User::updateOrCreate(
            ['email' => 'analyst@alfabank.ru'],
            [
                'name' => 'Руководитель аналитического центра',
                'password' => bcrypt('password123'),
            ]
        );
        if (!$analyst->hasRole('analyst')) {
            $analyst->assignRole('analyst');
        }
        echo "✅ analyst@alfabank.ru / password123 (analyst)\n";
        
        // Кредитный менеджер
        $manager = User::updateOrCreate(
            ['email' => 'manager@alfabank.ru'],
            [
                'name' => 'Кредитный менеджер',
                'password' => bcrypt('password123'),
            ]
        );
        if (!$manager->hasRole('credit_manager')) {
            $manager->assignRole('credit_manager');
        }
        echo "✅ manager@alfabank.ru / password123 (credit_manager)\n";
        
        // Супервайзер
        $supervisor = User::updateOrCreate(
            ['email' => 'supervisor@alfabank.ru'],
            [
                'name' => 'Супервайзер кредитного отдела',
                'password' => bcrypt('password123'),
            ]
        );
        if (!$supervisor->hasRole('supervisor')) {
            $supervisor->assignRole('supervisor');
        }
        echo "✅ supervisor@alfabank.ru / password123 (supervisor)\n";
    }
}