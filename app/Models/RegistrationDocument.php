<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class RegistrationDocument extends Model
{
    protected $fillable = [
        'registration_request_id',
        'file_name',
        'file_path',
        'document_type',
        'file_size',
        'mime_type',
    ];

    public function registrationRequest()
    {
        return $this->belongsTo(RegistrationRequest::class);
    }

    /**
     * Получить URL для скачивания
     */
    public function getDownloadUrlAttribute(): string
    {
        return route('manager.registrations.download-document', [
            'registration' => $this->registration_request_id,
            'document' => $this->id,
        ]);
    }

    /**
     * Форматированный размер файла
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' МБ';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' КБ';
        }
        return $bytes . ' Б';
    }
    public function deleteFile(): bool
    {
        // Удаляем физический файл с диска
        if (Storage::disk('private')->exists($this->file_path)) {
            Storage::disk('private')->delete($this->file_path);
        }
        
        // Удаляем запись из базы данных
        return $this->delete();
    }
}