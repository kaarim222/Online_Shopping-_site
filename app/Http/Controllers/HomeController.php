<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function index() {
        // $product=Product::paginate(10);
        return view('home.userpage');
    }

    public function redirect() {
        $usertype = Auth::user()->usertype;

        if($usertype=='1') {
            return view('admin.home');

        }
        else{
            return view('home.userpage');
        }


    }
    public function product_details($id){

        $product=Product::find($id);
        return view('home.product_details', compact('product'));
            
    }

    public function add_cart(Request $request, $id){
        if(Auth::id()){
            $user=Auth::user();
            $userid=$user->id;
            $product=product::find($id);
            $product_exist_id=cart::where('product_id', '=',$id)->where('user_id','=',$userid)->get('id')->first();

            if($product_exist_id){
                $cart=cart::find($product_exist_id)->first();
                $quantity=$cart->quantity;
                $cart->quantity=$quantity+$request->quantity;
                if($product->discount_price!=null){
                    $cart->price=$product->discount_price * $cart->quantity;
    
                }
                else{
                    $cart->price=$product->price * $cart->quantity;
                }
                $cart->save();
                return redirect()->back()->with('message','Product added successfully');

            }
            else{
                $cart=new cart;
                $cart->name=$user->name;
                $cart->email=$user->email;
                $cart->phone=$user->phone;
                $cart->address=$user->address;
                $cart->user_id=$user->id;
                $cart->product_title=$product->title;
    
                if($product->discount_price!=null){
                    $cart->price=$product->discount_price * $request->quantity;
    
                }
                else{
                    $cart->price=$product->price * $request->quantity;
                }
                
                $cart->product_id=$product->id;
                $cart->image=$product->image;
                $cart->quantity=$request->quantity;
    
                $cart->save();
                return redirect()->back()->with('message','Product added successfully');

            }



        }

        else{
            return redirect('login');
        }
    }

    public function show_cart(){
        if(Auth::id()){
            $id=Auth::user()->id;
        $cart=cart::where('user_id', '=', $id)->get();
        return view('home.showcart', compact('cart'));

        }
        else{
            return redirect('login');
        }
    }

    public function remove_cart($id){
        $cart=cart::find($id);
        $cart->delete();
        return redirect()->back();
    }
}
