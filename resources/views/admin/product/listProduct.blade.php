@extends('layouts.admin')

@section('title', 'Danh sách sản phẩm')

@section('content')
    <div id="content" class="container-fluid ">
        <div class="card">
            <div class="card-header font-weight-bold">
                <div class="row">
                    <h5 class="m-0 col-md-8 col-xs-12">Danh sách sản phẩm</h5>
                    <form action="#"
                        class="form-search form d-flex justify-content-end align-items-center col-md-4 col-xs-12 mr-0">
                        {!! Form::text('keyword', request()->input('keyword'), [
                            'class' => 'form-control form-search mr-1',
                            'placeholder' => 'Tìm kiếm',
                        ]) !!}
                        {!! Form::submit('Tìm kiếm', ['class' => 'btn btn-primary']) !!}
                    </form>
                </div>
            </div>
            @if (session('status'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-bullhorn"></i> {{ session('status') }}
                </div>
            @endif
            @if (session('status-danger'))
                <div class="alert alert-danger">
                    <i class="fa-solid fa-bullhorn"></i> {{ session('status-danger') }}
                </div>
            @endif
            <div class="card-body">
                <div class="analytic">
                    <a href="{{ route('index.product') }}" class="text-primary {{ !$status ? 'active-bottom' : '' }}">
                        Hiệu lực<span class="text-muted ">({{ $count[0] + $count[1] }})</span></a>
                    <a href="?status=public" class="text-primary {{ $status == 'public' ? 'active-bottom' : '' }}">
                        Công khai<span class="text-muted">({{ $count[0] }})</span></a>
                    <a href="?status=pending" class="text-primary {{ $status == 'pending' ? 'active-bottom' : '' }}">
                        Chờ duyệt<span class="text-muted">({{ $count[1] }})</span></a>
                    <a href="?status=trash" class="text-primary {{ $status == 'trash' ? 'active-bottom' : '' }}">
                        Thùng rác<span class="text-muted">({{ $count[2] }})</span></a>
                </div>
                <form action="{{ route('action.product') }}" method="product">@csrf
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-action form-inline py-3">
                            <select class="form-control mr-1 list-option-responsive" name="act" id="">
                                <option>---Chọn tác vụ---</option>
                                @foreach ($list_option as $k => $act)
                                    <option value="{{ $k }}">{{ $act }}</option>
                                @endforeach
                            </select>
                            {!! Form::submit('Áp dụng', ['name' => 'btn-search', 'class' => 'btn btn-primary']) !!}
                        </div>
                        <div class="btn-add">
                            <a class="btn btn-primary" href="{{ route('product.add') }}" role="button">Thêm mới</a>
                        </div>
                        <div class="btn-add--responsive">
                            <a class="btn btn-primary" href="{{ route('product.add') }}" role="button"><i class="fa-solid fa-plus"></i></a>
                        </div>
                    </div>
                    <table class="table table-striped table-checkall table-responsive-sm">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <input name="checkall" type="checkbox">
                                </th>
                                <th scope="col">#</th>
                                <th scope="col">Ảnh</th>
                                <th scope="col">Tiêu đề</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Danh mục</th>
                                <th scope="col">Ngày tạo</th>
                                @if ($status == 'trash')
                                    <th scope="col">Ngày xoá</th>
                                @else
                                    <th scope="col">Tác vụ</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if ($listProduct->total() > 0)
                                @php $temp = (($listProduct->currentpage() - 1) * $listProduct->perpage() + 1) - 1 @endphp
                                @foreach ($listProduct as $product)
                                    @php $temp++; @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="list_check[]" value="{{ $product->id }}">
                                        </td>
                                        <td scope="row">{{ $temp }}</td>
                                        <td class="wp_thumbnail"><img
                                                src="{{ asset($product->thumbnail) }} "class="img_thumbnail"alt="">
                                        </td>
                                        <td><a href="{{ route('product.edit', $product->id) }}">{{ Str::limit($product->name, 50) }}</a>
                                        </td>
                                        @if ($product->status == 'pending')
                                            <td><span class="text-badge badge badge-warning">Chờ duyệt</span></td>
                                        @else
                                            <td><span class="text-badge badge badge-success">Công khai</span></td>
                                        @endif
                                        <td><span
                                                class="text-badge badge badge-success">{{ $product->productCat->name }}</span>
                                        </td>
                                        <td>{{ $product->created_at }}</td>
                                        @if ($status == 'trash')
                                            <td>{{ $product->deleted_at }}</td>
                                        @else
                                            <td>
                                                <a href="{{ route('product.edit', $product->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                        class="fa fa-edit"></i></a>
                                                <a href="{{ route('product.delete', $product->id) }}"
                                                    class="btn btn-danger btn-delete btn-sm rounded-0 text-white"
                                                    type="button" data-toggle="tooltip" data-placement="top"
                                                    title="Delete">
                                                    <i class="fa fa-trash"></i></a>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8" class="text-center bg-white">Không có bản ghi nào được tìm thấy!</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </form>
                {{ $listProduct->appends(['keyword' => request()->input('keyword'), 'status' => $status])->links() }}
            </div>
        </div>
    </div>
@endsection
