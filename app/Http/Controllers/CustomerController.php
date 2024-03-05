<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class CustomerController extends Controller
{

    public function productList()
    {
        $user_id=Auth::user()->id;
        $products = Product::where('user_id',$user_id)->get();
        return response()->json(['products' => $products], 200);
    }
}
