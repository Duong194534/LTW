<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    
    public function index(){
        $cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id','desc')->get();
        $brand_product = DB::table('tbl_brand')->where('brand_status','1')->orderby('brand_id','desc')->get();
        $all_product = DB::table('tbl_product')->where('product_status','1')->orderby('product_id','desc')->limit(6)->get();
        return view('pages.home',[
            'cate_product'=> $cate_product,
            'brand_product'=>$brand_product,
            'all_product'=>$all_product
        ]);
    }
    public function search(Request $request){
        $cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id','desc')->get();
        $brand_product = DB::table('tbl_brand')->where('brand_status','1')->orderby('brand_id','desc')->get();

        $keywords = $request->keyword;
        $search_product = DB::table('tbl_product')->where('product_name','like','%'.$keywords.'%')->get();
        

        return view('pages.products.search',[
            'cate_product'=> $cate_product,
            'brand_product'=>$brand_product,
            'search_product'=>$search_product
        ]);
    }
}
