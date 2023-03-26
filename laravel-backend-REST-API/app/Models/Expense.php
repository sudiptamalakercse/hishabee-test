<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
 
    protected $primaryKey = 'id';

    protected $table = 'expenses';

    protected $fillable = [
        'expense_quantity',
        'expense_category_id',
        'payment_type_id'
    ];

    public function expenseCategory()
    {
        return $this->belongsTo(ExpenseCategory::class);
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class);
    }
}
