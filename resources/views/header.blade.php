<div class="site-header-navbar container-full mb-4">
    <div class="navbar navbar-expand-xl navbar-custom-green bg-white">
        <div class="container">
            <a href="{{ $options['site_url'] }}" class="navbar-brand text-muted">
                <img src="{{ $static_domain }}/assets/images/main-64.png" width="30" height="30" alt="{{ $options['site_url'] }}logo" />
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <form method="get" action="/search" class="form-inline my-2 my-lg-0">
                    <div class="form-group">
                        <input type="text" name="q" class="form-control" placeholder="@lang('search')">
                        <div class="form-control-focus"></div>
                        <i class="fas fa-search mx-2"></i>
                    </div>
                </form>
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item mx-3">
                        <a class="nav-link" href="/" rel="nofollow" title="LooCode">@lang('main')</a>
                    </li>
                    <li class="nav-item mx-3">
                        <a class="nav-link" href="/post/tutorial">@lang('tutorial')</a>
                    </li>
                    <li class="nav-item mx-3">
                        <a class="nav-link" href="/post/question">@lang('question')</a>
                    </li>
                    @foreach($menu as $item)
                    <li class="nav-item mx-3">
                        <a class="nav-link" href="@if($item['type'] == 'category')/category/{{ $item['name'] }}@elseif($item['type'] == 'post_tag')/tag/{{ $item['name'] }}@else{{ $item['uri'] }}@endif">
                            {{ $item['name'] }}
                        </a>
                    </li>
                    @endforeach
                </ul>
                <ul class="navbar-nav align-items-center">
                    @if($user)
                        <li class="nav-item mx-2">
                            <a href="/user/setting">@lang('setting')</a>
                        </li>
                        <li class="nav-item  mx-2">
                            <a href="/logout">@lang('logout')</a>
                        </li>
                        <li class="nav-item  mx-2">
                            <a href="javascript:">
                                <img width="35" class="rounded-circle" alt="{{ $user->display_name }} avatar" src="{{ $user->avatar }}" />
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:" data-toggle="modal" data-target="#login">@lang('login')</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
