@extends('app.layouts.main')

@section('content')
    <div>
        @if(Auth::user()->isCanEditRegion($region))
            <a class="btn" href="{{ route('connection_point.create', ['region' => $region]) }}">Нове приєднання</a>
        @endif
        @if($connectionPoints)
            <table class="m-2">
                <tr>
                    <th>Технічні умови</th>
                    <th>Дата ТУ</th>
                    <th>Замовник</th>
                    <th>Тип замовника</th>
                    <th>Місце знаходження об'єкту</th>
                    <th>Точка забезпечення потужності</th>
                    <th>Потужність</th>
                    <th>Перелік робіт</th>
                    <th>Дата договору</th>
                    <th>Дата оплати</th>
                    <th>Виконати до<br>включно</th>
                    <th>Виконано</th>
                    <th>замовлення<br>матеріалів</th>
                    <th>отримання<br>матеріалів</th>
                    <th>Операції</th>
                </tr>
                @foreach($connectionPoints as $point)
                    <tr>
                        <td>{{$point->technical_conditions}}</td>
                        <td>{{\Illuminate\Support\Facades\Date::parse($point->technical_conditions_date)->format('d.m.Y')}}</td>
                        <td>{{$point->customer}}</td>
                        <td>{{$point->customerType->name}}</td>
                        <td>{{$point->point_place}}</td>
                        <td>{{$point->power_point}}</td>
                        <td>{{$point->power}} кВт</td>
                        <td>
                            @foreach($point->workTypes as $cpwt)
                                <li class="ms-3">{{$cpwt->name}}</li>
                            @endforeach
                        </td>
                        <td>{{$point->contract_date ? \Illuminate\Support\Facades\Date::parse($point->contract_date)->format('d.m.Y'):""}}</td>
                        <td>{{$point->payment_date ? \Illuminate\Support\Facades\Date::parse($point->payment_date)->format('d.m.Y'):""}}</td>
                        <td>{{$point->perform_by_date ? \Illuminate\Support\Facades\Date::parse($point->perform_by_date)->format('d.m.Y'):""}}</td>
                        <td>{{$point->performance_date ? \Illuminate\Support\Facades\Date::parse($point->performance_date)->format('d.m.Y'):""}}</td>
                        <td>{{$point->materials_order_date ? \Illuminate\Support\Facades\Date::parse($point->materials_order_date)->format('d.m.Y'):""}}</td>
                        <td>{{$point->materials_receipt_date ? \Illuminate\Support\Facades\Date::parse($point->materials_receipt_date)->format('d.m.Y'):""}}</td>
                        <td>
                            <a href="{{route('connection_point.show', ['region' => $point->region_id, 'cp' => $point->id])}}">Show</a>
                            @if(Auth::user()->isCanEditRegion($region))
                                <a href="{{ route('connection_point.edit', ['region' => $point->region_id, 'cp' => $point->id]) }}">Edit</a>
                            @endif

                        </td>
                    </tr>
                @endforeach
            </table>
        @else
            <div>Empty</div>
        @endif

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
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            font-size: 10px;
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
    </style>
@endpush
