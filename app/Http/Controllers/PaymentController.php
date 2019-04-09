<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    protected $validation = [
        'description' => 'required',
        'type' => 'required'
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Payment::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id, Request $request)
    {
        $return = [
            "status" => true,
            "msg" => "",
            "errors" => [],
            "data" => null
        ];

        $validation = Validator::make($request->all(), $this->validation);

        if($validation->fails()){
            $return["errors"] = $validation->errors();
            $return["status"] = false;
        }else{
            $payment = null;

            if($id){
                $payment = Payment::find($id);
            }else{
                $payment = new Payment();
            }

            if($payment){
                $payment->description = $request->input('description');
                $payment->type = $request->input('type');
                $payment->save();
                $return["data"] = $payment;
            }else{
                $return["errors"][] = ["id" => "No data from this id"];
                $return["status"] = false;
            }
        }

        return $return;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->create(null, $request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $return = [
            'status' => true,
            'data' => '',
            'errors' => []
        ];
        $payment = Payment::find($id);

        if($payment){
            $return['data'] = $payment;
        }else{
            $return['errors'][] = ['id' => 'No data from this id'];
            $return['status'] = false;
        }

        return $return;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $this->create($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
