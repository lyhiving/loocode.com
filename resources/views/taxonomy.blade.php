@extends("layout")
@section("content")
    <div class="container my-5">
        <div class="row">
            <div class="col-12 col-xl-9">
                <div class="media">
                    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                        <ol class="breadcrumb bg-white m-0">
                            <li class="breadcrumb-item"><a href="/">@lang('main')</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $taxonomy }}</li>
                        </ol>
                    </nav>
                </div>
                @foreach($posts as $item)
                    <div class="media p-4 position-relative">
                        <div class="media-body">
                            <h4 class="mt-0 h5">
                                <a href="/post/{{ $item->id }}" title="{{ $item->post_title }}">
                                    {{ $item->post_title }}
                                </a>
                            </h4>
                            <div class="d-flex">
                                <div class="article-attributes">
                                    <a href="/user/{{ $item->name }}"><span>{{ $item->name }}</span></a>
                                    <span>·</span>
                                    <span>{{ date('Y/m/d', strtotime($item->post_modified)) }}</span>
                                    @if($item->tags)
                                        <span>·</span>
                                        <a href="/tag/{{ $item->tags[0] }}">{{ $item->tags[0] }}</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="col-12 col-xl-3">
                <div class="row widget mx-0">
{{--                    <div class="card widget">--}}
{{--                        <div class="card-header">--}}

{{--                        </div>--}}
{{--                        <div class="card-body">--}}

{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
    </div>
@endsection
