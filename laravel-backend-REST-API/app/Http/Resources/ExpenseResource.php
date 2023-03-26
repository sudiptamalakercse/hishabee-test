<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\ExpenseCategory;
use App\Models\PaymentType;
use App\Models\Expense;
use App\Http\Resources\ExpenseCategoryResource;

class ExpenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     * 
     */

    private function get_expense_category($expense_category_id){
        return new ExpenseCategoryResource(ExpenseCategory::find($expense_category_id));
    }

    private function get_payment_type($payment_type_id){
        return PaymentType::find($payment_type_id);
    }


    private function get_expense_percentage($expense_quantity){
        $total = Expense::sum('expense_quantity');
        return $expense_percentage=intval((100/$total)*$expense_quantity) ." %";
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'expense_quantity' => $this->expense_quantity,
            'expense_date' => $this->expense_date,
            'expense_category' => $this->get_expense_category($this->expense_category_id),
            'payment_type' =>$this->get_payment_type($this->payment_type_id),
            'expense_percentage'=>$this->get_expense_percentage($this->expense_quantity),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
