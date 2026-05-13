<?php

namespace App\Http\Controllers;

use App\Models\LoanApplication;
use App\Models\Client;
use App\Models\CreditProduct;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $query = LoanApplication::with(['client', 'creditProduct']);

        // Фильтрация
        if ($request->filled('product_id')) {
            $query->where('credit_product_id', $request->product_id);
        }
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        $loans = $query->orderBy('created_at', 'desc')->get();
        $clients = Client::orderBy('short_name')->get();
        $products = CreditProduct::orderBy('name')->get();

        return view('loans.registry', compact('loans', 'clients', 'products'));
    }

    public function create()
    {
        $clients = Client::where('status', 'active')->orderBy('short_name')->get();
        $products = CreditProduct::where('is_active', true)->orderBy('name')->get();
        $recentLoans = LoanApplication::with(['client', 'creditProduct'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('loans.create', compact('clients', 'products', 'recentLoans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'product_id' => 'required|exists:credit_products,id',
            'amount' => 'required|numeric|min:100000|max:1000000000',
            'issue_date' => 'required|date',
        ]);

        $loan = LoanApplication::create([
            'client_id' => $validated['client_id'],
            'credit_product_id' => $validated['product_id'],
            'amount' => $validated['amount'],
            'issue_date' => $validated['issue_date'],
            'status' => 'issued',
            'created_by' => auth()->id(),
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('loans.create')
            ->with('success', 'Кредит на сумму ' . number_format($validated['amount'], 0, ',', ' ') . ' ₽ успешно зарегистрирован.');
    }
}