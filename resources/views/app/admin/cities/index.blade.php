@extends('app.layouts.main')

@section('content')
    <div class="flex flex-col">
        <div>
            <a href="{{ route('admin.cities.create',['region' => $region]) }}"><div class="btn">Додати нове місто</div></a>
        </div>
        <div>
            <table>
{{--                <tr>--}}
{{--                    <th colspan="3" class="text-center">Населені пункти</th>--}}
{{--                </tr>--}}
                <tr>
                    <th>Населені пункти</th>
                    <th>Кількість кулиць</th>
                    <th>Action</th>
                </tr>
                @foreach($cities as $city)
                    <tr>
                        <td>{{$city->fullName()}}</td>
                        <td>{{$city->streets()->count()}}</td>
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
        table {
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        th {
            border: 1px solid #ccc;
            text-align: center;
            padding: 3px;
        }

        td {
            text-align: left;
            padding: 3px;
            border: 1px solid #ccc;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        th {
            background-color: #38479E;
            color: white;
        }


        div.tbl > div {
            background-color: #FFF;
            padding: 5px;
            min-height: 30px;
            align-items: center;
        }



    </style>
@endpush
