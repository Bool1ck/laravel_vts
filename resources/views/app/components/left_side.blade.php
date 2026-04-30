@use(\Illuminate\Support\Facades\Auth)

<div class="flex flex-col">
    <div class="p-1">
        @if(Auth::user()->regions()->count())
        <ul>
            @foreach(Auth::user()->regions as $region)
               <li><a href="{{ route('connection_point.index',['region' => $region->id]) }}">{{$region->name}}</a></li>
            @endforeach
        </ul>
        @else
            <div class="w-full text-center">Доступні РЕМ відсутні</div>
        @endif
    </div>
    <div>
        <hr>
    </div>
    @if(!request()->routeIs('dashboard'))
        @if(Auth::user()->isAdminInRegion($region))
            <div class="p-2">
                <div class="w-full text-center">Admin panel</div>
                <div><a href="#">Користувачі</a></div>
                <div><a href="{{route('admin.cities.index',['region' => $region->id])}}">Населені пункти</a></div>
                <div><a href="{{route('admin.streets.index',['region' => $region->id])}}">Вулиці</a></div>
                <div><a href="#">ТП</a></div>
            </div>
        @endif
    @endif
</div>
