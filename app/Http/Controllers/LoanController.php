<?php

namespace App\Http\Controllers;

use App\Models\LoanApplication;
use App\Models\Client;
use App\Models\CreditProduct;
use Illuminate\Http\Request;
use App\Models\RegistrationDocument;
use App\Models\RegistrationRequest;

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
            'amount' => 'required|numeric|min:10000|max:1000000000',
            'term_months' => 'required|integer|min:6|max:84',
            'issue_date' => 'required|date',
            'purpose' => 'required|string|min:10|max:1000',
            'contact_person' => 'required|string|max:255',
            'contact_phone' => 'required|string|regex:/^\+7\s\([0-9]{3}\)\s[0-9]{3}-[0-9]{2}-[0-9]{2}$/',
            'documents' => 'nullable|array|max:10',
            'documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);

        // Создаём заявку
        $loan = LoanApplication::create([
            'client_id' => $validated['client_id'],
            'credit_product_id' => $validated['product_id'],
            'amount' => $validated['amount'],
            'term_months' => $validated['term_months'],
            'issue_date' => $validated['issue_date'],
            'purpose' => $validated['purpose'],
            'contact_person' => $validated['contact_person'],
            'contact_phone' => $validated['contact_phone'],
            'status' => 'pending',
            'created_by' => auth()->id(),
        ]);

        if ($request->hasFile('documents')) {
            // Создаём временную заявку для документов 
            $client = Client::find($validated['client_id']);
            
            $regRequest = RegistrationRequest::create([
                'company_full_name' => $client->full_name ?? '',
                'company_short_name' => $client->short_name ?? '',
                'inn' => $client->inn ?? '',
                'ogrn' => $client->ogrn ?? '',
                'ownership_form' => $client->ownership_form ?? '',
                'legal_address' => $client->legal_address ?? '',
                'phone' => $client->phone ?? '',
                'contact_person' => $client->contact_person ?? '',
                'contact_email' => $client->user->email ?? '',
                'email' => $client->user->email ?? '',
                'password_hash' => '',
                'status' => 'approved',
                'request_type' => 'loan_application',  
                'existing_client_id' => $client->id,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            foreach ($request->file('documents') as $file) {
                if (!$file) continue;
                
                $safeName = uniqid('loan_doc_', true) . '_' . preg_replace('/[^a-zA-Z0-9_.-]/', '_', $file->getClientOriginalName());
                $path = $file->storeAs('loan-documents/' . $loan->id, $safeName, 'private');
                
                RegistrationDocument::create([
                    'registration_request_id' => $regRequest->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'document_type' => 'loan_application',
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ]);
            }
        }

        return redirect()->route('loans.create')
            ->with('success', 'Заявка на кредит отправлена на рассмотрение менеджеру.');
    }

    /**
     * Список заявок для менеджера
     */
    public function managerIndex(Request $request)
    {
        $query = LoanApplication::with(['client', 'creditProduct', 'creator']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $loans = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('manager.loans.index', compact('loans'));
    }

    /**
     * Просмотр заявки менеджером
     */
    public function managerShow(LoanApplication $loan)
    {
        $loan->load(['client', 'creditProduct', 'creator', 'approver']);
        return view('manager.loans.show', compact('loan'));
    }

    /**
     * Одобрить заявку
     */
    public function approve(LoanApplication $loan)
    {
        $loan->update([
            'status' => 'issued',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('manager.loans.index')
            ->with('success', 'Заявка #' . $loan->id . ' одобрена');
    }

    /**
     * Отклонить заявку
     */
    public function reject(Request $request, LoanApplication $loan)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:10|max:500',
        ]);

        $loan->update([
            'status' => 'rejected',
            'notes' => $request->rejection_reason,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('manager.loans.index')
            ->with('success', 'Заявка #' . $loan->id . ' отклонена');
    }

}