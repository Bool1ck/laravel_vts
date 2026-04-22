@extends('app.layouts.main')

@section('content')
    <div>
        @foreach(\Illuminate\Support\Facades\Auth::user()->regionRoles as $regionrole)
            <div>Region: {{ $regionrole->region->name }}, role : {{ $regionrole->role->name }},
                points: {{$regionrole->region->allConnectionPoints->count()}}
            </div>
        @endforeach
    </div>
    <div>
    </div>
@endsection
