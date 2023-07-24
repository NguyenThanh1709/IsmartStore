@extends('layouts.client')

@section('title', 'title')

@section('content-right')
    <div class="secion" id="breadcrumb-wp">
        <div class="secion-detail">
            <ul class="list-item clearfix">
                <li>
                    <a href="{{ asset('home.index') }}" title="">Trang chủ</a>
                </li>
                <li>
                    <a href="" title="">Giới thiệu</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="section" id="detail-blog-wp">
        <div class="section-head clearfix">
            <h3 class="section-title">{{ $pageIntroduce->first()->title }}</h3>
        </div>
        <div class="section-detail">
            <span class="create-date">{{ $pageIntroduce->first()->created_at }}</span>
            <div class="detail">
                {!! $pageIntroduce->first()->content !!}
            </div>
        </div>
    </div>
@endsection
@section('content-left')
    <div class="section" id="selling-wp">
        <div class="section-head">
            <h3 class="section-title">Sản phẩm bán chạy</h3>
        </div>
        <div class="section-detail">
            <ul class="list-item">
                @foreach ($productASC as $item)
                    <li class="clearfix">
                        <a href="{{ route('product.detail', ['slug' => $item->Product->slug, 'id' => $item->Product->id]) }}" title="" class="thumb fl-left">
                            <img src="{{ asset($item->thumbnail) }}" alt="Ảnh sản phẩm">
                        </a>
                        <div class="info fl-right">
                            <a href="{{ route('product.detail', ['slug' => $item->Product->slug, 'id' => $item->Product->id]) }}" title=""
                                class="product-name">{{ $item->product->name }}</a>
                            <div class="price">
                                @if ($item->Warehouse->discount > 0)
                                    <span class="new">{{ number_format($item->Warehouse->sale_off, 0, '', ',') }}đ</span>
                                    <span class="old">{{ number_format($item->Warehouse->price, 0, '', ',') }}đ</span>
                                @else
                                    <span class="new">{{ number_format($item->Warehouse->price, 0, '', ',') }}đ</span>
                                @endif

                            </div>
                            <a href="{{ route('product.detail', ['slug' => $item->Product->slug, 'id' => $item->Product->id]) }}" title="" role="button"
                                class="w-100 btn btn_detail">Chi tiết</a>
                        </div>
                    </li>
                @endforeach

            </ul>
        </div>
    </div>
    {{-- <div class="section" id="banner-wp">
        <div class="section-detail">
            @foreach ($banners as $banner)
                <a href="{{ $banner->link }}" title="" class="thumb mb-2 d-block">
                    <img src="{{ asset($banner->thumbnail) }}" alt="">
                </a>
            @endforeach
        </div>
    </div> --}}
@endsection
