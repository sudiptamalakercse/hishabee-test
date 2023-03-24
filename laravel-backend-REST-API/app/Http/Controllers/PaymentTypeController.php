<?php

namespace App\Http\Controllers;

use App\Models\PaymentType;
use Illuminate\Http\Request;

class PaymentTypeController extends Controller
{
    public function create_payment_type(Request $request)
    {

        $request->validate([
            'name' => 'required|string',
        ]);


        try {

        $name=$request->name;

        $payment_type=PaymentType::where('name', $name)->first();

        if($payment_type){
            return response([
                'all_ok' => 'no',
                'message'=>'Unable to Save Record Because This Payment Type is Already Exist.'
            ], 422);
        }
         else{
            $payment_type = new PaymentType();
            $payment_type->name = $name;
            $payment_type->save();

            if($payment_type){
                return response([
                    'all_ok' => 'yes',
                    'message' => 'Record is Successfully Created.',
                    'payment_type' => $payment_type,
                ], 201);
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

public function get_payment_types(Request $request)
    {
        try {
        $payment_types=PaymentType::all()->unique('name');

        if($payment_types->count()>0){
            return response([
            'all_ok' => 'yes',
            'payment_types' => $payment_types,
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

}
