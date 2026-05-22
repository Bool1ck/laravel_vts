@extends('app.layouts.main')

@section('content')
    <div>
        @foreach(Auth::user()->regions as $region)
            <div>РЕМ: {{ $region->name }}, права :
                {{ Auth::user()->roleInRegion($region)->name }},
                точок приєднання: {{$region->allConnectionPoints->count()}}
            </div>
        @endforeach
    </div>
@endsection
