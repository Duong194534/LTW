<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Database\Factories\UserFactory;
use Illuminate\Support\Facades\DB;
// use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    public function save_cart(Request $request){
        // $cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id','desc')->get();
        // $brand_product = DB::table('tbl_brand')->where('brand_status','1')->orderby('brand_id','desc')->get();

        $productId = $request->productid_hidden;
        $quantity = $request->qty;
        $product_info = DB::table('tbl_product')->where('product_id', $productId)->first(); // sao deo get() duoc nhi?
        // $data;
        // Cart::add('293ad', 'Product 1', 1, 9.99, 550);
        $data['id'] = $productId;
        $data['qty'] = $quantity;
        $data['name'] = $product_info->product_name;
        $data['price'] = $product_info->product_price;
        $data['weight'] = '123';
        $data['options']['image'] = $product_info->product_image;
        Cart::setGlobalTax(5);
        Cart::add($data);
        // Cart::destroy();
        return Redirect::to('/show-cart');
        
    }

    public function show_cart(){
        $cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id','desc')->get();
        $brand_product = DB::table('tbl_brand')->where('brand_status','1')->orderby('brand_id','desc')->get();

        return view('pages.cart.show_cart',[
            'cate_product' => $cate_product,
            'brand_product' => $brand_product,
        ]);
    }

    public function delete_to_cart($rowId){
        Cart::update($rowId,0);
        return Redirect::to('/show-cart');
    }
    public function update_cart_quantity(Request $request){
        $rowId = $request->rowId_cart;
        $qty = $request -> quantity;
        Cart::update($rowId,$qty);
        return Redirect::to('/show-cart');
    }
}
