@use(\Illuminate\Support\Facades\Auth)

<div class="flex flex-col">
    <div class="p-1">
        @if(Auth::user()->regionRoles->count() > 0)
{{--            <select name="regions" id="regions" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-1 shadow-sm">--}}
{{--                @foreach(Auth::user()->regionRoles as $regionrole)--}}
{{--                    <option value="{{$regionrole->region->id}}"><a href="#">{{$regionrole->region->name}}</a></option>--}}
{{--                @endforeach--}}
{{--            </select>--}}
        <ul>
            @foreach(Auth::user()->regionRoles as $regionrole)
               <li><a href="{{ route('app.index',['region' => $regionrole->region->id]) }}">{{$regionrole->region->name}}</a></li>
            @endforeach
        </ul>

        @else
            <div class="w-full text-center">Empty Regions</div>
        @endif

    </div>
    <div>
        <hr>
    </div>
    <div class="w-full text-center">Admin panel</div>
    <div><a href="#">Some text</a></div>
    <div><a href="#">Some text</a></div>
    <div><a href="#">Some text</a></div>
    <div><a href="#">Some text</a></div>
    <div><a href="#">Some text</a></div>
    <div><a href="#">Some text</a></div>
</div>
