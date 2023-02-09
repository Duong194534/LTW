<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Database\Factories\UserFactory;
// use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

session_start();

class CategoryProduct extends Controller
{
    public function AuthLogin(){
        $admin_id = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('/dashboard');
        }else{
            return Redirect::to('/admin')->send();
        }
    }
    
    public function add_category_product(){
        $this->AuthLogin();
        return view('admin.add_category_product');
    }
    public function all_category_product(){
        $this->AuthLogin();
        $all_category_product = DB::table('tbl_category_product')->get();
        // $maneger_category_product = view('admin.all_category_product')->with('all_category_product',$all_category_product);
        // return view('admin_layout')->with('admin.category_product',$maneger_category_product);
        return view('admin.all_category_product')->with('all_category_product',$all_category_product);

    }
    public function save_category_product(Request $request){
        $this->AuthLogin();
        $data = array();
        $data['category_name'] = $request->category_product_name;
        $data['category_desc'] = $request->category_product_desc;
        $data['category_status'] = $request->category_product_status;
        // dd($data);

        DB::table('tbl_category_product')->insert($data);
        Session::put('message', 'Thêm danh mục sản phẩm thành công!');
        return Redirect::to('add-category-product');
    }

    public function active_category_product($category_id){
        $this->AuthLogin();
        DB::table('tbl_category_product')->where('category_id',$category_id)->update(['category_status' => '0']);
        Session::put('message', 'Tắt kích hoạt danh mục sản phẩm thành công!');
        return Redirect::to('all-category-product');

    }
    public function unactive_category_product($category_id){
        $this->AuthLogin();
        DB::table('tbl_category_product')->where('category_id',$category_id)->update(['category_status' => '1']);
        Session::put('message', 'Kích hoạt danh mục sản phẩm thành công!');
        return Redirect::to('all-category-product');
    }

    public function edit_category_product($category_id){
        $this->AuthLogin();
        $categories = DB::table('tbl_category_product')->where('category_id',$category_id)->get();
        return view('admin.edit_category_product')->with('categories',$categories);
    }
    public function update_category_product(Request $request, $category_id){
        $this->AuthLogin();
        $data = array();
        $data['category_name'] = $request->category_product_name;
        $data['category_desc'] = $request->category_product_desc;

        DB::table('tbl_category_product')->where('category_id',$category_id)->update($data);
        Session::put('message', 'Cập nhật danh mục sản phẩm thành công!');
        return Redirect::to('all-category-product');
    }
    public function delete_category_product($category_id){
        $this->AuthLogin();
        DB::table('tbl_category_product')->where('category_id',$category_id)->delete();
        Session::put('message','Xóa danh mục sản phẩm thành công!');
        return Redirect::to('all-category-product');
    }

    //End functio Admin page
    public function show_category_home($category_id){
        // $this->AuthLogin();
        $cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id','desc')->get();
        $brand_product = DB::table('tbl_brand')->where('brand_status','1')->orderby('brand_id','desc')->get();
        $category_by_id = DB::table('tbl_product')->join('tbl_category_product','tbl_category_product.category_id','=','tbl_product.category_id')->where('tbl_product.category_id',$category_id)->get();
        $category_name = DB::table('tbl_category_product')->where('category_id',$category_id)->limit(1)->get();
        return view('pages.categories.show_category',[
            'cate_product' => $cate_product,
            'brand_product' => $brand_product,
            'category_by_id' => $category_by_id,
            'category_name' => $category_name
        ]);
    }
}
