<?php

namespace App\Http\Controllers;

use App\Mail\RequestSent;
use App\Models\Customer;
use App\Models\Operation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OperationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Operation::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Get maker ID        
        $maker_id = auth()->user()->id;
        $request->validate([
            'request_type' => 'required'
        ]);

        if($request['request_type'] == 'create'){
            //Validate inputs
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required',
            ]);

            //Add maker ID to the input
            $request->merge(["maker_id"=>$maker_id]);
            
            //Add Operation type to the input
            $request->merge(["op_type"=>'create']);
            
            //Get all other administrator's IDs
            $users = User::where('id','!=',$maker_id)->get();
            if ($users->count() > 0) {
                foreach($users as $key => $value){
                    if (!empty($value->email)) {
                        $details = strtoupper($request['request_type']).' Request From Glover Maker-Checker System';
                        
                        //Send email notification
                        Mail::to($value->email)->send(new RequestSent($details));
                    }
                }
            }

            //Run operation
            return response([
                'message' => 'CREATE request created successfully',
                'requested_data' => Operation::create($request->all())
            ], 201);

        }elseif($request['request_type'] == 'update'){
        //Validate inputs
        $request->validate([
            'user_id' => 'required',
            'first_name' => 'required_without_all:last_name,email',
            'last_name' => 'required_without_all:first_name,email',
            'email' => 'required_without_all:last_name,first_name',
        ]);

        //Add maker ID to the input
        $request->merge(["maker_id"=>$maker_id]);
        
        //Add Operation type to the input
        $request->merge(["op_type"=>'update']);
        
        //Get all other administrator's IDs
        $users = User::where('id','!=',$maker_id)->get();
        if ($users->count() > 0) {
            foreach($users as $key => $value){
                if (!empty($value->email)) {
                    $details = strtoupper($request['request_type']).' Request From Glover Maker-Checker System';
                    
                    //Send email notification
                    Mail::to($value->email)->send(new RequestSent($details));
                }
            }
        }

        //Run operation
        return response([
            'message' => 'UPDATE request created successfully',
            'requested_data' => Operation::create($request->all())
        ]);

        }elseif($request['request_type'] == 'delete'){
            //Validate inputs
            $request->validate([
                'user_id' => 'required'
            ]);
    
            //Add maker ID to the input
            $request->merge(["maker_id"=>$maker_id]);
            
            //Add Operation type to the input
            $request->merge(["op_type"=>'delete']);
            
            //Get all other administrator's IDs
            $users = User::where('id','!=',$maker_id)->get();
            if ($users->count() > 0) {
                foreach($users as $key => $value){
                    if (!empty($value->email)) {
                        $details = strtoupper($request['request_type']).' Request From Glover Maker-Checker System';
                        
                        //Send email notification
                        Mail::to($value->email)->send(new RequestSent($details));
                    }
                }
            }

            //Run operation
            return response([
                'message' => 'DELETE request created successfully',
                'requested_data' => Operation::create($request->all())
            ]);
        }else{
            return response([
                'message' => 'Invalid request'
            ]);
        }
            
        
    }

    /**
     * Display the single operation.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
        //Check if operation exists
        if(Operation::find($id)){
            //Get operation by ID and output
            return response(Operation::find($id), 200);
        }else{
            return response([
                "message" => "Operation not found"
              ], 404);
        }
    }

    /**
     * Display the all pending resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function pending()
    {      
        //Get operation by ID
        $user_id = "";

        //Get operation by ID and output
        $operation = Operation::select('id AS request_id', 'op_type AS request_type', 'user_id', 'first_name', 'last_name', 'email')->where('status', 'pending')->get();
        foreach($operation as $key => $value)
        {
            //to get each columns value
            $user_id = $value->user_id;
            $request_type = $value->request_type;
        }
        

        //Get customer data
        $customer = Customer::find($user_id);

        //Get request type
        if($request_type == 'create'){
            $type = "CREATE";
        }elseif($request_type == 'update'){
            $type = "UPDATE";
        }elseif($request_type == 'delete'){
            $type = "DELETE";
        }

        return response([
            'request_type' => $type,
            'requested_change' => $operation,
           // 'customer_data' => $customer
        ], 200);
    }

     /**
     * Approve the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approve(Request $request, $id)
    {
        //Get checker ID        
        $checker_id = auth()->user()->id;

       //Check if operation exists
       if(Operation::find($id)){
            //Get operation by ID
            $operation = Operation::find($id);
        }else{
            return response([
                "message" => "Operation not found"
            ], 404);
        }

        //Get customer id
        $user_id = $operation['user_id'];
        
        //Get customer first_name
        $first_name = $operation['first_name'];
        
        //Get customer last_name
        $last_name = $operation['last_name'];
        
        //Get customer email
        $email = $operation['email'];

        $maker_id = $operation['maker_id'];

        //Validate checker ID
        if($checker_id == $maker_id){
            return response([
                'message' => 'You cannot approve an operation you made (Maker and Checker ID are the same)'
            ], 401);
        }
        
        //check if request is previously approved
        if($operation['status'] == 'approved'){
            return response([
                'message' => 'Request has already been approved'
            ], 401);
        }    

        //conditions for "CREATE/UPDATE/DELETE" operation types
        if($operation['op_type'] == 'create'){        
            //Run operation
            $type = "CREATE";
            $customer = Customer::create([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email
            ]);
        }elseif($operation['op_type'] == 'update'){
            $type = "UPDATE";
            
            //Check if customer exists        
            if(Customer::find($user_id)){
                //Get customer by ID
                $customer = Customer::find($user_id);
            }else{
                return response([
                    "message" => "Customer not found"
                ], 404);
            }

            //Check input conditions and update
            if($first_name != NULL){
                $customer->update(['first_name'=>$first_name]);
            }
            if($last_name != NULL){
                $customer->update([
                    'last_name' => $last_name
                ]);
            }
            if($email != NULL){
                $customer->update([
                    'email' => $email
                ]);
            }
        }elseif($operation['op_type'] == 'delete'){
            $type = "DELETE";
            
            //Check if customer exists        
            if(Customer::find($user_id)){
                //Get customer by ID
                $customer = Customer::find($user_id);
            }else{
                return response([
                    "message" => "Customer not found"
                ], 404);
            }

            //Get customer by ID and delete
            Customer::destroy($user_id);
        }
        
        //Update the selected operation
        $operation->update([
            'status' => 'approved',
            "checker_id"=>$checker_id
        ]);

        //Output
        return response([
            'message' => 'Request approved successfully',
            'request_type' => $type,
            'customer_data' => $customer]);
    }

     /**
     * Reject the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reject(Request $request, $id)
    {
        $type = "REJECTED";

        //Get checker ID        
        $checker_id = auth()->user()->id;

       //Check if operation exists
       if(Operation::find($id)){
            //Get operation by ID
            $operation = Operation::find($id);
        }else{
            return response([
                "message" => "Operation not found"
            ], 404);
        }

        //Validate checker ID
        if($checker_id == $operation['maker_id']){
            return response([
                'message' => 'You cannot reject an operation you made (Maker and Checker ID are the same)'
            ], 401);
        }
        
        //Update the selected operation
        /* $operation->update([
            "checker_id" => $checker_id,
            "status" => "rejected",
        ]); */
        
        //Get operation by ID and delete
        Operation::destroy($id);
        

        //Output
        return response([
            'message' => 'Request rejected and deleted successfully',
            'type' => $type,
        ]);
    }

}
