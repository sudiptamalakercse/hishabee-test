<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpenseCategory;
use Illuminate\Support\Str;
use App\Http\Resources\ExpenseCategoryResource;
use Exception;

class ExpenseCategoryController extends Controller
{
    public function get_expense_categories(Request $request)
    {
        try {
        $expense_categories=ExpenseCategory::all()->unique('name');

        if($expense_categories->count()>0){
            return response([
            'all_ok' => 'yes',
            'expense_categories' => ExpenseCategoryResource::collection($expense_categories),
            'message'=>'Records are Retrieved Successfully.'
        ], 200);
    }else{
        return response([
            'all_ok' => 'no',
            'message'=>'No Record is Available.'
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

    public function get_single_expense_category_record_by_id($expense_category_id)
    {
    try {
    $expense_category=ExpenseCategory::find($expense_category_id);

    if($expense_category){
            return response([
            'all_ok' => 'yes',
            'expense_category' => new ExpenseCategoryResource($expense_category),
            'message'=>'Record is Retrieved Successfully.'
        ], 200);
    }else{
        return response([
            'all_ok' => 'no',
            'message'=>'No Record is Available.'
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

    public function create_expense_category(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'image' => 'required|image',//not possible to upload pic more than 2 MB
        ]);

        try {

        $name=$request->name;
        $image=$request->file('image');

        $expense_category=ExpenseCategory::where('name', $name)->first();

        if($expense_category){
            return response([
                'all_ok' => 'no',
                'message'=>'Unable to Save Record Because This Category name is Already Exist.'
            ], 422);
        }

        $expense_category=ExpenseCategory::latest('id')->first();

        if($expense_category==null){
           $unique= 0;
        }else{
           $unique= $expense_category->id;
        }

        $image_name=Str::random(10).$unique;
        $ext=strtolower($image->getClientOriginalExtension());
        $image_full_name=$image_name.'.'.$ext;


        $upload_path='public/images/';
        $image_url=$upload_path.$image_full_name;
        $success=$request->image->move($upload_path, $image_full_name);

        if ($success) {

            $expense_category = new ExpenseCategory();
            $expense_category->name = $name;
            $expense_category->image_url = $image_url;
            $expense_category->save();

            if($expense_category){
                return response([
                    'all_ok' => 'yes',
                    'message' => 'Record is Successfully Created.',
                    'expense_category' => new ExpenseCategoryResource($expense_category),
                ], 201);
            }
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


    public function update_expense_category(Request $request,$category_id)
    {
    

        $request->validate([
            'name' => 'required',
            'image' => 'required|image',//not possible to upload pic more than 2 MB
        ]);

        try {

        $name=$request->name;
        $image=$request->file('image');

        $expense_category=ExpenseCategory::where('name', $name)
                                        ->where('id', '!=' ,$category_id)
                                        ->first();

        if($expense_category){
            return response([
                'all_ok' => 'no',
                'message'=>'Unable to Update Record Because This Category name is Already Exist.'
            ], 422);
        }
        
        $expense_category=ExpenseCategory::find($category_id);

        if($expense_category==null){      
                return response([
                    'all_ok' => 'no',
                    'message'=>'Record With This Id is Not Exist.'
                ], 422);           
        }

        $img_path=$expense_category->image_url;

        if($img_path){
            $deleted=unlink($img_path);
        }

        $unique=$category_id-1;
        $image_name=Str::random(10).$unique;
        $ext=strtolower($image->getClientOriginalExtension());
        $image_full_name=$image_name.'.'.$ext;


        $upload_path='public/images/';
        $image_url=$upload_path.$image_full_name;
        $success=$request->image->move($upload_path, $image_full_name);

        if ($success && $deleted) {
            $expense_category->name = $name;
            $expense_category->image_url = $image_url;
            $expense_category->save();

            if($expense_category){
                return response([
                    'all_ok' => 'yes',
                    'message' => 'Record is Successfully Updated.',
                    'expense_category' => new ExpenseCategoryResource($expense_category),
                ], 201);
            }
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
}
