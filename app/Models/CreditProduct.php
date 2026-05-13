<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'base_rate',
        'purpose',
        'is_active'
    ];

    public function loanApplications()
    {
        return $this->hasMany(LoanApplication::class);
    }
}