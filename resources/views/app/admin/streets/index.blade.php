@extends('app.layouts.main')

@section('content')
    <div class="w-fit">
        <div><a href="{{ route('admin.streets.create',['region' => $region]) }}">Додати нову вулицю</a></div>
        <div>
            @foreach($cities as $city)
                <div>
                    <div class="bg-indigo-50">{{$city->fullName() . " ,Streets count: " . $city->streets()->count()}}</div>
                </div>
                <div class="grid grid-cols-2">
                    @foreach($city->streets as $street)
                            <div class="w-fit">{{$street->fullName()}}</div>
                            <div class="w-fit ps-2"><a href="{{route('admin.streets.edit',['region' => $region, 'street' => $street])}}">edit</a></div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
    <div>
        {{ $cities->links() }}
    </div>
@endsection
