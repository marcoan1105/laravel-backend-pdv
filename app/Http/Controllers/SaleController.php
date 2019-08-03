<?php

namespace App\Http\Controllers;


use App\Models\Customer;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SalePayment;
use App\Models\SaleProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
{
    protected $validation = [
        'client_id' => 'required|integer',
        'total' => 'required',
        'discount' => 'required',
        'products' => 'required',
        'payments' => 'required',
        'final_value' => 'required',
        'date' => 'required'
    ];

    public function sale(Request $request){

        $return = [
            "errors" => [],
            "status" => true,
            "msg" => ""
        ];

        $validation = Validator::make($request->all(), $this->validation);

        if($validation->fails()){
            $return["errors"] = $validation->errors();
            $return["status"] = false;
        }else{
            $products = $request->input('products');

            foreach($products as $product){
                $validationProducts = Validator::make($product, [
                    'product_id' => 'required|integer',
                    'value' => 'required',
                    'quant' => 'required'
                ]);

                if($validationProducts->fails()){
                    $return["errors"] = $validationProducts->errors();
                    $return["status"] = false;

                    return $return;
                }

                $productModel = Product::find($product['product_id']);

                if(!$productModel){
                    $return["errors"][] = ["product_id" => "Not product from this id (".$product['product_id'].") "];
                    $return["status"] = false;

                    return $return;
                }
            }

            $payments = $request->input('payments');

            foreach($payments as $payment){
                $validationPayment = Validator::make($payment, [
                    'payment_id' => 'required|integer',
                    'value' => 'required'
                ]);

                if($validationPayment->fails()){
                    $return["errors"] = $validationPayment->errors();
                    $return["status"] = false;

                    return $return;
                }

                $paymentModel = Payment::find($payment['payment_id']);

                if(!$paymentModel){
                    $return["errors"][] = ["product_id" => "Not product from this id (".$payment['payment_id'].") "];
                    $return["status"] = false;

                    return $return;
                }
            }

            if(count($products) <= 0) {
                $return["errors"][] = ["products" => "Sale need products"];
                $return["status"] = false;

                return $return;
            }

            if(count($payments) <= 0){
                $return["errors"][] = ["payments" => "Sale need payments"];
                $return["status"] = false;

                return $return;
            }

            $customer = Customer::find($request->input('client_id'));

            if(!$customer){
                $return["errors"][] = ["client_id" => "Not client from this id (".$request->input('client_id').")"];
                $return["status"] = false;

                return $return;
            }

            $sale = new Sale();
            $sale->client_id = $request->input('client_id');
            $sale->total = $request->input('total');
            $sale->discount = $request->input('discount');
            $sale->date = $request->input('date');
            $sale->final_value = $request->input('final_value');
            $sale->save();

            foreach ($products as $product){
                $saleProduct = new SaleProduct();
                $saleProduct->sale_id = $sale->id;
                $saleProduct->product_id = $product["product_id"];
                $saleProduct->value = $product["value"];
                $saleProduct->quant = $product["quant"];
                $saleProduct->save();

                $productController = new ProductController();
                $stock = $productController->changeStock($product["product_id"], $product["quant"], 'remove');

                if(!$stock['status']){
                    return $stock;
                }
            }

            foreach($payments as $payment){
                $saleProduct = new SalePayment();
                $saleProduct->sale_id = $sale->id;
                $saleProduct->payment_id = $payment['payment_id'];
                $saleProduct->value = $payment['value'];
                $saleProduct->save();
            }

            $return["msg"] = "Sale registred";


        }

        return $return;
    }

    public function index(){
        return Sale::all();
    }

    public function show($id){
        $return = [
            "errors" => [],
            "status" => true,
            "msg" => "",
            "data" => null
        ];

        $sale = Sale::find($id);

        if($sale){
            $sale->payments = $sale->payments();
            $sale->products = $sale->products();
            $return["data"] = $sale;
        }else{
            $return["message"] = "No data from this Id";
            $return["status"] = false;
        }

        return $return;
    }
}
