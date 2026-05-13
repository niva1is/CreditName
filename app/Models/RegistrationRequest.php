<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class RegistrationRequest extends Model
{
    protected $fillable = [
        'company_full_name',
        'company_short_name',
        'inn',
        'ogrn',
        'ownership_form',
        'legal_address',
        'phone',
        'contact_person',
        'contact_email',
        'email',
        'password_hash',
        'status',
        'request_type',
        'existing_client_id',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /**
     * Связь с документами
     */
    public function documents()
    {
        return $this->hasMany(RegistrationDocument::class);
    }

    /**
     * Кто одобрил
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Существующий клиент (для заявок на изменение)
     */
    public function existingClient()
    {
        return $this->belongsTo(Client::class, 'existing_client_id');
    }

    /**
     * Одобрить заявку
    */
    public function approve($approverId)
    {
        if ($this->request_type === 'update' && $this->existing_client_id) {
            // Обновляем существующего клиента
            $client = Client::findOrFail($this->existing_client_id);
            $client->update([
                'full_name' => $this->company_full_name,
                'short_name' => $this->company_short_name,
                'ownership_form' => $this->ownership_form,
                'legal_address' => $this->legal_address,
                'phone' => $this->phone,
                'contact_person' => $this->contact_person,
            ]);
            
            // Обновляем данные пользователя
            if ($client->user) {
                $client->user->update([
                    'name' => $this->contact_person,
                    'email' => $this->email,
                ]);
            }
        } else {
            // Создаём нового клиента
            $client = Client::create([
                'full_name' => $this->company_full_name,
                'short_name' => $this->company_short_name,
                'inn' => $this->inn,
                'ogrn' => $this->ogrn,
                'ownership_form' => $this->ownership_form,
                'legal_address' => $this->legal_address,
                'phone' => $this->phone,
                'contact_person' => $this->contact_person,
                'status' => 'active',
            ]);

            // Создаём пользователя
            $user = User::create([
                'name' => $this->contact_person,
                'email' => $this->email,
                'password' => $this->password_hash,
            ]);
            $user->assignRole('client');
            
            // Привязываем пользователя к клиенту
            $client->update(['user_id' => $user->id]);
        }

        $this->update([
            'status' => 'approved',
            'approved_by' => $approverId,
            'approved_at' => now(),
        ]);

        return $client;
    }

    /**
     * Отклонить заявку
     */
    public function reject($approverId, $reason)
    {
        $this->update([
            'status' => 'rejected',
            'approved_by' => $approverId,
            'approved_at' => now(),
            'rejection_reason' => $this->sanitize($reason),
        ]);
    }

    /**
     * Проверить дубликаты среди существующих клиентов
     */
    public static function findDuplicates($inn, $ogrn, $excludeId = null)
    {
        $duplicates = [];
        
        $innQuery = Client::where('inn', $inn);
        $ogrnQuery = Client::where('ogrn', $ogrn);
        
        if ($excludeId) {
            $innQuery->where('id', '!=', $excludeId);
            $ogrnQuery->where('id', '!=', $excludeId);
        }
        
        $innDuplicate = $innQuery->first();
        $ogrnDuplicate = $ogrnQuery->first();
        
        if ($innDuplicate) {
            $duplicates['inn'] = [
                'field' => 'ИНН',
                'company' => $innDuplicate->short_name,
                'client_id' => $innDuplicate->id,
            ];
        }
        
        if ($ogrnDuplicate) {
            $duplicates['ogrn'] = [
                'field' => 'ОГРН',
                'company' => $ogrnDuplicate->short_name,
                'client_id' => $ogrnDuplicate->id,
            ];
        }
        
        return $duplicates;
    }

    /**
     * Санитарная обработка текста (защита от XSS)
     */
    private function sanitize($input): string
    {
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}