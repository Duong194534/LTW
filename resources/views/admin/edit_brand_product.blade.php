@extends('admin_layout')
@section('admin_content')
    <div class="row">
            <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Thêm thương hiệu sản phẩm
                        </header>
                        <div class="panel-body">
                            @foreach ($brands as $key=>$brand)
                                <div class="position-center">
                                <form role="form" action="{{URL::to('/update-brand-product/'.$brand->brand_id)}}" method="post">
                                    {{csrf_field()}}
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tên thương hiệu</label>
                                    <input type="text" value="{{$brand->brand_name}}" name="brand_product_name" class="form-control" id="exampleInputEmail1">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Mô tả thương hiệu</label>
                                    <textarea style="resize:none" rows="5" class="form-control" name="brand_product_desc" id="exampleInputPassword1">{{$brand->brand_desc}}
                                    </textarea>
                                </div>
                                <div class="checkbox">
                                    
                                </div>
                                <button name="update_brand_product" type="submit" class="btn btn-info">Cập nhật thương hiệu</button>
                                    <?php   
                                    $message = Session::get('message');
                                    if($message){
                                        echo '<span style="margin-left:50px" class= "text-alert">',$message,'</span>';
                                        Session::put('message',null);
                                    }
                                    ?>
                            </form>
                            </div>
                            @endforeach
                            

                        </div>
                    </section>

            </div>
            
    </div>
@endsection