@extends('app.layouts.main')

@section('content')
    <div class="w-fit">
        <div><a class="btn" href="{{ route('admin.tps.create',['region' => $region, 'city' => $city]) }}">Додати нове ТП</a></div>
        <div>
            <table class="m-2">
                <tr>
                    <td style="background-color: #38479E; color: #fff; font-size: 16px; font-weight: bold"
                        colspan="2">{{$city->fullName() . " ,кількість ТП: " . $city->tps()->count()}}</td>
                </tr>
                @foreach($city->tps as $tp)
                    <tr>
                        <td>{{$tp->fullName()}}</td>
                        <td>
                            <a href="{{route('admin.tps.edit',['region' => $region, 'tp' => $tp])}}">edit</a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .btn {
            border: 1px solid #ccc;
            width: 300px;
            padding: 3px;
            margin: 10px;
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
            min-width: 350px;
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

        td + td  {
            text-align: center;
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
    </style>
@endpush
