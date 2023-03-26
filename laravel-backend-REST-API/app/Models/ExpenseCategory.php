<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'expense_categories';

    protected $fillable = [
        'name',
        'image_url',
    ];

    public function expense()
    {
        return $this->hasOne(Expense::class);
    }
}
