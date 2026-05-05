@extends('app.layouts.main')

@section('content')
    <div class="w-fit">
        <div class="m-1"><a href="{{ route('admin.streets.create',['region' => $region]) }}"><div class="btn">Додати нову вулицю</div></a></div>
        <div>
            @foreach($cities as $city)
                <div class="flex flex-row">
                    <div class="bg-indigo-50">{{$city->fullName() . " ,Streets count: " . $city->streets()->count()}}</div>
                    <div><a href="#">Show streets</a></div>
                </div>
            @endforeach
        </div>
    </div>
    <div>
        {{ $cities->links() }}
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
