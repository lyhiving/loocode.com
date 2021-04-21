@extends("default.layout")
@section("content")
    <div class="container w-3/4 mx-auto my-5">
        <div class="flex flex-wrap">
            <div class="w-full lg:w-80 px-4 flex-initial">
                <div class="bg-white p-4">
                    <div class="">
                        <h3 class="text-xl text-gray-500 font-medium border-b pb-1 border-gray-200 after:border-b after:border-red-500">工具集合</h3>
                        <div class="pt-4">
                            <ul class="grid grid-cols-2 lg:grid-cols-1 gap-4 font-semibold text-gray-900 text-center">
                                @foreach($tools as $item)
                                    <li class="flex">
                                        <a href="@if($tool['name'] == $item['name'])#@else {{$item['href']}} @endif" class="relative rounded-xl ring-1 ring-black ring-opacity-5 shadow-sm w-full pt-8 pb-6 px-6">
                                            {{$item['name']}}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 lg:mt-0 flex-1 flex-grow px-4 overflow-hidden">
                <div class="bg-white border-b">
                    <nav aria-label="breadcrumb">
                        <ol class="px-6 py-3 m-0 flex items-center">
                            <li class=""><a href="/">@lang('main')</a></li>
                            <li class="px-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="8" height="8">
                                    <path d="M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z" fill="currentColor"/>
                                </svg>
                            </li>
                            <li>
                                <a href="/tools">工具</a>
                            </li>
                            <li class="px-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="8" height="8">
                                    <path d="M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z" fill="currentColor"/>
                                </svg>
                            </li>
                            <li aria-current="page" class="text-gray-400">{{$tool['name']}}</li>
                        </ol>
                    </nav>
                </div>
                <div class="bg-white" x-data="toolConvert()">
                    <div class="p-6 relative border-b border-opacity-5">
                        <form name="body" x-ref="form">
                            <input type="hidden" name="tool" value="{{$name}}">
                            @yield("body")
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("footer_js")
<script type="text/javascript">
    function toolConvert() {
        return {
            data: null,
            convert: function () {
                window.fetch('/api/tool/convert', {
                    method: 'POST',
                    body: new FormData(this.$refs.form),
                })
                .then((response) => {
                    response.json().then((body) => {
                        console.log(body);
                        if (body.code === 200) {
                            this.data = body.data;
                        }
                    }).catch((err) => {
                        console.log(err);
                    });
                })
                .catch((err) => {
                    console.log(err);
                })
            }
        };
    }
</script>
@endsection
