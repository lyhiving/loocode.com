<footer>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="list-inline text-center m-0 p-0">
                    <li class="list-inline-item">
                        <a href="http://www.miibeian.gov.cn/" target="_blank">浙ICP备17018093号-2</a>
                    </li>
                    <li class="list-inline-item">
                        Copyright &copy;2017 | Powered by <a href="https://laraval.com">Laravel</a>
                    </li>
                </ul>
            </div>
            <div class="col-12">
                <ul class="list-inline text-center m-0 p-0">
                    <li class="list-inline-item">
                        <a target="_blank" href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=33010602010059">
                            <img src="{{ $static_domain }}/assets/images/beian.png" />浙公网安备 33010602010059号
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>
@if($user==null)
<div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog  modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">@lang('login'){{ $options['site_title'] }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-muted">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <ul class="list-inline">
                    <li class="list-inline-item m-3">
                        <a href="/oauth/qq" class="login-icon login-icon-qq"></a>
                    </li>
                    <li class="list-inline-item m-3">
                        <a href="/oauth/weixinweb" class="login-icon login-icon-wx"></a>
                    </li>
                    <li class="list-inline-item m-3">
                        <a href="/oauth/github" class="login-icon login-icon-git"></a>
                    </li>
                </ul>
            </div>
            <div class="modal-footer">@lang('login_tips')</div>
        </div>
    </div>
</div>
@endif
