<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Database\Factories\UserFactory;
use Illuminate\Support\Facades\DB;
// use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Gloudemans\Shoppingcart\Facades\Cart;
use Termwind\Components\Dd;

class CheckoutController extends Controller
{
    public function AuthLogin(){
        $admin_id = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('/dashboard');
        }else{
            return Redirect::to('/admin')->send();
        }
    }

    public function login_checkout(){
        $cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id','desc')->get();
        $brand_product = DB::table('tbl_brand')->where('brand_status','1')->orderby('brand_id','desc')->get();
        return view('pages.checkout.login_checkout',[
            'cate_product'=> $cate_product,
            'brand_product'=>$brand_product,
        ]);
    }

    public function add_customer(Request $request){
        $data = array();
        $data['customer_name'] = $request->customer_name;
        $data['customer_email'] = $request->customer_email;
        $data['customer_password'] = md5($request->customer_password);
        $data['customer_phone'] = $request->customer_phone;

        $customer_id= DB::table('tbl_customers')->insertGetId($data);
        Session::put('customer_id', $customer_id);
        Session::put('customer_name',$request->customer_name);

        return Redirect::to('/checkout');
    }

    public function checkout(){
        $cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id','desc')->get();
        $brand_product = DB::table('tbl_brand')->where('brand_status','1')->orderby('brand_id','desc')->get();
        return view('pages.checkout.checkout',[
            'cate_product'=> $cate_product,
            'brand_product'=>$brand_product,
        ]);
    }

    public function save_checkout_customer(Request $request){
        $data = array();
        $data['shipping_name'] = $request->shipping_name;
        $data['shipping_email'] = $request->shipping_email;
        $data['shipping_address'] = $request->shipping_address;
        $data['shipping_phone'] = $request->shipping_phone;
        $data['shipping_notes'] = $request->shipping_notes;

        $shipping_id= DB::table('tbl_shipping')->insertGetId($data);
        Session::put('shipping_id', $shipping_id);
        // Session::put('customer_name',$request->customer_name);

        return Redirect::to('/payment');
    }
    public function payment(){
        $cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id','desc')->get();
        $brand_product = DB::table('tbl_brand')->where('brand_status','1')->orderby('brand_id','desc')->get();
        return view('pages.checkout.payment',[
            'cate_product'=> $cate_product,
            'brand_product'=>$brand_product,
        ]);
    }
    public function logout_checkout(){
        Session::flush(); // xóa dữ liệu phiên
        return Redirect::to('/login-checkout');
    }
    public function login_customer(Request $request){
        $customer_email = $request->email_account;
        $customer_password = md5($request->password_account);
        $result = DB::table('tbl_customers')->where('customer_email', $customer_email)->where('customer_password', $customer_password)->first();

        if($result){
            Session::put('customer_id',$result->customer_id);
            return Redirect::to('checkout');
        }else{
            return Redirect::back(); // nên back hay nên trả về trang chủ hoặc giỏ hàng hơn?
        }

    }

    public function order_place(Request $request){
        //insert payment method
        $payment_data = array();
        $payment_data['payment_method'] = $request->payment_option;
        $payment_data['payment_status'] = 'Đang chờ xử lý';
        $payment_id = DB::table('tbl_payment')->insertGetId($payment_data);

        //insert order
        $order_data = array();
        $order_data['customer_id'] = Session::get('customer_id');
        $order_data['shipping_id'] = Session::get('shipping_id');
        $order_data['payment_id'] = $payment_id;
        $order_data['order_total'] = Cart::total(0,',','.');
        $order_data['order_status'] = 'Đang chờ xử lý';
        $order_id = DB::table('tbl_order')->insertGetId($order_data);

        //insert order details
        $content = Cart::content();

        foreach($content as $v_content){
            $order_d_data = array();
        $order_d_data['order_id'] = $order_id;
        $order_d_data['product_id'] = $v_content->id;
        $order_d_data['product_name'] = $v_content->name;
        $order_d_data['product_price'] = $v_content->price;
        $order_d_data['product_sales_quantity'] = $v_content->qty;
        DB::table('tbl_order_details')->insert($order_d_data);
        }
        if($payment_data['payment_method'] == 1){
            echo 'Thanh toán bằng thẻ ATM';
        }elseif($payment_data['payment_method'] == 2){
            // echo 'Thanh toán bằng tiền mặt';
            Cart::destroy();
            $cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id','desc')->get();
            $brand_product = DB::table('tbl_brand')->where('brand_status','1')->orderby('brand_id','desc')->get();
            return view('pages.checkout.handcash',[
                'cate_product'=> $cate_product,
                'brand_product'=>$brand_product,
            ]);
        }else{
            echo 'Thanh toán bằng thẻ ghi nợ';
        }
        // return Redirect::to('/payment');
        
    }
    public function order_manager(){
        $this->AuthLogin();
        $all_order = DB::table('tbl_order')
        ->join('tbl_customers','tbl_customers.customer_id','=','tbl_order.customer_id')
        ->select('tbl_order.*','tbl_customers.customer_name')
        ->orderBy('tbl_order.order_id','desc')->get();
        // $maneger_order = view('admin.all_product')->with('all_product',$all_product);
        return view('admin.order_manager')->with('all_order',$all_order);
    }

    public function view_order($orderId){
         $this->AuthLogin();
        $order_by_id = DB::table('tbl_order')->where('tbl_order.order_id',$orderId)
        ->join('tbl_customers','tbl_customers.customer_id','=','tbl_order.customer_id')
        ->join('tbl_shipping','tbl_shipping.shipping_id','=','tbl_order.shipping_id')
        ->join('tbl_order_details','tbl_order_details.order_id','=','tbl_order.order_id')
        ->select('tbl_order.*','tbl_customers.*','tbl_shipping.*','tbl_order_details.*')
        ->first();
        dd($order_by_id);
        $order_by_id1 = DB::table('tbl_order')->where('tbl_order.order_id',$orderId)
        ->join('tbl_customers','tbl_customers.customer_id','=','tbl_order.customer_id')
        ->join('tbl_shipping','tbl_shipping.shipping_id','=','tbl_order.shipping_id')
        ->join('tbl_order_details','tbl_order_details.order_id','=','tbl_order.order_id')
        ->select('tbl_order.*','tbl_customers.*','tbl_shipping.*','tbl_order_details.*')
        ->get();
        dd($order_by_id1);

        // $maneger_order = view('admin.all_product')->with('all_product',$all_product);
        return view('admin.view_order')->with('order_by_id',$order_by_id);
    }
}
