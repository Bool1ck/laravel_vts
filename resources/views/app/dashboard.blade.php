@extends('app.layouts.main')

@section('content')
    <div>
        @foreach(\Illuminate\Support\Facades\Auth::user()->regions as $reg)
            <div>РЕМ: {{ $reg->name }}, права :
                {{ \Illuminate\Support\Facades\Auth::user()->roleInRegion($reg)->name }},
                точок приєднання: {{$reg->allConnectionPoints->count()}}
            </div>
        @endforeach
    </div>
@endsection
