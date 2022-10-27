<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    use HasFactory;

    protected $fillable = [
        'receipt',
        'company_id',
        'user_id',
        'currency_id',
        'method',
        'rate',
        'amount',
        'total',
        'customer_name',
        'customer_pay',
        'company_pay',
        'phone_no',
        'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class,'currency_id','id');
    }
}
