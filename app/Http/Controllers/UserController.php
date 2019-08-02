<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    protected $valdation = [
        'name' => 'required',
        'email' => 'required',
        'password' => 'required'
    ];

    public function create($id, Request $request){

        $return = [
            "status" => true,
            "msg" => "",
            "errors" => [],
            "data" => null
        ];

        $data = $request->all();

        $validation = Validator::make($data, $this->valdation);

        if($validation->fails()){
            $return["status"] = false;
            $return["errors"] = $validation->errors();
        }else{
            $user = null;

            if($id){
                $user = User::find($id);
            }else{
                $user = new User();
            }

            $where = [
                ['email', '=', $data['email']]
            ];

            if($id){
                $where[] = ['id', '<>', $data['id']];
            }

            $emailDuplicated = User::where($where)->get();

            if(count($emailDuplicated) > 0){
                $return["status"] = false;
                $return["errors"][] = ["email" => "This email is already registred"];

                return $return;
            }

            if(!$user){
                $return["status"] = false;
                $return["errors"][] = ["id" => "No data from this id "];

                return $return;
            }

            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = bcrypt($data['password']);
            $user->save();

            $user->password = null;

            $return['data'] = $user;
        }

        return $return;
    }

    public function login(){
        return [
            "status" => false,
            "msg" => "You need to login or register",
            "errors" => ["login" => "You need to login or register"],
            "data" => null
        ];
    }

    public function store(Request $request){
        return $this->create(null, $request);
    }

    public function update($id, Request $request){
        return $this->create($id, $request);
    }

    public function show($id){
        $return = [
            "status" => true,
            "msg" => "",
            "errors" => [],
            "data" => null
        ];

        $user = User::find($id);

        if($user){

            $user->password = null;

            $return["data"] = $user;

            return $return;
        }else{
            $return["status"] = false;
            $return["errors"][] = ["Id" => "No data from this id"];
        }
    }

    public function user(Request $request){
        $return = [
            "status" => true,
            "msg" => "",
            "errors" => [],
            "data" => []
        ];

        $data = $request->user();

        $return["data"] = [
            "code" => $data["id"],
            "email" => $data["email"],
            "name" => $data["name"]
        ];


        return $return;
    }

    public function deleteUserByEmail($email){
        User::where([
            ["email", "=", $email]
        ])->delete();
    }
}
