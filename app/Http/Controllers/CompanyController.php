<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\RegistrationDocument;
use Illuminate\Support\Facades\Storage;
use App\Models\LoanApplication;
use App\Models\CreditProduct;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        // Клиенты
        $query = Client::query();

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('short_name', 'like', "%{$search}%")
                ->orWhere('full_name', 'like', "%{$search}%")
                ->orWhere('inn', 'like', "%{$search}%");
            });
        }

        if ($request->filled('ownership')) {
            $query->where('ownership_form', $request->ownership);
        }

        $clients = $query->orderBy('short_name')->paginate(15);

        // KPI
        $totalPortfolio = LoanApplication::where('status', 'approved')->sum('amount') ?? 0;
        $totalLoans     = LoanApplication::where('status', 'approved')->count();
        $activeClients  = Client::where('status', 'active')->count();

        // Статистика по продуктам
        $productStats = CreditProduct::withCount([
                'loanApplications as loans_count' => fn($q) => $q->where('status', 'approved')
            ])
            ->withSum([
                'loanApplications as total_amount' => fn($q) => $q->where('status', 'approved')
            ], 'amount')
            ->get();

        // Данные для графика (по месяцам)
        $monthlyData = LoanApplication::selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->where('status', 'approved')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        return view('companies.index', compact(
            'clients',
            'totalPortfolio',
            'totalLoans',
            'activeClients',
            'productStats',
            'monthlyData'
        ));
    }

    public function show(Client $client)
    {
        $client->load(['loanApplications.creditProduct', 'user']);
        return view('companies.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('companies.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'short_name' => 'required|string|max:255',
            'ownership_form' => 'required|in:ООО,АО,ПАО,ЗАО',
            'legal_address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'contact_person' => 'required|string|max:255',
        ]);

        $client->update([
            'full_name' => strip_tags($validated['full_name']),
            'short_name' => strip_tags($validated['short_name']),
            'ownership_form' => $validated['ownership_form'],
            'legal_address' => strip_tags($validated['legal_address']),
            'phone' => strip_tags($validated['phone']),
            'contact_person' => strip_tags($validated['contact_person']),
        ]);

        return redirect()->route('companies.show', $client)
            ->with('success', 'Данные компании обновлены');
    }

    /**
     * Загрузка документа для компании
     */
    public function uploadDocument(Request $request, Client $client)
    {
        $request->validate([
            'document' => 'required|array',
            'document.*' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'document_type' => 'required|array',
            'document_type.*' => 'required|string',
        ]);
    
        // Создаём заявку для документов
        $regRequest = \App\Models\RegistrationRequest::create([
            'company_full_name' => $client->full_name,
            'company_short_name' => $client->short_name,
            'inn' => $client->inn,
            'ogrn' => $client->ogrn,
            'ownership_form' => $client->ownership_form,
            'legal_address' => $client->legal_address,
            'phone' => $client->phone,
            'contact_person' => $client->contact_person,
            'contact_email' => '',
            'email' => $client->user->email ?? '',
            'password_hash' => '',
            'status' => 'approved',
            'request_type' => 'update',
            'existing_client_id' => $client->id,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
    
        foreach ($request->file('document') as $index => $file) {
            $safeName = uniqid('doc_', true) . '_' . preg_replace('/[^a-zA-Z0-9_.-]/', '_', $file->getClientOriginalName());
            $path = $file->storeAs('registration-documents/' . $regRequest->id, $safeName, 'private');
    
            \App\Models\RegistrationDocument::create([
                'registration_request_id' => $regRequest->id,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'document_type' => $request->document_type[$index],
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ]);
        }
    
        return back()->with('success', count($request->file('document')) . ' док. загружено');
    }
    
    /**
     * Удаление документа
     */
    public function deleteDocument(RegistrationDocument $document)
    {
        Storage::disk('private')->delete($document->file_path);
        $document->delete();
        return back()->with('success', 'Документ удалён');
    }
}