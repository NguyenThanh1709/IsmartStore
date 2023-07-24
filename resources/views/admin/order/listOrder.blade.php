@extends('layouts.admin')

@section('title', 'Danh sách đơn hàng')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                <div class="row">
                    <h5 class="m-0 col-md-8 col-xs-12">Danh sách đơn hàng</h5>
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
                    <a href="{{ route('order.index') }}" class="text-primary {{ !$status ? 'active-bottom' : '' }}">
                        Tất cả<span class="text-muted ">({{ $count[0] }})</span></a>
                    <a href="?status=processing" class="text-primary {{ $status == 'processing' ? 'active-bottom' : '' }}">
                        Đang xử lý<span class="text-muted ">({{ $count[2] }})</span></a>
                    <a href="?status=delivering" class="text-primary {{ $status == 'delivering' ? 'active-bottom' : '' }}">
                        Đang giao<span class="text-muted">({{ $count[4] }})</span></a>
                    <a href="?status=delivered" class="text-primary {{ $status == 'delivered' ? 'active-bottom' : '' }}">
                        Đã giao<span class="text-muted">({{ $count[1] }})</span></a>
                    <a href="?status=canceled" class="text-primary {{ $status == 'canceled' ? 'active-bottom' : '' }}">
                        Đã huỷ<span class="text-muted">({{ $count[3] }})</span></a>
                    <a href="{{ route('order.revenue') }}" class="text-primary">
                        Tổng doanh thu<span class="text-muted">({{number_format($revenue, 0 ,'',',')}}đ)</span></a>
                </div>
                <form action="{{ route('order.actions') }}" method="post">@csrf
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
                            <a class="btn btn-primary" href="{{ route('order.add') }}" role="button">Thêm mới</a>
                        </div>
                        <div class="btn-add--responsive">
                            <a class="btn btn-primary" href="{{ route('order.add') }}" role="button"><i
                                    class="fa-solid fa-plus"></i></a>
                        </div>
                    </div>
                    <table class="table table-checkall table-striped table-responsive-sm">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <input name="checkall" type="checkbox">
                                </th>
                                <th scope="col">#</th>
                                <th scope="col">Mã</th>
                                <th scope="col">Khách hàng</th>
                                <th scope="col">Giá trị đơn hàng</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Thời gian</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($orders->total() > 0)
                                @php $temp = (($orders->currentpage() - 1) * $orders->perpage() + 1) - 1 @endphp
                                @foreach ($orders as $order)
                                    @php $temp++; @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="list_check[]" value="{{ $order->id }}">
                                        </td>
                                        <td class="font-weight-bold">{{ $temp }}</td>
                                        <td>#CODE{{ $order->id }}</td>
                                        <td class="text-center">
                                            {{ $order->name }} <br> <span
                                                class="text-success font-weight-bold">0{{ $order->phone }}</span>
                                        </td>
                                        <td>{{ number_format($order->total, 0, '', ',') . 'đ' }}</td>
                                        <td>
                                            @if ($order->status == 'processing')
                                                <span class="badge badge-warning">Đang xử lý</span>
                                            @elseif ($order->status == 'delivered')
                                                <span class="badge badge-primary">Đã giao</span>
                                            @elseif ($order->status == 'canceled')
                                                <span class="badge badge-danger">Đã huỷ</span>
                                            @elseif ($order->status == 'delivering')
                                                <span class="badge badge-success">Đang giao</span>
                                            @endif
                                        </td>
                                        <td>{{ $order->created_at }}</td>
                                        <td>
                                            <a class="btn btn-success btn-info-order btn-sm rounded-0 text-white"
                                                type="button" data-toggle="modal" data-target="#exampleModal"
                                                data-url="{{ route('order.detail') }}" data-id="{{ $order->id }}"
                                                title="Edit"><i class="fa fa-edit"></i></a>
                                            <a href="{{ route('order.delete', $order->id) }}"
                                                class="btn btn-danger btn-delete btn-sm rounded-0 text-white" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Delete">
                                                <i class="fa fa-trash"></i></a>
                                        </td>
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
                {{ $orders->appends(['status' => $status])->links() }}
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" id="modal-dialog-order" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">THÔNG TIN ĐƠN HÀNG</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {!! Form::open(['', 'method' => 'POST']) !!}@csrf
                <div class="modal-body">
                    <h6>Thông tin khách hàng</h6>
                    <div class="wp-info-customer border p-2">
                        <div class="row">
                            <div class="col-12 col-sm-4">
                                {!! Form::label('name', 'Họ và tên', ['class' => 'name']) !!}
                                {!! Form::text('name', '', ['id' => 'name', 'class' => 'form-control', 'readonly' => 'readonly']) !!}
                            </div>
                            <div class="col-12 col-sm-4">
                                {!! Form::label('phone', 'Số điện thoại', ['class' => 'phone']) !!}
                                {!! Form::text('phone', '', ['id' => 'phone', 'class' => 'form-control', 'readonly' => 'readonly']) !!}
                            </div>
                            <div class="col-12 col-sm-4">
                                {!! Form::label('email', 'Email', ['class' => 'email']) !!}
                                {!! Form::text('email', '', ['id' => 'email', 'class' => 'form-control', 'readonly' => 'readonly']) !!}
                            </div>

                        </div>
                    </div>
                    <h6 class="pt-3">Thông tin đơn hàng</h6>
                    <div class="wp-info-customer border p-2">
                        <div class="row">
                            <div class="col-12 col-sm-4">
                                {!! Form::label('code', 'Mã đơn hàng', ['class' => 'code']) !!}
                                {!! Form::text('code', '', ['id' => 'code', 'class' => 'form-control', 'readonly' => 'readonly']) !!}
                            </div>
                            <div class="col-12 col-sm-4">
                                {!! Form::label('payment_method', 'Hình thức thanh toán', ['class' => 'payment_method']) !!}
                                {!! Form::text('payment_method', '', [
                                    'id' => 'payment_method',
                                    'class' => 'form-control',
                                    'readonly' => 'readonly',
                                ]) !!}
                            </div>
                            <div class="col-12 col-sm-4">
                                {!! Form::label('status', 'Trạng thái', ['class' => 'status']) !!}
                                {!! Form::select('status', $options, null, ['class' => 'form-control']) !!}
                            </div>
                            <div class="col-12 col-sm-4">
                                {!! Form::label('date_order', 'Ngày đặt', ['class' => 'date_order']) !!}
                                {!! Form::text('date_order', '', ['id' => 'date_order', 'class' => 'form-control', 'readonly' => 'readonly']) !!}
                            </div>
                            <div class="col-12 col-sm-4">
                                {!! Form::label('date_update', 'Cập nhật gần nhất', ['class' => 'date_update']) !!}
                                {!! Form::text('date_update', '', ['id' => 'date_update', 'class' => 'form-control', 'readonly' => 'readonly']) !!}
                            </div>
                            <div class="col-12 col-sm-4">
                                {!! Form::label('total', 'Gía trị đơn hàng', ['class' => 'total']) !!}
                                {!! Form::text('total', '', ['id' => 'total', 'class' => 'form-control', 'readonly' => 'readonly']) !!}
                            </div>
                            <div class="col-12 col-sm-12">
                                {!! Form::label('address', 'Địa chỉ giao hàng', ['class' => 'address']) !!}
                                {!! Form::text('address', '', ['id' => 'address', 'class' => 'form-control', 'readonly' => 'readonly']) !!}
                            </div>
                            <div class="col-12 col-sm-12 pt-2">
                                <table class="table table-striped ">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Ảnh sản phẩm</th>
                                            <th scope="col">Tên sản phẩm</th>
                                            <th scope="col">Số lượng</th>
                                            <th scope="col">Giá</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-modal" id="table-info-modal-product">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="button" data-id="" data-url="{{ route('order.update') }}"
                        class="btn btn-primary btn-update-order">Cập nhật đơn hàng</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
