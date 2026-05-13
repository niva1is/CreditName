<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\RegistrationRequest;
use App\Models\RegistrationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RegistrationController extends Controller
{
    /**
     * Список заявок
     */
    public function index(Request $request)
    {
        $query = RegistrationRequest::query()->with(['documents', 'existingClient']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('type')) {
            $query->where('request_type', $request->type);
        }
        
        $registrations = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('manager.registrations.index', compact('registrations'));
    }

    /**
     * Просмотр заявки
     */
    public function show(RegistrationRequest $registration)
    {
        $registration->load(['documents', 'existingClient', 'approver']);
        return view('manager.registrations.show', compact('registration'));
    }

    /**
     * Одобрить заявку
     */
    public function approve(RegistrationRequest $registration)
    {
        try {
            $registration->approve(auth()->id());
            return redirect()->route('manager.registrations.index')
                ->with('success', 'Заявка одобрена!');
        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка при одобрении: ' . $e->getMessage());
        }
    }

    /**
     * Отклонить заявку
     */
    public function reject(Request $request, RegistrationRequest $registration)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:10|max:1000',
        ]);

        $registration->reject(auth()->id(), $validated['rejection_reason']);

        return redirect()->route('manager.registrations.index')
            ->with('success', 'Заявка отклонена. Клиент получит уведомление.');
    }

    /**
     * Скачать документ
     */
    public function downloadDocument(RegistrationRequest $registration, RegistrationDocument $document)
    {
        // Проверяем, что документ принадлежит этой заявке
        if ($document->registration_request_id !== $registration->id) {
            abort(403);
        }

        // Проверяем существование файла
        if (!Storage::disk('private')->exists($document->file_path)) {
            abort(404, 'Файл не найден');
        }

        // Отдаём файл с правильными заголовками
        return response()->file(
            Storage::disk('private')->path($document->file_path),
            [
                'Content-Type' => $document->mime_type,
                'Content-Disposition' => 'inline; filename="' . $document->file_name . '"',
                'X-Content-Type-Options' => 'nosniff',
                'X-Frame-Options' => 'DENY',
            ]
        );
    }
}