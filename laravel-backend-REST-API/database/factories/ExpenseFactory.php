<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PaymentType;
use App\Models\ExpenseCategory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'expense_quantity' => fake()->numberBetween($min = 1000, $max = 9000),
            'expense_category_id' => ExpenseCategory::inRandomOrder()->first()->id,
            'payment_type_id' => PaymentType::inRandomOrder()->first()->id,
        ];
    }
}
