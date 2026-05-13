<?php

namespace App\Http\Controllers;

use App\Models\LoanApplication;
use App\Models\CreditProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // KPI данные
        $totalPortfolio = LoanApplication::where('status', 'issued')->sum('amount');
        $totalLoans = LoanApplication::where('status', 'issued')->count();
        $activeClients = LoanApplication::where('status', 'issued')
            ->distinct('client_id')
            ->count();

        // Статистика по продуктам
        $productStats = CreditProduct::withCount(['loanApplications as loans_count' => function($query) {
            $query->where('status', 'issued');
        }])
        ->withSum(['loanApplications as total_amount' => function($query) {
            $query->where('status', 'issued');
        }], 'amount')
        ->get()
        ->filter(function($product) {
            return $product->loans_count > 0;
        });

        // Данные для графика по месяцам
        $monthlyData = LoanApplication::where('status', 'issued')
            ->whereYear('issue_date', date('Y'))
            ->select(DB::raw('MONTH(issue_date) as month'), DB::raw('SUM(amount) as total'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        return view('dashboard.index', compact(
            'totalPortfolio',
            'totalLoans',
            'activeClients',
            'productStats',
            'monthlyData'
        ));
    }
}