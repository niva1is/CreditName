<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'credit_product_id',
        'created_by',
        'approved_by',
        'amount',
        'issue_date',
        'status',
        'notes',
        'approved_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'issue_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function creditProduct()
    {
        return $this->belongsTo(CreditProduct::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}