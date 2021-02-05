@extends("layout")
@section("content")
    <div class="container my-5">
        <div class="row">
            <div class="col-12 col-xl-9">
                <div class="d-flex featured-tags">
                    <div class="col-2 p-4  text-center">
                        <a href="/tag/php" title="php专题"><i class="devicon-php-plain"></i></a>
                    </div>
                    <div class="col-2 p-4 text-center">
                        <a href="/tag/docker" title="docker专题"><i class="devicon-docker-plain"></i></a>
                    </div>
                    <div class="col-2 p-4 text-center">
                        <a href="/tag/java" title="java专题"><i class="devicon-java-plain"></i></a>
                    </div>
                    <div class="col-2 p-4 text-center">
                        <a href="/tag/golang" title="golang专题"><i class="devicon-go-plain"></i></a>
                    </div>
                    <div class="col-2 p-4 text-center">
                        <a href="/tag/mysql" title="mysql专题"><i class="devicon-mysql-plain"></i></a>
                    </div>
                    <div class="col-2 p-4 text-center">
                        <a href="/tag/javascript" title="javascript专题"><i class="devicon-javascript-plain"></i></a>
                    </div>
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
                @if ($loop->index == 1)
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-format="fluid"
                     data-ad-layout-key="-dy-6f+m7+bm-20a"
                     data-ad-client="ca-pub-1413550160662632"
                     data-ad-slot="8061439532"></ins>
                @endif
                @endforeach
                <div class="media p-4">
                    {{ $posts->links("vendor/pagination/bootstrap-4")  }}
                </div>
            </div>
            <div class="col-12 col-xl-3">
                <div class="row widget mx-0">
                    <div class="card widget">
                        <div class="card-header">
                            <h3 class="card-title">@lang("top_question")</h3>
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                @foreach($hotPosts as $posts)
                                <li class="list-group-item">
                                    <span>{{ $loop->iteration }}.</span>
                                    <a href="/post/{{ $posts->id }}">{{ $posts->post_title}}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
