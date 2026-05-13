<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LoanApplication;
use App\Models\User;

class LoanApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        
        $loans = [
            ['client_id' => 1, 'amount' => 4500000, 'date' => '2025-09-15', 'product_id' => 1],
            ['client_id' => 2, 'amount' => 2300000, 'date' => '2025-10-02', 'product_id' => 2],
            ['client_id' => 3, 'amount' => 1200000, 'date' => '2025-08-20', 'product_id' => 3],
            ['client_id' => 1, 'amount' => 3100000, 'date' => '2025-11-05', 'product_id' => 4],
            ['client_id' => 4, 'amount' => 8700000, 'date' => '2025-09-28', 'product_id' => 1],
            ['client_id' => 5, 'amount' => 2950000, 'date' => '2025-10-18', 'product_id' => 5],
            ['client_id' => 2, 'amount' => 1850000, 'date' => '2025-12-01', 'product_id' => 3],
            ['client_id' => 3, 'amount' => 5200000, 'date' => '2026-01-20', 'product_id' => 1],
            ['client_id' => 4, 'amount' => 4100000, 'date' => '2026-02-10', 'product_id' => 2],
            ['client_id' => 5, 'amount' => 1100000, 'date' => '2026-01-28', 'product_id' => 5],
            ['client_id' => 2, 'amount' => 7600000, 'date' => '2026-02-18', 'product_id' => 4],
            ['client_id' => 1, 'amount' => 2990000, 'date' => '2025-12-14', 'product_id' => 2],
            ['client_id' => 3, 'amount' => 500000, 'date' => '2026-03-05', 'product_id' => 3],
            ['client_id' => 4, 'amount' => 6300000, 'date' => '2026-03-20', 'product_id' => 1],
        ];

        foreach ($loans as $loan) {
            LoanApplication::create([
                'client_id' => $loan['client_id'],
                'credit_product_id' => $loan['product_id'],
                'created_by' => $user->id,
                'amount' => $loan['amount'],
                'issue_date' => $loan['date'],
                'status' => 'issued',
                'approved_by' => $user->id,
                'approved_at' => now(),
            ]);
        }
    }
}