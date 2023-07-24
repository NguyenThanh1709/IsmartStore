@extends('layouts.admin')
@section('title', 'Danh sách trang')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                <div class="row">
                    <h5 class="m-0 col-md-8 col-xs-12">Danh sách trang</h5>
                    <form action="#"
                        class="form-search form d-flex justify-content-end align-items-center col-md-4 col-xs-12 mr-0">
                        <input type="search" name="keyword" class="form-control form-search mr-1"
                            value="{{ request()->input('keyword') }}" placeholder="Tìm kiếm">
                        <input type="submit" value="Tìm kiếm" class="btn btn-primary">
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
                    <a href="{{ route('index.page') }}" class="text-primary {{ !$status ? 'active-bottom' : '' }}">Hiệu
                        lực<span class="text-muted ">({{ $count[0] + $count[1] }})</span></a>
                    <a href="?status=public" class="text-primary {{ $status == 'public' ? 'active-bottom' : '' }}">Công
                        khai<span class="text-muted">({{ $count[0] }})</span></a>
                    <a href="?status=pending" class="text-primary {{ $status == 'pending' ? 'active-bottom' : '' }}">Chờ
                        duyệt<span class="text-muted">({{ $count[1] }})</span></a>
                    <a href="?status=trash" class="text-primary {{ $status == 'trash' ? 'active-bottom' : '' }}">Thùng
                        rác<span class="text-muted">({{ $count[2] }})</span></a>
                </div>
                <form action="{{ route('action.page') }}" method="POST">@csrf
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
                            <a class="btn btn-primary" href="{{ route('add.page') }}" role="button">Thêm mới</a>
                        </div>
                        <div class="btn-add--responsive">
                            <a class="btn btn-primary" href="{{ route('add.page') }}" role="button"><i class="fa-solid fa-plus"></i></a>
                        </div>
                    </div>
                    <table class="table table-striped table-checkall table-responsive-sm">
                        <thead>
                            <tr>
                                <th scope="col"><input name="checkall" type="checkbox"> </th>
                                <th scope="col">#</th>
                                <th scope="col">Tên trang</th>
                                <th scope="col">Tiêu đề</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Ngày tạo</th>
                                @if ($status == 'trash')
                                    <th scope="col">Ngày xoá</th>
                                @else
                                    <th scope="col">Tác vụ</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if ($listPage->total() > 0)
                                @php $temp = (($listPage->currentpage() - 1) * $listPage->perpage() + 1) - 1 @endphp
                                @foreach ($listPage as $page)
                                    @php $temp++; @endphp
                                    <tr>
                                        <td><input type="checkbox" name="list_check[]" value="{{ $page->id }}"></td>
                                        <td scope="row">{{ $temp }}</td>
                                        <td>{{ $page->name }}</td>
                                        <td><a
                                                href="{{ route('edit.page', $page->id) }}">{{ Str::limit($page->title, 45) }}</a>
                                        </td>
                                        @if ($page->status == 'pending')
                                            <td><span class="text-badge badge badge-warning">Chờ duyệt</span></td>
                                        @else
                                            <td><span class="text-badge badge badge-success">Công khai</span></td>
                                        @endif
                                        </td>
                                        <td>{{ $page->created_at }}</td>
                                        @if ($status == 'trash')
                                            <td>{{ $page->deleted_at }}</td>
                                        @else
                                            <td>
                                                <a href="{{ route('edit.page', $page->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                        class="fa fa-edit"></i></a>
                                                <a href="{{ route('delete.page', $page->id) }}"
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
                {{ $listPage->appends(['keyword' => request()->input('keyword'), 'status' => $status])->links() }}
            </div>
        </div>
    </div>
@endsection
