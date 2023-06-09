<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Http\Resources\ExpenseResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;

class ExpenseController extends Controller
{
    public function create_expense(Request $request)
    {
        
        $request->validate([
            'expense_quantity' => 'required|integer',
            'expense_date' => 'required|date',
            'expense_category_id' => 'required|integer',
            'payment_type_id' => 'required|integer',
        ]);

        try {
            $expense = new Expense();
            $expense->expense_quantity = $request->expense_quantity;
            $expense->expense_date = $request->expense_date;
            $expense->expense_category_id = $request->expense_category_id;
            $expense->payment_type_id = $request->payment_type_id;
            $expense->save();

            if($expense){
                return response([
                    'all_ok' => 'yes',
                    'message' => 'Record is Successfully Created.',
                    'expense'=>new ExpenseResource($expense)
                ], 201);
            }
            else{

                return response([
                    'all_ok' => 'no',
                    'message'=>'Unable to Save Record.'
                ], 422);
            
            }
        
        }
        catch (Exception $e) {

            return response([
                'all_ok' => 'no',
                'message'=>$e->getMessage()
            ], 422);    
    
        }
    }

    public function update_expense(Request $request,$expense_id)
    {
    
        $request->validate([
            'expense_quantity' => 'required|integer',
            'expense_date' => 'required|date',
            'expense_category_id' => 'required|integer',
            'payment_type_id' => 'required|integer',
        ]);
        try {

            $expense=Expense::find($expense_id);
      
        if($expense==null){      
                return response([
                    'all_ok' => 'no',
                    'message'=>'Record With This Id is Not Exist.'
                ], 422);           
        }else{
            $expense->expense_quantity = $request->expense_quantity;
            $expense->expense_date = $request->expense_date;
            $expense->expense_category_id = $request->expense_category_id;
            $expense->payment_type_id = $request->payment_type_id;
            $expense->save();

            if($expense){
                return response([
                    'all_ok' => 'yes',
                    'message' => 'Record is Successfully Updated.',
                    'expense_category' => new ExpenseResource($expense),
                ], 204);
            }
         }

    }
    catch (Exception $e) {

        return response([
            'all_ok' => 'no',
            'message'=>$e->getMessage()
        ], 422);    

    }
 }

 public function delete_expense($expense_id)
    {
        try {
       
        $expense=Expense::find($expense_id);

        if($expense==null){      
                return response([
                    'all_ok' => 'no',
                    'message'=>'Record With This Id is Not Exist.'
                ], 422);           
        }else{

            $expense->delete();
            
            return response([
                'all_ok' => 'yes',
                'message' => 'Record is Successfully Deleted.',
            ], 204);

        }

    }
    catch (Exception $e) {

        return response([
            'all_ok' => 'no',
            'message'=>$e->getMessage()
        ], 422);    

    }
 }

 public function get_single_expense_record_by_id($expense_id)
 {
 try {

 $expense=Expense::find($expense_id);

 if($expense){
         return response([
         'all_ok' => 'yes',
         'expense' => new ExpenseResource($expense),
         'message'=>'Record is Retrieved Successfully.'
     ], 200);
 }else{
     return response([
         'all_ok' => 'no',
         'message'=>'No Record is Available With This Id.'
     ], 422);
 }
 }
 catch (Exception $e) {

     return response([
         'all_ok' => 'no',
         'message'=>$e->getMessage()
     ], 422);    

 }
 }

 public function get_expenses(Request $request){

$day= $request->day;
$week= $request->week;
$month= $request->month;
$year= $request->year;
$date= $request->date;
$search= $request->search;
$expense_category_type=$request->expense_category_type;
$date_old_to_new=$request->date_old_to_new;
$expense_low_to_high= $request->expense_low_to_high;




    try {
         
        if (Expense::count() > 0) {

            $expenses = expense::where('id', '!=', null);

            $now = Carbon::now();

            if ($day != null) {
                $expenses = $expenses->whereDay('expense_date', $now->day);
    
            }

            if ($week != null) {
                $expenses=$expenses->whereBetween('expense_date', 
                        [$now->startOfWeek()->format('Y-m-d'), $now->endOfWeek()->format('Y-m-d')]
                      );
            }

            if ($month != null) {
                $expenses = $expenses->whereMonth('expense_date', $now->month);
    
            }

            if ($year != null) {
                $expenses = $expenses->whereYear('expense_date', $now->year);
    
            }

            if ($date != null) {
                $expenses = $expenses->whereDate('expense_date', $date);
    
            }

            if ($search != null) {
                $expenses=$expenses
                ->where('expense_quantity','LIKE','%'.$search.'%')
                ->orWhere('expense_date','LIKE','%'.$search.'%')
                ->orWhere(function($query) use ($search)
                {
                    $query->whereRelation('expenseCategory', 'name', 'like', '%' . $search . '%')
                    ->orWhereRelation('paymentType', 'name', 'like', '%' . $search . '%');
                });
                   
            } 


            if ($expense_category_type != null) {
                
                $new_expense_category_type='';

                for ($i=1;$i<(strlen($expense_category_type)-1);$i++)
                {
                    $new_expense_category_type=$new_expense_category_type.$expense_category_type[$i];
                }
        
                $expense_category_type=explode(",", $new_expense_category_type);

                $expenses=$expenses->whereRelation('expenseCategory', function (Builder $query)  use ($expense_category_type) {
                    $query->whereIn('name',  $expense_category_type);
                });  
            }


            if ($date_old_to_new == 'true') {
                $expenses=$expenses->orderBy('created_at', 'asc');
            }
            else if ($date_old_to_new == 'false'){
                $expenses=$expenses->orderBy('created_at', 'desc');
            }
            else if ($expense_low_to_high == 'true'){
                $expenses=$expenses->orderBy('expense_quantity', 'asc');
            }
            else if ($expense_low_to_high == 'false'){
                $expenses=$expenses->orderBy('expense_quantity', 'desc');
            }
            else {
                $expenses=$expenses->orderBy('created_at', 'asc');
            }
            
            $expenses2=$expenses;

            $total_expense_quantity=$expenses2->sum('expense_quantity');
            
            $expenses=$expenses->simplePaginate(2); 

            $expenses=$expenses->appends($request->all());

            $expenses=ExpenseResource::collection($expenses);

            $expenses=$expenses->additional([
                'all_ok' => 'yes',
                'total_expense_quantity' => $total_expense_quantity,
                'message'=>'Record is Retrieved Successfully.'
            ]);

            return $expenses;

        }
        else {

            return response([
                'all_ok' => 'no',
                'message' => 'No Record!',
            ], 404);

        }
    }
    catch (Exception $e) {
        return response([
            'all_ok' => 'no',
            'message'=>$e->getMessage()
        ], 422);    
    }
 }

}
