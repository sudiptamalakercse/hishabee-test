<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ExpenseCategory;
use App\Models\PaymentType;
use App\Models\Expense;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        ExpenseCategory::factory(10)->create();
        PaymentType::factory(10)->create();
        Expense::factory(10)->create();
    }
}
