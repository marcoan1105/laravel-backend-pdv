<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    protected $validation = [
        'description' => 'required|max:300',
        'unit' => 'required|max:2',
        'ncm' => 'required|max:8',
        'barcode' => 'required',
        'value' => 'required',
        'quantities' => 'required'
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id = null, Request $request)
    {
        $return = [
            "errors" => [],
            "status" => true,
            "msg" => "",
            "data" => null
        ];

        $validation = Validator::make($request->all(), $this->validation);

        if($validation->fails()){
            $return["errors"] = $validation->errors();
            $return["status"] = false;
        }else{
            $product = null;

            if($id){
                $product = Product::find($id);

                if(!$product){
                    $return["errors"][] = ["id" => "No data from this id"];
                    $return["status"] = false;

                    return $return;
                }
            }else{
                $product = new Product();
            }

            $product->description = $request->input('description');
            $product->unit = $request->input('unit');
            $product->ncm = $request->input('ncm');
            $product->barcode = $request->input('barcode');
            $product->value = $request->input('value');
            $product->quantities = $request->input('quantities');
            $product->save();

            $return["data"] = $product;
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
            'errors' => [],
            'status' => false,
            'data' => null,
            'msg' => ''
        ];

        $product = Product::find($id);

        if($product){
            $return["data"] = $product;
        }else{
            $return["errors"][] = ["id" => "No data from this id"];
            $return["status"] = false;
        }

        return $return;
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

    public function changeStockApi(Request $request){

        $return = [
            'status' => true,
            'errors' => []
        ];

        $validation = Validator::make($request->all(), [
           'id' => 'required',
           'type' => 'required',
           'quantity' => 'required'
        ]);

        if($validation->fails()){
            $return["status"] = false;
            $return["errors"] = $validation->errors();
        }else{
            $id = $request->input('id');
            $quantity = $request->input('quantity');
            $type = $request->input('type');

            return $this->changeStock($id, $quantity, $type);

        }

        return $return;
    }

    public function changeStock($id, $quantity, $type){
        $return = [
            'status' => true,
            'errors' => []
        ];

        $product = Product::find($id);

        if($product){
            if($type == 'add'){
                $product->quantities += $quantity;
            }else if($type == 'remove') {
                $product->quantities -= $quantity;
            }

            $product->save();

            $return["status"] = true;
            $return["msg"] = "Stock changed";

        }else{
            $return["status"] = false;
            $return["errors"][] = ["id" => "No data from this id"];
        }

        return $return;
    }

}
