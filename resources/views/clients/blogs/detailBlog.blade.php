@extends('layouts.client')

@section('title', 'title')

@section('content-right')
    <div class="secion" id="breadcrumb-wp">
        <div class="secion-detail">
            <ul class="list-item clearfix">
                <li>
                    <a href="" title="">Trang chủ</a>
                </li>
                <li>
                    <a href="" title="">Blog</a>
                </li>
                <li>
                    <a href="" title="">Chi tiết blog</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="section" id="detail-blog-wp">
        <div class="section-head clearfix">
            <h3 class="section-title">{{ $infoPost->title }}</h3>
        </div>
        <div class="section-detail">
            <span class="create-date">{{ $infoPost->created_at }}</span>
            <div class="detail">
                {!! $infoPost->content !!}
            </div>
        </div>
    </div>
    <div class="section" id="social-wp">
        <div class="section-detail">
            <div class="fb-like" data-href="" data-layout="button_count" data-action="like" data-size="small"
                data-show-faces="true" data-share="true"></div>
            <div class="g-plusone-wp">
                <div class="g-plusone" data-size="medium"></div>
            </div>
            <div class="fb-comments" id="fb-comment" data-href="" data-numposts="5"></div>
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
                        <a href="{{ route('product.detail',['slug' => $item->Product->slug, 'id' => $item->Product->id]) }}" title="" class="thumb fl-left">
                            <img src="{{ asset($item->thumbnail) }}" alt="Ảnh sản phẩm">
                        </a>
                        <div class="info fl-right">
                            <a href="{{ route('product.detail',['slug' => $item->Product->slug, 'id' => $item->Product->id]) }}" title=""
                                class="product-name">{{ $item->product->name }}</a>
                            <div class="price">
                                @if ($item->Warehouse->discount > 0)
                                    <span class="new">{{ number_format($item->Warehouse->sale_off, 0, '', ',') }}đ</span>
                                    <span class="old">{{ number_format($item->Warehouse->price, 0, '', ',') }}đ</span>
                                @else
                                    <span class="new">{{ number_format($item->Warehouse->price, 0, '', ',') }}đ</span>
                                @endif

                            </div>
                            <a href="{{ route('product.detail',['slug' => $item->Product->slug, 'id' => $item->Product->id]) }}" title="" role="button"
                                class="w-100 btn btn_detail">Chi tiết</a>
                        </div>
                    </li>
                @endforeach

            </ul>
        </div>
    </div>
    <div class="section" id="banner-wp">
        <div class="section-detail">
            <a href="?page=detail_blog_product" title="" class="thumb">
                <img src="public/images/banner.png" alt="">
            </a>
        </div>
    </div>
@endsection
