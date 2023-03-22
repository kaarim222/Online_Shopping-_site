<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function view_catagory(){
        $data=Catagory::all();
        return view('admin.catagory', compact('data'));
    }
    public function view_product(){
        $catagory=catagory::all();
        return view('admin.product', compact('catagory'));
    }

    public function add_product(Request $request){
        $product=new product;
        $product->title=$request->title;
        $product->description=$request->description;
        $product->price=$request->price;
        $product->quantity=$request->quantity;
        $product->discount_price=$request->dis_price;
        $product->catagory=$request->catagory;

        $image=$request->image;

        $imagename=time().'.'.$image->getClientOriginalExtension();
        $request->image->move('product', $imagename);
        $product->image=$imagename;
        $product->save();

        return redirect()->back()->with('message','Product added successfully');


    }

    public function show_product(){
        $product=product::all();
        return view('admin.show_product', compact('product'));
    }

    public function delete_product($id){
        $product=product::find($id);
        $product->delete();
        return redirect()->back()->with('message','Product deleted successfully');

    }

    public function update_product($id){
        $product=product::find($id);
        $catagory=catagory::all();
        return view('admin.update_product', compact('product', 'catagory'));
       
    }

    public function update_product_confirm(Request $request,$id){
        $product=product::find($id);

        $product->title=$request->title;
        $product->description=$request->description;
        $product->price=$request->price;
        $product->quantity=$request->quantity;
        $product->discount_price=$request->dis_price;
        $product->catagory=$request->catagory;

        $image=$request->image;

        if($image){

        $imagename=time().'.'.$image->getClientOriginalExtension();
        $request->image->move('product', $imagename);
        $product->image=$imagename;
        }
        $product->save();

        return redirect()->back()->with('message','Product updated successfully');

    }

}
