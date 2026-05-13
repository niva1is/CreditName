<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\RegistrationRequest;
use App\Models\RegistrationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ClientProfileController extends Controller
{
    /**
     * Профиль клиента
     */
    public function index()
    {
        $user = auth()->user();
        $client = Client::where('user_id', $user->id)->first();
        
        if (!$client) {
            return redirect()->route('client.dashboard')
                ->with('error', 'Профиль компании не найден');
        }
        
        $client->load(['loanApplications.creditProduct', 'user']);
        $loans = $client->loanApplications()->with('creditProduct')->orderBy('created_at', 'desc')->get();
        $documents = RegistrationDocument::whereIn('registration_request_id', 
            RegistrationRequest::where('existing_client_id', $client->id)
                ->orWhere(function($q) use ($client) {
                    $q->where('inn', $client->inn)->where('ogrn', $client->ogrn);
                })
                ->pluck('id')
        )->orderBy('created_at', 'desc')->get();
        
        return view('clients.profile', compact('client', 'loans', 'documents'));
    }

    /**
     * Обновление профиля
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        $client = Client::where('user_id', $user->id)->first();
        
        if (!$client) abort(404);

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'short_name' => 'required|string|max:255',
            'legal_address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'contact_person' => 'required|string|max:255',
        ]);

        $client->update($validated);
        return back()->with('success', 'Профиль обновлён');
    }

    /**
     * Смена пароля
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Текущий пароль неверен');
        }

        $user->update(['password' => Hash::make($request->new_password)]);
        return back()->with('success', 'Пароль успешно изменён');
    }

    /**
     * Загрузка документов
     */
    public function uploadDocuments(Request $request)
    {
        $request->validate([
            'documents.*' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'document_types.*' => 'required|string',
        ]);

        $user = auth()->user();
        $client = Client::where('user_id', $user->id)->first();
        if (!$client) return back()->with('error', 'Компания не найдена');

        $regRequest = RegistrationRequest::create([
            'company_full_name' => $client->full_name,
            'company_short_name' => $client->short_name,
            'inn' => $client->inn,
            'ogrn' => $client->ogrn,
            'ownership_form' => $client->ownership_form,
            'legal_address' => $client->legal_address,
            'phone' => $client->phone,
            'contact_person' => $client->contact_person,
            'contact_email' => '',
            'email' => $user->email,
            'password_hash' => '',
            'status' => 'approved',
            'request_type' => 'update',
            'existing_client_id' => $client->id,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        foreach ($request->file('documents') as $index => $file) {
            $safeName = uniqid('doc_', true) . '_' . preg_replace('/[^a-zA-Z0-9_.-]/', '_', $file->getClientOriginalName());
            $path = $file->storeAs('registration-documents/' . $regRequest->id, $safeName, 'private');

            RegistrationDocument::create([
                'registration_request_id' => $regRequest->id,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'document_type' => $request->document_types[$index],
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ]);
        }

        return back()->with('success', 'Документы загружены');
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