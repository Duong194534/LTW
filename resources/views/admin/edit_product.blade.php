@extends('admin_layout')
@section('admin_content')
    <div class="row">
            <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Cập nhật sản phẩm
                        </header>
                        <div class="panel-body">
                            @foreach ($all_product as $product)
                                <div class="position-center">
                                <form role="form" action="{{URL::to('/update-product/'.$product->product_id)}}" method="post" enctype="multipart/form-data">
                                    {{csrf_field()}}
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tên sản phẩm</label>
                                    <input type="text" value="{{$product->product_name}}" name="product_name" class="form-control" id="exampleInputEmail1" placeholder="Tên sản phẩm">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Giá sản phẩm</label>
                                    <input type="text" value="{{$product->product_price}}" name="product_price" class="form-control" id="exampleInputEmail1" placeholder="Giá sản phẩm">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Hình ảnh sản phẩm</label>
                                    <input type="file" value="{{$product->product_image}}" name="product_image" class="form-control" id="exampleInputEmail1" placeholder="Hình ảnh sản phẩm">
                                    <img src="{{URL::to('public/uploads/product/'.$product->product_image)}}" height="100" width="100">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Mô tả sản phẩm</label>
                                    <textarea style="resize:none" rows="5" class="form-control" name="product_desc" id="exampleInputPassword1" placeholder="Mô tả sản phẩm">{{$product->product_desc}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Nội dung sản phẩm</label>
                                    <textarea style="resize:none" rows="5" class="form-control" name="product_content" id="exampleInputPassword1" placeholder="Nội dung sản phẩm">{{$product->product_content}}</textarea>
                                </div>
                            @endforeach

                                 <div class="form-group">
                                    <label for="exampleInputPassword1">Danh mục sản phẩm</label>
                                    <select name="product_cate" class="form-control input-sm m-bot15">
                                        @foreach ($cate_product as $key => $cate)
                                        @if ($cate->category_id == $product->category_id)
                                            <option selected value="{{$cate->category_id}}">{{$cate->category_name}}</option>
                                        @else
                                            <option value="{{$cate->category_id}}">{{$cate->category_name}}</option>
                                        @endif
                                            
                                        @endforeach
                                        
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Thương hiệu</label>
                                    <select name="product_brand" class="form-control input-sm m-bot15">
                                        @foreach ($brand_product as $key => $brand)
                                            @if ($brand->brand_id == $product->brand_id)
                                                <option selected value="{{$brand->brand_id}}">{{$brand->brand_name}}</option>
                                            @else
                                                <option value="{{$brand->brand_id}}">{{$brand->brand_name}}</option>
                                            @endif
                                            
                                        @endforeach
                                    </select>
                                </div>
                                {{-- <div class="form-group">
                                    <label for="exampleInputPassword1">Hiển thị</label>
                                    <select style="margin-left: 20px" name="product_status" class="form-select input-sm m-bot15">
                                
                                        @foreach ($all_product as $product)
                                            @if ($product->product_status == '0')
                                            
                                            <option selected value="{{$product->product_status}}">Ẩn</option>
                                            
                                        
                                            @else
                                            <option value="{{$product->product_status}}">Hiển thị</option>
                                            
                                            @endif
                                        @endforeach
                                 
                                    </select>
                                </div> --}}
                                <div class="checkbox">
                                    
                                </div>
                                <button name="add_product" type="submit" class="btn btn-info">Cập nhật sản phẩm</button>
                                    <?php   
                                    $message = Session::get('message');
                                    if($message){
                                        echo '<span style="margin-left:50px" class= "text-alert">',$message,'</span>';
                                        Session::put('message',null);
                                    }
                                    ?>
                            </form>
                            </div>
                        </div>
                    </section>

            </div>
            
    </div>
@endsection