@extends('app.layouts.main')

@section('content')
    <div>
        @foreach(\Illuminate\Support\Facades\Auth::user()->regions as $region)
            <div>Region: {{ $region->name }}, role : {{ $region->userRole()->name }},
                points: {{$region->allConnectionPoints->count()}}
            </div>
        @endforeach
    </div>
    <div>
    </div>
@endsection
