@extends('app.layouts.main')

@section('content')
    <div class="w-fit">
        <div><a href="{{ route('admin.tps.create',['region' => $region]) }}">Додати нове ТП</a></div>
        <div>
            @foreach($cities as $city)
                <div>
                    <div class="bg-indigo-50">{{$city->fullName() . " ,TPs count: " . $city->tps()->count()}}</div>
                </div>
                <div class="grid grid-cols-2">
                    @foreach($city->tps as $tp)
                            <div class="w-fit">{{$tp->fullName()}}</div>
                            <div class="w-fit ps-2"><a href="{{route('admin.tps.edit',['region' => $region, 'tp' => $tp])}}">edit</a></div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
    <div>
        {{ $cities->links() }}
    </div>
@endsection
