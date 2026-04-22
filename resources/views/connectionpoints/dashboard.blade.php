@extends('connectionpoints.layouts.main')

@section('content')
    <div>
        <p>
        {{--        {{ \Illuminate\Support\Facades\Auth::user()->role->name }}--}}
        {{--        {{ \Illuminate\Support\Facades\Auth::user()->regions }}--}}
        {{--        {{ \Illuminate\Support\Facades\Auth::user()->regionRoles }}--}}
        @foreach(\Illuminate\Support\Facades\Auth::user()->regionRoles as $regionrole)
            <div>Region: {{ $regionrole->region->name }}, role : {{ $regionrole->role->name }},
                points: {{$regionrole->region->connectionPoints->count()}}</div>
            @endforeach
            </p>
    </div>
@endsection
