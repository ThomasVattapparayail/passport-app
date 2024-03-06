<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    
    public function viewAllUsers()
    {
        $user=Auth::user();
        if($user){
            
            if($user->role_id==1)
            {
                $users=User::all();
            }else{
                $users=[];
            }
        }
        
        
        return response()->json(['data'=>$users]);
    }

    public function userUpdate(Request $request, User $user)
    {
        $validator= Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role_id'=>'required'
            
        ]);
        
        if ($validator->fails()) 
        {
            return response()->json([$validator->errors()], 403);
        }
         
        
            $user->update([
                'name' => $request->name,
                'email'=>$request->email,
                'password'=>$request->password,
                'role_id'=>$request->role_id,

            ]);

        return response()->json(['message' => 'User updated successfully', 'data' => $user]);
        
    }

    public function userDestroy(User $user)
    {
            $user->delete();
            return response()->json(['message' => 'User deleted successfully',$token]);
     
    }

    public function addProduct(Request $request)
    {
       
        $validator= Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            // 'vendor_id'=>'required'
        ]);

        if ($validator->fails()) {
            return response()->json([$validator->errors()], 403);
          }

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'user_id'=>Auth::user()->id,
        ]);
        
        return response()->json(['message' => 'Product added successfully', 'data' => $product], 201);
    }

    public function editProduct(Request $request, Product $product)
    {
    
        $product->update([
            'name' => $request->name,
            'price' => $request->price,
        ]);

        return response()->json(['message' => 'Product updated successfully', 'data' =>  $product]);
    }
    

    public function deleteProduct(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
        
    }

    public function addStock(Request $request)
    {
        $validator= Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
           
        ]);

        if ($validator->fails()) {
            return response()->json([$validator->errors()], 403);
          } 

        $stock = Stock::create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
        ]);

        return response()->json(['message' => 'Stock added successfully', 'data' => $stock], 201);
    }

    public function editStock(Request $request, Stock $stock)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
            
        ]);

        $stock->update([
            'quantity' => $request->quantity,
            
        ]);

        return response()->json(['message' => 'Stock updated successfully', 'data' => $stock]);
    }

    public function deleteStock(Stock $stock)
    {
        $stock->delete();

        return response()->json(['message' => 'Stock deleted successfully']);
    }

}
