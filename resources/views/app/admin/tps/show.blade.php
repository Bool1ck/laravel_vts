@extends('app.layouts.main')

@section('content')
    <div class="w-fit">
        <div><a class="btn" href="{{ route('admin.tps.create',['region' => $region]) }}">Додати нове ТП</a></div>
        <div>
                <div>
                    <div class="bg-indigo-50">{{$city->fullName() . " ,TPs count: " . $city->tps()->count()}}</div>
                </div>
                <div class="grid grid-cols-2">
                    @foreach($city->tps as $tp)
                        <div class="w-fit">{{$tp->fullName()}}</div>
                        <div class="w-fit ps-2"><a href="{{route('admin.tps.edit',['region' => $region, 'tp' => $tp])}}">edit</a></div>
                    @endforeach
                </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .btn {
            border: 1px solid #ccc;
            width: 300px;
            padding: 3px;
            margin: 3px;
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
