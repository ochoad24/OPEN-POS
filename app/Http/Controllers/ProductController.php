<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use \DB,\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $product=new Product;
            $product->name= $request->name;
            $product->price=$request->price;
            $product->priceSale= $request->priceSale;
            $product->priceMinSale= $request->priceMinSale;
            $product->profit= $request->profit;
            $product->code= $request->code;
            $product->description= $request->description;
            $product->stock= $request->stock;
            $product->save();
            DB::commit();
            return Response::json(['message' => 'El producto se ha credo exitosamente'], 200);
        }catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $product;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        DB::beginTransaction();
        try {
            $product->name=$request->name;
            $product->price=$request->price;
            $product->priceSale= $request->priceSale;
            $product->priceMinSale= $request->priceMinSale;
            $product->profit= $request->profit;
            $product->code= $request->code;
            $product->description= $request->description;
            $product->stock= $request->stock;
            $product->save();
            DB::commit();
            return Response::json(['message' => 'El producto se ha modificado exitosamente'], 200);
        }catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        DB::beginTransaction();
        try {
            $product->delete();
            DB::commit();
            return Response::json(['message' => 'El producto se ha eliminado exitosamente'], 200);
        }catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    /**
     * Search especific product in list with code or name.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function getProducts(Request $request){
        $search = explode(" ",$request->filter);
        return Product::select('id','name','priceSale','priceMinSale','code','stock',DB::raw('1 as quantity'),DB::raw('0 as subtotal'))
            ->where(function($query) use($search){
                foreach($search as $item){
                    $query->where('name','like','%'.$item.'%')
                    ->orWhere('code','like','%'.$item.'%');
                }
            })
            ->get();
    }
}
