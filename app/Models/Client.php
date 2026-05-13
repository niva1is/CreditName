<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'full_name',
        'short_name',
        'inn',
        'ogrn',
        'ownership_form',
        'legal_address',
        'phone',
        'contact_person',
        'status',
        'user_id'
    ];

    public function loanApplications()
    {
        return $this->hasMany(LoanApplication::class);
    }

    /**
     * Пользователь (сотрудник компании)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}