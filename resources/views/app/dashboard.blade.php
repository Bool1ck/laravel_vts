@extends('app.layouts.main')

@section('content')
    <div>
        @foreach(\Illuminate\Support\Facades\Auth::user()->regions as $reg)
            <div>Region: {{ $reg->name }}, role : {{ $reg->userRole()->name }},
                points: {{$reg->allConnectionPoints->count()}}
            </div>
        @endforeach
    </div>
    <div>
    </div>
@endsection
