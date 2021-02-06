@extends("layout")

@section("header_css")
<link rel="stylesheet" type="text/css" href="{{ mix("/assets/css/article.css")  }}"/>
@endsection

@section("content")
    <div class="container">
        <div class="row">
            <div class="col-12 col-xl-9 col">
                <div class="tutorial card">
                    <div class="card-body p-lg-30">
                        <div class="mb-2 border-bottom">
                            <h1 class="mb-4 h2">{{ $posts->post_title }}</h1>
                            <div class="mb-4">
                            <span class="text-custom-white">
                                <i class="theme-color">{{ $posts->author->name ?? "" }}</i>
                                · {{ date('Y-m-d', strtotime($posts->post_modified)) }}
                            </span>
                            </div>
                        </div>
                        <div class="content">
                            {!! str_replace('//image.', '//static.', $posts->post_content) !!}
                        </div>
                        <div class="my-4 text-center">
                            @foreach($posts->tags as $tag)<a href="/tag/{{ $tag }}" class="label label-pink mr-2">{{ $tag }}</a>@endforeach
                        </div>
                        <div class="my-4 text-center">
                            <a href="javascript:" id="btn-like" class="btn btn-outline-pink rounded-1 px-4">
                                <i class="fas fa-thumbs-up"></i>@lang('posts.like')
                            </a>
                            <a href="javascript:" class="ml-2 btn btn-outline-green rounded-1 px-4" data-toggle="modal" data-target="#appreciate">
                                @lang('posts.donation')
                            </a>
                        </div>
                        <div class="social-share text-right" data-initialized="true">
                            <a href="javascript:" class="social-share-icon icon-weibo"></a>
                            <a href="javascript:" class="social-share-icon icon-qq"></a>
                            <a href="javascript:" class="social-share-icon icon-wechat"></a>
                            <a href="javascript:" class="social-share-icon icon-douban"></a>
                            <a href="javascript:" class="social-share-icon icon-qzone"></a>
                        </div>
                    </div>
                </div>
                <div class="tutorial-comment">
                    @if($comments)
                        <div class="tutorial-comment-tree">
                            @foreach($comments as $item)
                                <div class="media">
                                    <div class="p-3 pt-0">
                                        <a href="/user/{{ $item->name }}">
                                            <img width="50" class="rounded-circle border-custom-white border"
                                                 @if($item->avatar)
                                                 src="{{ $item->avatar }}"
                                                 @else
                                                 src="{{ $static_domain }}/assets/images/default_avatar.png"
                                                 @endif
                                                 alt="{{ $item->name }} avatar">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <div class="media-heading">
                                            <a href="/user/{{ $item->name }}">{{ $item->name }}</a>
                                            @if(isset($item->saying))
                                            <span>·</span>
                                            <span>最重要的事，永远只有一件</span>
                                            @endif
                                            <div class="meta">
                                                <a name="reply{{ $loop->index + 1 }}"
                                                   id="reply{{ $loop->index +1 }}"
                                                   href="#reply{{ $loop->index + 1 }}">#{{ $loop->index + 1 }}</a>
                                                <span>·</span>
                                                <abbr
                                                    data-toggle="tooltip"
                                                    data-placement="top"
                                                    title="{{ $item->comment_date }}"
                                                    data-content="{{ $item->comment_date }}"
                                                    data-original-title="{{ $item->comment_date }}">
                                                    {{ $item->comment_date }}
                                                </abbr>
                                            </div>
                                        </div>
                                        <div class="content">
                                            {!! $item->comment_content !!}
                                        </div>
                                    </div>
                                    <div class="text-center justify-content-center">
                                        <a class="fas fa-reply text-custom-white" href="javascript:" title="回复"></a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <hr>
                    @endif

                    <div class="tutorial-comment-form">
                        <form>
                            <textarea placeholder="@lang('posts.comment_placeholder')" ></textarea>
                            <ul>
                                <li>
                                    <a title="插入代码" data-toggle="tooltip" data-placement="bottom" data-original-title="Code">
                                        <i class="fas fa-code text-custom-white"></i>
                                    </a>
                                </li>
                                <li>
                                    <a title="插入链接" data-toggle="tooltip" data-placement="bottom" data-original-title="link">
                                        <i class="fas fa-link text-custom-white"></i>
                                    </a>
                                </li>
                            </ul>
                            <button type="submit" class="btn btn-pink" data-loading-text="Loading..." id="commentButton">
                                <i class="fas fa-reply"></i> @lang('posts.comment')
                            </button>
                        </form>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="col-12 col-xl-3 col mt-4 mt-xl-0">
                <div class="author">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <picture class="rounded-circle mr-3">
                                    <img
                                        @if(isset($posts->author->avatar) && $posts->author->avatar)
                                        src="{{ $posts->author->avatar }}"
                                        @else
                                        src="{{ $station_domain }}/assets/images/default_avatar.png"
                                        @endif
                                        alt="avatar"
                                        class="rounded-circle" width="64" height="64">
                                </picture>
                                <div><h5 class="mb-0"><a href="javascript:" class="text-body">{{ $posts->author->name ?? ""  }}</a></h5></div>
                            </div>
                            <p class="text-secondary text-truncate-2">全栈工程师</p>
                            <div class="d-flex align-items-center mb-3">
                                <div class="mr-4">
                                    <strong>{{$posts->metas['_lc_post_views']}}</strong><span class="text-secondary"> @lang('posts.view')</span>
                                </div>
                                <div>
                                    <strong>{{$posts->metas['_lc_post_like']}}</strong><span class="text-secondary"> @lang('posts.like')</span>
                                </div>
                            </div>
                            <button type="button" class="btn btn-pink btn-block">关注作者</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(isset($posts->user->metas['alipayQr']) || isset($posts->user->metas['wechatQr']))
    <div class="modal fade" id="appreciate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog  modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">{{ $language['posts']['donation'] }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-muted">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <ul class="list-inline">
                        @if(isset($posts->user->metas['alipayQr']))
                        <li class="list-inline-item m-3">
                            <img style="border: 2px solid #449ee2"
                                 width="150"
                                 src="{{ $static_domain }}{{ $posts->user->metas['alipayQr'] }}"
                                 alt="支付宝收款码">
                        </li>
                        @endif
                        @if(isset($posts->user->metas['wechatQr']))
                        <li class="list-inline-item m-3">
                            <img style="border: 2px solid #53a849"
                                 width="150"
                                 src="{{ $static_domain }}{{ $posts->user->metas['wechatQr'] }}"
                                 alt="微信收款码">
                        </li>
                        @endif
                    </ul>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection

@section("footer_js")
<script type="text/javascript" src="{{ mix("/assets/js/article.js")  }}"></script>
@endsection
