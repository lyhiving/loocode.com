@extends("default.tools.tool")
@section("body")
    <div class="flex">
        <label class="text-gray-700">模式</label>
        <div class="flex-1 ml-4">
            <div>
                <label><input name="mode" value="1" checked="" type="radio"> 音标</label>
                <label class="mx-2"><input name="mode" value="2" type="radio"> 无音标</label>
                <label><input name="mode" value="3" type="radio"> 首字母</label>
                <label class="mx-2"><input name="mode" value="4" type="radio"> 多音</label>
            </div>
        </div>
    </div>
    <div class="flex my-5">
        <label class="text-gray-700">文本</label>
        <div class="flex-1 ml-4">
            <textarea name="text" rows="10" class="placeholder-gray-500 mt-0 block w-full p-2 text-sm border-2 border-gray-200 dark:bg-gray-800 dark:text-gray-50 focus:ring-0 focus:border-red-500"
                      placeholder="输入你要转换的汉字"></textarea>
        </div>
    </div>
    <div class="flex my-5 justify-center">
        <button type="button" class="bg-red-500 text-white rounded-md px-3 py-1 text-xl font-bold" @click.prevent="convert()">转换</button>
    </div>
    <div class="flex my-5">
        <label class="text-gray-700">结果</label>
        <div class="flex-1 ml-4">
            <textarea rows="10" x-model="data" class="placeholder-gray-500 mt-0 block w-full p-2 text-sm border-2 border-gray-200 dark:bg-gray-800 dark:text-gray-50 focus:ring-0 focus:border-red-500"
                      placeholder="这里是转换之后结果">
            </textarea>
        </div>
    </div>
@endsection
