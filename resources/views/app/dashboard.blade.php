@extends('app.layouts.main')

@section('content')
    <div>
        @foreach(\Illuminate\Support\Facades\Auth::user()->regions as $reg)
            <div>РЕМ: {{ $reg->name }}, права : {{ $reg->userRole()->name }},
                точок приєднання: {{$reg->allConnectionPoints->count()}}
            </div>
        @endforeach
    </div>
    <div>
    </div>
@endsection
