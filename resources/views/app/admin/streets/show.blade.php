@extends('app.layouts.main')

@section('content')
    <div class="w-fit">
{{--        <div>--}}
{{--            <a href="{{ route('admin.streets.create',['region' => $region]) }}"><div class="btn">Додати нову вулицю</div></a>--}}
{{--        </div>--}}
        <div>
            <div>
                <div class="bg-indigo-50">{{$city->fullName() . " ,Streets count: " . $city->streets()->count()}}</div>
            </div>
            <div class="grid grid-cols-2">
                @foreach($city->streets as $street)
                    <div class="w-fit">{{$street->fullName()}}</div>
                    <div class="w-fit ps-2"><a href="{{route('admin.streets.edit',['region' => $region, 'street' => $street])}}">edit</a></div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .btn {
            border: 1px solid #ccc;
            padding: 3px;
            padding-left: 5px;
            padding-right: 5px;
            background-color: #cfc;
        }

        .btn:hover {
            border: 1px solid #ccc;
            padding: 3px;
            padding-left: 5px;
            padding-right: 5px;
            background-color: #3f3;
        }
    </style>
@endpush
