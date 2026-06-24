@extends('app.layouts.main')

@section('content')
    <div>
        @if(Auth::user()->isSuperAdmin())
            SuperAdmin
        @else
            @foreach(Auth::user()->regions as $regionItem)
                <div>РЕМ: {{ $regionItem->name }}, права :
                    {{ Auth::user()->roleInRegion($regionItem)->name }},
                    точок приєднання: {{$regionItem->allConnectionPoints->count()}}
                </div>
            @endforeach
        @endif

    </div>
@endsection
