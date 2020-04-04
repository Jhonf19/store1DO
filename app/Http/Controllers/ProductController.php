<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Customer;
use App\Credit;
use App\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('products.index');
    }

    public function getProducts(){
        $products = Product::all();
        
        return $products;
    }

    public function searchProducts(Request $request){
        $products = DB::table('products')->select('*')->where('description','like','%'.$request->get('word').'%')->get();
        return $products;
    }

    public function getProductInfo($id){
        $product = Product::find($id);
        // $credit = Credit::where('customer',$id)->first();
        // return [ 'customer' => $customer, 'credit' => $credit];
        return $product;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //almacena en storage no en public
        // $img_name = time()."_".$image->getClientOriginalName();
        // Storage::disk('local')->put($img_name,  \File::get($image));
        
        //tambien funciona
        // $image = $request->file('image');
        // $img_name = time()."_".$image->getClientOriginalName();
        // $image->move('images/products',$img_name);




        
        $image = $request->file('image');
        $img_name ="p_".time().'.'.$image->getClientOriginalExtension();
        // Storage::disk('products')->put($img_name, \File::get($image));
        $path = $request->image->storeAs('products',$img_name,'public');
        // $link = Storage::url($img_name);




        $product = new Product();
        $product->description = $request->get('description');
        $product->cost = $request->get('cost');
        $product->price = $request->get('price');
        $product->stock = $request->get('stock');
        $product->image = $path;
        $product->save();

        return $path;
        // return public_path($path);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('products.show')->with(compact('id'));
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
    public function updateImg(Request $request,$id)
    {
        $image = $request->file('image');
        $old_image = $request->get('old_image');

        $img_name ="p_".time().'.'.$image->getClientOriginalExtension();
        $path = $request->image->storeAs('products',$img_name,'public');

        $product = Product::find($id);
        $product->image = $path;
        $product->save();

        // return $image->getClientOriginalName();
        $exists = Storage::disk('public')->exists($old_image);
        if ($exists) {
            Storage::disk('public')->delete($old_image);
        } 
        return $path;
        
    }

    public function update(Request $request,$id){
        
        $product = Product::find($id);
        $product->description = $request->get('description');
        $product->cost = $request->get('cost');
        $product->price = $request->get('price');
        $product->stock = $request->get('stock');
        $product->save();
        return 'Información Editada'; 
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
