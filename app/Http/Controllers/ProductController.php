<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Database\Factories\UserFactory;
use Illuminate\Support\Facades\DB;
// use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

session_start();

class ProductController extends Controller
{
    public function AuthLogin(){
        $admin_id = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('/dashboard');
        }else{
            return Redirect::to('/admin')->send();
        }
    }
    public function add_product(){
        $this->AuthLogin();
        $cate_product = DB::table('tbl_category_product')->orderby('category_id','desc')->get();
        $brand_product = DB::table('tbl_brand')->orderby('brand_id','desc')->get();

        return view('admin.add_product',[
            'cate_product' => $cate_product,
            'brand_product' => $brand_product
        ]);
    }
    public function all_product(){
        $this->AuthLogin();
        $all_product = DB::table('tbl_product')
        ->join('tbl_category_product','tbl_category_product.category_id','=','tbl_product.category_id')
        ->join('tbl_brand','tbl_brand.brand_id','=','tbl_product.brand_id')
        ->orderBy('tbl_product.product_id','desc')->get();
        // $maneger_product = view('admin.all_product')->with('all_product',$all_product);
        return view('admin.all_product')->with('all_product',$all_product);
    }
    public function save_product(Request $request){
        $this->AuthLogin();
        // $request->validate(['image'=>'required|mimes:jpg,png,jpeg,gif|max:5048']);
        // $generatedImageName = 'image'.time().'-'
        //                             .$request->name.'.'
        //                             .$request->image->extension();
        // $request->image->move(public_path('images'), $generatedImageName);
        $data = array();
        $data['product_name'] = $request->product_name;
        $data['product_price'] = $request->product_price;
        // $data['product_image'] = $generatedImageName;
        $data['product_desc'] = $request->product_desc;
        $data['product_content'] = $request->product_content;
        $data['category_id'] = $request->product_cate;
        $data['brand_id'] = $request->product_brand;
        $data['product_status'] = $request->product_status;

        $get_image = $request->file('product_image');
        if($get_image){
            $get_name_image = $get_image->getClientOriginalName();
            $get_name = current(explode('.',$get_name_image ));
            $new_image = $get_name.rand(0,999).'.'.$get_image->getClientOriginalExtension();
            $get_image->move('public/uploads/product',$new_image);
            $data['product_image'] = $new_image;     
            DB::table('tbl_product')->insert($data);
            Session::put('message', 'Thêm sản phẩm thành công!');
            return Redirect::to('add-product');
        }
        $data['product_image']= '';
        DB::table('tbl_product')->insert($data);
        Session::put('message', 'Thêm sản phẩm thành công!');
        return Redirect::to('add-product');
    }

    public function active_product($product_id){
        $this->AuthLogin();
        DB::table('tbl_product')->where('product_id',$product_id)->update(['product_status' => '0']);
        Session::put('message', 'Tắt kích hoạt thương hiệu sản phẩm thành công!');
        return Redirect::to('all-product');

    }
    public function unactive_product($product_id){
        $this->AuthLogin();
        DB::table('tbl_product')->where('product_id',$product_id)->update(['product_status' => '1']);
        Session::put('message', 'Kích hoạt thương hiệu sản phẩm thành công!');
        return Redirect::to('all-product');
    }

    public function edit_product($product_id){
        $this->AuthLogin();
        $cate_product = DB::table('tbl_category_product')->orderBy('category_id','desc')->get();
        $brand_product = DB::table('tbl_brand')->orderby('brand_id','desc')->get();
        // $products = DB::table('tbl_product')->where('product_id',$product_id)->get();
        $all_product = DB::table('tbl_product')->where('product_id',$product_id)
        ->join('tbl_category_product','tbl_category_product.category_id','=','tbl_product.category_id')
        ->join('tbl_brand','tbl_brand.brand_id','=','tbl_product.brand_id')
        ->orderBy('tbl_product.product_id','desc')->get();
        return view('admin.edit_product',[
            'cate_product' => $cate_product,
            'brand_product' => $brand_product,
            'all_product' => $all_product
        ]);
    }
    public function update_product(Request $request, $product_id){
        $this->AuthLogin();
        $data = array();
        $data['product_name'] = $request->product_name;
        $data['product_price'] = $request->product_price;
        // $data['product_image'] = $generatedImageName;
        $data['product_desc'] = $request->product_desc;
        $data['product_content'] = $request->product_content;
        $data['category_id'] = $request->product_cate;
        $data['brand_id'] = $request->product_brand;
        // $data['product_status'] = $request->product_status;

        $get_image = $request->file('product_image');
        if($get_image){
            $get_name_image = $get_image->getClientOriginalName();
            $get_name = current(explode('.',$get_name_image ));
            $new_image = $get_name.rand(0,999).'.'.$get_image->getClientOriginalExtension();
            $get_image->move('public/uploads/product',$new_image);
            $data['product_image'] = $new_image;     
            DB::table('tbl_product')->where('product_id',$product_id)->update($data);
            Session::put('message', 'Cập nhật sản phẩm thành công!');
            return Redirect::to('all-product');
        }
        // $data['product_image']= '';
        DB::table('tbl_product')->where('product_id',$product_id)->update($data);
        Session::put('message', 'Cập nhật sản phẩm thành công!');
        return Redirect::to('all-product');
    }
    public function delete_product($product_id){
        $this->AuthLogin();
        DB::table('tbl_product')->where('product_id',$product_id)->delete();
        Session::put('message','Xóa sản phẩm thành công!');
        return Redirect::to('all-product');
    }

    public function details_product($product_id){
        // $this->AuthLogin();
        $cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id','desc')->get();
        $brand_product = DB::table('tbl_brand')->where('brand_status','1')->orderby('brand_id','desc')->get();
        $details_product = DB::table('tbl_product')
        ->join('tbl_category_product','tbl_category_product.category_id','=','tbl_product.category_id')
        ->join('tbl_brand','tbl_brand.brand_id','=','tbl_product.brand_id')
        ->where('tbl_product.product_id',$product_id)->get();
        foreach($details_product as $key => $value){
            $category_id = $value->category_id;
        }
        $related_product = DB::table('tbl_product')
        ->join('tbl_category_product','tbl_category_product.category_id','=','tbl_product.category_id')
        ->join('tbl_brand','tbl_brand.brand_id','=','tbl_product.brand_id')
        ->where('tbl_category_product.category_id',$category_id)->whereNotIn('tbl_product.product_id',[$product_id])->get();
        
        return view('pages.products.show_details',[
            'cate_product' => $cate_product,
            'brand_product' => $brand_product,
            'details_product' => $details_product,
            'related_product' => $related_product
        ]);
    }
}
