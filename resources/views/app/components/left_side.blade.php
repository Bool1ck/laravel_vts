@use(\Illuminate\Support\Facades\Auth)

<div class="flex flex-col">
    <div class="p-1">
        @if(Auth::user()->regionRoles->count() > 0)
        <ul>
            @foreach(Auth::user()->regionRoles as $regionrole)
               <li><a href="{{ route('app.index',['region' => $regionrole->region->id]) }}">{{$regionrole->region->name}}</a></li>
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
                <div><a href="#">Населені пункти</a></div>
                <div><a href="#">Вулиці</a></div>
                <div><a href="#">ТП</a></div>
            </div>
        @endif
    @endif
</div>
