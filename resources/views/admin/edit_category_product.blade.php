@extends('admin_layout')
@section('admin_content')
    <div class="row">
            <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Thêm danh mục sản phẩm
                        </header>
                        <div class="panel-body">
                            @foreach ($categories as $key=>$category)
                                <div class="position-center">
                                <form role="form" action="{{URL::to('/update-category-product/'.$category->category_id)}}" method="post">
                                    {{csrf_field()}}
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tên danh mục</label>
                                    <input type="text" value="{{$category->category_name}}" name="category_product_name" class="form-control" id="exampleInputEmail1">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Mô tả danh mục</label>
                                    <textarea style="resize:none" rows="5" class="form-control" name="category_product_desc" id="exampleInputPassword1">{{$category->category_desc}}
                                    </textarea>
                                </div>
                                <div class="checkbox">
                                    
                                </div>
                                <button name="update_category_product" type="submit" class="btn btn-info">Cập nhật danh mục</button>
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