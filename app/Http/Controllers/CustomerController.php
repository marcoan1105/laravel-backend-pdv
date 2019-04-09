<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    protected $validate = [
        'name'=> 'required'
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Customer::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id = null, Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|max:200',
            'document' => 'required|max:20'
        ]);

        $return = [
            "errors" => [],
            "msg" => "",
            "data" => null,
            "status" => true
        ];

        if($validation->fails()){
            $return["errors"] = $validation->errors();
            $return["status"] = false;
        }else{
            $customer = null;

            $where = [
                ['document', '=', $request->input('document')]
            ];

            if($id){
                $customer = Customer::find($id);
                $where[] = ['id', '<>', $id];
            }else {
                $customer = new Customer;
            }

            $data = Customer::where($where)->get();

            if(count($data)){
                $return["errors"][] = ["document" => "Document already registredy"];
                $return["status"] = false;
            }else{
                $customer->name = $request->input('name');
                $customer->document = $request->input('document');
                $customer->address = $request->input('address');
                $customer->number = $request->input('number');
                $customer->obs = $request->input('obs');
                $customer->email = $request->input('email');

                if($request->input('phone')){
                    $customer->phone = $request->input('phone');
                }

                if($request->input('block')){
                    $customer->block = $request->input('block');
                }else{
                    if(!$id){
                        $customer->block = 0;
                    }
                }

                if($request->input('inactive')){
                    $customer->inactive = $request->input('inactive');
                }else{
                    if(!$id){
                        $customer->inactive = 0;
                    }
                }

                $customer->save();

                $return["data"] = $customer;
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

        $customer = $this->create(null, $request);
        return $customer;

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
            "errors" => [],
            "status" => true,
            "data" => null
        ];

        $customer = Customer::find($id);

        if($customer){
            $return["data"] = $customer;
        }else{
            $return["errors"] = ["id" => "No data from this id"];
            $return["status"] = false;
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
        $customer = $this->create($id, $request);
        return $customer;
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
