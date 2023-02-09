<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Database\Factories\UserFactory;
use Illuminate\Support\Facades\DB;
// use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

session_start();

class BrandProduct extends Controller{

    public function AuthLogin(){
        $admin_id = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('/dashboard');
        }else{
            return Redirect::to('/admin')->send();
        }
    }
    public function add_brand_product(){
        $this->AuthLogin();
        return view('admin.add_brand_product');
    }
    public function all_brand_product(){
        $this->AuthLogin();
        $all_brand_product = DB::table('tbl_brand')->get();
        // $maneger_category_product = view('admin.all_category_product')->with('all_category_product',$all_category_product);
        // return view('admin_layout')->with('admin.category_product',$maneger_category_product);
        return view('admin.all_brand_product')->with('all_brand_product',$all_brand_product);
    }
    public function save_brand_product(Request $request){
        $this->AuthLogin();
        $data = array();
        $data['brand_name'] = $request->brand_product_name;
        $data['brand_desc'] = $request->brand_product_desc;
        $data['brand_status'] = $request->brand_product_status;
        // dd($data);

        DB::table('tbl_brand')->insert($data);
        Session::put('message', 'Thêm thương hiệu sản phẩm thành công!');
        return Redirect::to('add-brand-product');
    }

    public function active_brand_product($brand_id){
        $this->AuthLogin();
        DB::table('tbl_brand')->where('brand_id',$brand_id)->update(['brand_status' => '0']);
        Session::put('message', 'Tắt kích hoạt thương hiệu sản phẩm thành công!');
        return Redirect::to('all-brand-product');

    }
    public function unactive_brand_product($brand_id){
        $this->AuthLogin();
        DB::table('tbl_brand')->where('brand_id',$brand_id)->update(['brand_status' => '1']);
        Session::put('message', 'Kích hoạt thương hiệu sản phẩm thành công!');
        return Redirect::to('all-brand-product');
    }

    public function edit_brand_product($brand_id){
        $this->AuthLogin();
        $brands = DB::table('tbl_brand')->where('brand_id',$brand_id)->get();
        return view('admin.edit_brand_product')->with('brands',$brands);
    }
    public function update_brand_product(Request $request, $brand_id){
        $this->AuthLogin();
        $data = array();
        $data['brand_name'] = $request->brand_product_name;
        $data['brand_desc'] = $request->brand_product_desc;

        DB::table('tbl_brand')->where('brand_id',$brand_id)->update($data);
        Session::put('message', 'Cập nhật thương hiệu sản phẩm thành công!');
        return Redirect::to('all-brand-product');
    }
    public function delete_brand_product($brand_id){
        $this->AuthLogin();
        DB::table('tbl_brand')->where('brand_id',$brand_id)->delete();
        Session::put('message','Xóa thương hiệu sản phẩm thành công!');
        return Redirect::to('all-brand-product');
    }
    
    //end function admin page
    public function show_brand_home($brand_id){
        // $this->AuthLogin();
        $cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id','desc')->get();
        $brand_product = DB::table('tbl_brand')->where('brand_status','1')->orderby('brand_id','desc')->get();
        $brand_by_id = DB::table('tbl_product')->join('tbl_brand','tbl_brand.brand_id','=','tbl_product.brand_id')->where('tbl_product.brand_id',$brand_id)->get();
        $brand_name = DB::table('tbl_brand')->where('brand_id',$brand_id)->limit(1)->get();

        return view('pages.brands.show_brand',[
            'cate_product' => $cate_product,
            'brand_product' => $brand_product,
            'brand_by_id' => $brand_by_id,
            'brand_name' => $brand_name
        ]);
    }
}
