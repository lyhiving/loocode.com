<div class="w-full mb-4 bg-white shadow-md dark:bg-gray-800 border-t-4 border-red-500">
    <div class="container w-3/4 mx-auto px-4">
        <div class="flex justify-start items-center py-3">
            <div class="flex justify-start flex-1 md:flex-none mx-2 md:mx-0">
                <a href="{{ $options['site_url'] }}">
                    <span class="sr-only">LooCode</span>
                    <img class="h-8 w-auto sm:h-11" src="{{ $static_domain }}/assets/images/main-64.png" alt="{{ $options['site_url'] }}logo" />
                </a>
            </div>
            <div class="hidden md:block md:ml-8 w-72">
                <form method="get" action="/search">
                    <label class="block">
                        <input type="text"
                               name="q"
                               class="placeholder-gray-500 mt-0 block w-full px-0.5 py-2 text-sm border-0 border-b-2 border-gray-200 dark:bg-gray-800 dark:text-gray-50 focus:ring-0 focus:border-red-500"
                               placeholder="搜索">
                    </label>
                </form>
            </div>
            <div class="hidden md:block flex-1">
                <ul class="font-medium">
                    <li class="px-3">
                        <a class="hover:text-red-500" href="/" rel="nofollow" title="LooCode">@lang('main')</a>
                    </li>
                    @foreach($menu as $item)
                    <li class="px-3">
                        <a class="hover:text-red-500" href="@if($item['type'] == 'category')/category/{{ $item['name'] }}@elseif($item['type'] == 'post_tag')/tag/{{ $item['name'] }}@else{{ $item['uri'] }}@endif">
                            {{ $item['name'] }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="mx-2 md:mx-0">
                <ul class="flex items-center">
                    @if($user)
                        <li>
                            <a href="/user/setting">@lang('setting')</a>
                        </li>
                        <li class="mx-4">
                            <a href="/logout">@lang('logout')</a>
                        </li>
                        <li>
                            <a href="javascript:">
                                <img class="rounded-full h-11 w-auto border border-gray-200 overflow-hidden" alt="{{ $user->display_name }} avatar" src="{{ $user->avatar }}" />
                            </a>
                        </li>
                    @else
                        <li class="cursor-pointer">
                            <span @click="login = true">@lang('login')</span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
