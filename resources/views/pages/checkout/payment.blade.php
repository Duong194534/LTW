@extends('layout')

@section('content')
    <section id="cart_items">
		<div class="container">
			<div class="breadcrumbs">
				<ol class="breadcrumb">
				  <li><a href="#">Trang chủ</a></li>
				  <li class="active">Thanh toán giỏ hàng</li>
				</ol>
			</div><!--/breadcrums-->

			<div class="review-payment">
				<h2>Giỏ hàng</h2>
			</div>

            <div class="table-responsive cart_info">
				{{-- {{$content = Cart::content()}} --}}
				<?php
					$content = Cart::content();
				?>
				<table class="table table-condensed">
					<thead>
						<tr class="cart_menu">
							<td class="image">Hình ảnh</td>
							<td class="description">Tên sản phẩm</td>
							<td class="price">Giá</td>
							<td class="quantity">Số lượng</td>
							<td class="total">Tổng tiền</td>
							<td></td>
						</tr>
					</thead>
					<tbody>
						@foreach ($content as $key => $content)
                            <tr style="margin: 10px">
							<td style="margin:0%" class="cart_product">
								<a href=""><img src="{{asset('public/uploads/product/'.$content->options->image)}}" width="100" alt=""></a>
							</td>
							<td class="cart_description">
								<h4><a href="">{{$content->name}}</a></h4>
								<p>ID: {{$content->id}}</p>
							</td>
							<td class="cart_price">
								<p>{{number_format($content->price)}}$</p>
							</td>
							<td class="cart_quantity">
								<div class="cart_quantity_button">
									<form style="display: inline-flex" action="{{URL::to('/update-cart-quantity')}}" method="post">
										{{ csrf_field() }}
									<input class="cart_quantity_input" type="text" name="quantity" value="{{$content->qty}}" autocomplete="off" size="2">
										<input type="hidden" value="{{$content->rowId}}" name="rowId_cart" class="form-control">
										<input type="submit" value="Cập nhật" name="update_qty" class="btn btn-default btn-sm">
									</form>
								</div>
							</td>
							<td class="cart_total">
								<p class="cart_total_price"><?php $subtotal= $content->price*$content->qty; echo number_format($subtotal)?>$</p>
							</td>
							<td class="cart_delete">
								<a class="cart_quantity_delete" href="{{URL::to('/delete-to-cart/'.$content->rowId)}}"><i class="fa fa-times"></i></a>
							</td>
						</tr>
                        @endforeach

						
					</tbody>
				</table>
			</div>
			<h4 style="margin:40px 0; font-size:20px">Chọn hình thức thanh toán</h4>
			<form action="{{URL::to('/order-place')}}" method="post">
				{{ csrf_field() }}
				<div  class="payment-options">
					<span>
						<label><input name="payment_option" value="1" type="checkbox"> Thanh toán bằng thẻ ATM</label>
					</span>
					<span>
						<label><input name="payment_option" value="2" type="checkbox"> Thanh toán bằng tiền mặt</label>
					</span>
					<span>
						<label><input name="payment_option" value="3" type="checkbox"> Thanh toán bằng thẻ ghi nợ</label>
					</span>
					<input style="margin-top:-10px; width:10%" type="submit" value="Đặt hàng" name="send_order_place" class="btn btn-primary btn-sm">
				</div> 
			</form>
		</div>
	</section> <!--/#cart_items-->
@endsection