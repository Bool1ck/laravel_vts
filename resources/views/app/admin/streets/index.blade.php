@extends('app.layouts.main')

@section('content')
    <div class="flex flex-col">
        <div><a href="{{ route('admin.cities.create',['region' => $region]) }}">Додати нове місто</a></div>
        <div class=""></div>
        <div>
            <table>
                <tr>
                    <th colspan="2" class="text-center">Cities</th>
                </tr>
                <tr>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
                @foreach($cities as $city)
                    <tr>
                        <td>{{$city->cityType->name}}{{$city->name}}</td>
                        <td><a href="{{route('admin.cities.edit',['region' => $region, 'city' => $city->id])}}">edit</a></td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div>
            {{ $cities->links() }}
        </div>
    </div>
@endsection
