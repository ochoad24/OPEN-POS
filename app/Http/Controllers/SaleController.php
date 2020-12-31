<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use \DB,\Response;

class SaleController extends Controller
{
    public function save(Request $request){
        DB::beginTransaction();
        try {
            // SAVE SALE
            $sale=new Sale;
            $sale->amount=$request->amount;
            $sale->exchange=$request->exchange;
            $sale->payment=$request->payment;
            $sale->save();
            // END SALE
            // NEW SALE DETAIL
            $products=json_decode($request->products,true);
            foreach($products as $product){
                $saleDetail=new SaleDetail;
                $saleDetail->idProduct=$product['id'];
                $saleDetail->idSale=$sale->id;
                $saleDetail->quantity=$product['quantity'];
                $saleDetail->price=$product['price'];
                $saleDetail->typePrice=$product['typePrice'];
                $saleDetail->subtotal=$product['subtotal'];
                $saleDetail->save();

                $Product=Product::find($product['id']);
                $Product->stock=$Product->stock-$saleDetail->quantity;
                $Product->save();
            };  
            // END SALE DETAIL
            DB::commit();
            return Response::json(['message' => 'La venta se ha guardado exitosamente','id'=>$sale->id], 200);
        }catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function get($id){
        $sale=Sale::find($id);
        $products=SaleDetail::join('products','products.id','sale_details.idProduct')
            ->select('products.id','products.name','sale_details.quantity','sale_details.subtotal')
            ->where('sale_details.idSale',$id)
            ->get();
        $response=array(['sale'=>$sale,'products'=>$products]);
        return Response::json($response, 200);
    }
}
