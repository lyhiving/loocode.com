@extends("layout")
@section("header_css")
    <link rel="stylesheet" type="text/css" href="{{ mix("/assets/css/filepond.css")  }}"/>
@endsection

@section("content")
    <div class="container my-5">
        <div class="row bg-white">
            <div class="col-md-3 d-md-block d-none">
                <header>
                    <div class="mb-3 pt-3">
                        <h3 class="font-weight-bold">设置</h3>
                    </div>
                </header>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item border-0">
                        <i class="iconfont icon-email font-size-18"></i>
                        <a href="#email">邮箱</a>
                    </li>
                    <li class="list-group-item border-0">
                        <i class="iconfont icon-account font-size-18"></i>
                        <a href="#account">账户</a>
                    </li>
                    <li class="list-group-item border-0">
                        <i class="iconfont icon-security font-size-18"></i>
                        <a href="#security">安全</a>
                    </li>
                    <li class="list-group-item border-0">
                        <i class="iconfont icon-account font-size-18"></i>
                        <a href="#third">第三方</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-9 col-12">
                <header>
                    <div class="mb-3 pt-3">
                        <h3 id="email">邮箱设置</h3>
                    </div>
                </header>
                <ul class="list-group list-group-flush mb-5 list-bordered">
                    <li class="list-group-item border-0 py-6">
                        <div class="pb-md-0 pb-3">
                            <div class="h5">你的邮箱</div>
                            <div class="mt-3 d-flex">
                                <label class="form-label">
                                    <input class="form-control border-0" disabled id="email-text" placeholder="example@gmail.com"
                                           type="email" value="{{ $user->user_email }}"/>
                                </label>
                                <div class="email-btn-container">
                                    <button class="btn btn-pink ml-2" id="btn-email">修改邮箱</button>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
                <header>
                    <div class="mb-3">
                        <h3 id="account">账户</h3>
                    </div>
                </header>
                <ul class="list-group list-group-flush list-bordered">
                    <li class="list-group-item border-0 py-6 d-flex justify-content-between">
                        <div class="pb-md-0 pb-3">
                            <div class="h5">
                                支付宝二维码
                            </div>
                            <div class="mt-3">
                                上传个人支付宝收款二维码用于赞赏
                            </div>
                            @if(isset($metas['alipayQr']))
                            <div class="mt-3">
                                <img
                                    width="150"
                                    src="{{ $static_domain }}{{ $metas['alipayQr'] }}"
                                    alt="支付宝收款码">
                            </div>
                            @endif
                        </div>
                        <div class="d-flex position-relative">
                            <div class="pond">
                                <input type="file"
                                       class="filepond"
                                       name="alipayQr"
                                       data-allow-image-preview="true"
                                       data-instant-upload="false"
                                       data-max-file-size="1MB"
                                       data-max-files="1">
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item border-0 py-6 d-flex justify-content-between">
                        <div class="pb-md-0 pb-3">
                            <div class="h5">
                                微信二维码
                            </div>
                            <div class="mt-3">
                                上传个人微信收款二维码用于赞赏
                            </div>
                            @if(isset($metas['wechatQr']))
                            <div class="mt-3">
                                <img
                                    width="150"
                                    src="{{ $static_domain }}{{ $metas['wechatQr'] }}"
                                    alt="微信收款码">
                            </div>
                            @endif
                        </div>
                        <div class="d-flex position-relative">
                            <div class="pond">
                                <input type="file"
                                   class="filepond"
                                   name="wechatQr"
                                   data-allow-image-preview="true"
                                   data-instant-upload="false"
                                   data-max-file-size="1MB"
                                   data-max-files="1">
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

@endsection
@section("footer_js")
    <script type="text/javascript" src="{{ mix("/assets/js/user.js") }}"></script>
@endsection
