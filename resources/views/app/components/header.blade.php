<header class="w-full lg:max-w-4xl max-w-[335px] text-sm not-has-[nav]:hidden py-2 px-2" >
    @if (Route::has('login'))
        <nav class="flex items-center  gap-4">
            @auth
                <div>Користувач : {{\Illuminate\Support\Facades\Auth::user()->name}}
{{--                    @isset($region), відділ: {{$region->userRole()->name}}--}}
{{--                    @endisset--}}
                </div>
                <a
                    href="{{ route('dashboard') }}"
                    class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal"
                >
                    Info Page
                </a>|
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">Вийти</button>
                </form>
            @else
                <a
                    href="{{ route('login') }}"
                    class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal"
                >
                    Log in
                </a>

                @if (Route::has('register'))
                    <a
                        href="{{ route('register') }}"
                        class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                        Register
                    </a>
                @endif
            @endauth
        </nav>
    @endif

</header>
@if(isset($region))
    <div class="bg-gray-200 flex w-full py-2 px-2">
        <a href="{{ route('connection_point.index',['region' => $region->id]) }}">{{$region->name}}</a>&nbsp;>>
        @if (Route::is('connection_point.show'))
            <div>{{$cp->customer}}({{$cp->technical_conditions}})</div>
        @elseif(Route::is('connection_point.edit'))
            <div>{{$cp->customer}}({{$cp->technical_conditions}}) [Внесення змін]</div>
        @elseif(Route::is('connection_point.index'))
                <a class="ps-1" href="{{ route('connection_point.create', ['region' => $region->id]) }}">Нове приєднання</a>
        @elseif(Route::is('connection_point.create'))
            <p class="ps-1" style="color: #1BCD1B">Нова точка приєднання</p>
        @elseif(Route::is('admin.cities.*'))
            <p class="ps-1" style="color: #1BCD1B">Admin >> Населені пункти</p>
        @endif
    </div>
@endif
    <hr class="p-0 m-0">
