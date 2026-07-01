@use(\Illuminate\Support\Facades\Auth)

<div class="flex flex-col">
    <div class="p-1">
        @if(Auth::user()->isSuperAdmin())
            @foreach(\App\Models\Region::all() as $regionList)
                <li><a href="{{ route('connection_point.index',['region' => $regionList->id]) }}">{{$regionList->name}}</a></li>
            @endforeach
        @elseif(Auth::user()->regions()->count())
        <ul>
            @foreach(Auth::user()->regions as $regionList)
               <li><a href="{{ route('connection_point.index',['region' => $regionList->id]) }}">{{$regionList->name}}</a></li>
            @endforeach
        </ul>
        @else
            <div class="w-full text-center">Доступні РЕМ відсутні</div>
        @endif
    </div>
    <div>
        <hr>
    </div>
    @if(!request()->routeIs('dashboard') && !request()->routeIs('root.regions.*'))
        @if(Auth::user()->isCanEditRegion($region) || Auth::user()->isMainEngineerInRegion($region) || Auth::user()->isSuperAdmin())
            <div class="p-2">
                <div class="w-full text-center">Діючі</div>
                <div><a href="{{route('connection_point.index', ['region' => $region, 'filter' => "all_active"])}}">Всі</a></div> {{-- *all_active --}}
                <div class="ps-3"><a href="{{route('connection_point.index', ['region' => $region, 'filter' => "execution_out"])}}">Закінчується строк виконання</a></div> {{-- *execution_out --}}
                <div class="ps-3"><a href="{{route('connection_point.index', ['region' => $region, 'filter' => "ordering_materials_out"])}}">Не замовлені матеріали</a></div> {{-- *ordering_materials_out --}}
                <div class="ps-3"><a href="{{route('connection_point.index', ['region' => $region, 'filter' => "execution_fail"])}}">Прострочене виконання</a></div> {{-- execution_fail --}}
                <div><hr></div>
                <div class="w-full text-center">Архівні</div>
                <div class="ps-3"><a href="{{route('connection_point.index', ['region' => $region, 'filter' => "completed"])}}">Зроблені</a></div> {{-- *completed --}}
                <div><hr></div>
            </div>
        @endif
        @if(Auth::user()->isAdminInRegion($region) || Auth::user()->isSuperAdmin())
            <div class="p-2">
                <div class="w-full text-center">Admin panel</div>
                <div><a href="{{route('admin.users.index', ['region' => $region])}}">Користувачі</a></div>
                <div><a href="{{route('admin.cities.index',['region' => $region])}}">Довідник</a></div>
                <div><hr></div>
            </div>
        @endif
    @endif
    @if(Auth::user()->isSuperAdmin() && (request()->routeIs('root.regions.*') || request()->routeIs('dashboard')))
            <div class="p-2">
                <div class="w-full text-center">Root panel</div>
                <div><a href="{{route('root.regions.index')}}">Регіони</a></div>
                <div>
                    @if(config('app.env') === 'sandbox')
                        <form action="{{ route('root.regions.reset_sandbox') }}" method="POST" onsubmit="return confirm('Ви впевнені, що хочете повністю скинути та перезаписати стан тестової пісочниці?');">
                            @csrf
                            <button type="submit" class="a">
                                Скинути тестову пісочницю
                            </button>
                        </form>
                    @endif
                </div>
            </div>
    @endif

</div>
