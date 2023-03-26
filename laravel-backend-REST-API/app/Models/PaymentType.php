<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    
    protected $table = 'payment_types';
    
    protected $fillable = [
        'name',
    ];

    public function expense()
    {
        return $this->hasOne(Expense::class);
    }

}
