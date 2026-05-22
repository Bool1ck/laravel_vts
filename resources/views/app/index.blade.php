@extends('app.layouts.main')

@section('content')
    <div>
        <div class="flex flex-row">
            @if(Auth::user()->isCanEditRegion($region))
                <a class="btn" href="{{ route('connection_point.create', ['region' => $region]) }}">Нове приєднання</a>
            @endif
            @if($completed)
                    <a class="btn btn-completed"
                       href="{{route('connection_point.index', ['region' => $region, 'completed' => ""])}}">В роботі</a>
                @else
                    <a class="btn btn-completed"
                       href="{{route('connection_point.index', ['region' => $region, 'completed' => "completed"])}}">Завершені</a>
            @endif

        </div>
        @if($connectionPoints)
            <table class="m-2">
                <tr>
                    <th>Технічні<br>умови</th>
                    <th>Дата ТУ</th>
                    <th>Замовник</th>
                    <th>Тип<br>замовника</th>
                    <th>Місце знаходження об'єкту</th>
                    <th>Точка забезпечення потужності</th>
                    <th>Потужність</th>
                    <th>Перелік робіт</th>
                    <th>Дата<br>договору</th>
                    <th>Дата<br>оплати</th>
                    <th>Виконати до<br>включно</th>
                    <th>Заплановано<br>на дату</th>
                    <th>Виконано</th>
                    <th>замовлення<br>матеріалів</th>
                    <th>отримання<br>матеріалів</th>
                    <th>Операції</th>
                </tr>
                @foreach($connectionPoints as $point)
                    <tr>
                        <td>{{$point->technical_conditions}}</td>
                        <td>{{Date::parse($point->technical_conditions_date)->format('d.m.Y')}}</td>
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
                        <td>{{$point->contract_date ? Date::parse($point->contract_date)->format('d.m.Y'):""}}</td>
                        <td>{{$point->payment_date ? Date::parse($point->payment_date)->format('d.m.Y'):""}}</td>
                        <td>{{$point->perform_by_date ? Date::parse($point->perform_by_date)->format('d.m.Y'):""}}</td>
                        <td>{{$point->planning_date ? Date::parse($point->planning_date)->format('d.m.Y'):""}}</td>
                        <td>{{$point->performance_date ? Date::parse($point->performance_date)->format('d.m.Y'):""}}</td>
                        <td>{{$point->materials_order_date ? Date::parse($point->materials_order_date)->format('d.m.Y'):""}}</td>
                        <td>{{$point->materials_receipt_date ? Date::parse($point->materials_receipt_date)->format('d.m.Y'):""}}</td>
                        <td>
                            <a href="{{route('connection_point.show', ['region' => $point->region_id, 'cp' => $point->id])}}">Show</a>
                            @if((Auth::user()->isCanEditRegion($region)|Auth::user()->isMainEngineerInRegion($region))&&!$completed)
                                <a href="{{ route('connection_point.edit', ['region' => $point->region_id, 'cp' => $point->id]) }}">Edit</a>
                            @endif

                        </td>
                    </tr>
                @endforeach
                <tr style="background-color: #E5E7EB">
                    <td colspan="16" style="border: 0px">
                        <div class="flex flex-col">
                            {{ $connectionPoints->links('vendor.pagination.custom') }}
                        </div>
                    </td>
                </tr>
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

        .btn-completed {
            border: 1px solid #ccc;
            width: 300px;
            padding: 3px;
            margin: 10px;
            padding-left: 5px;
            padding-right: 5px;
            background-color: #FFFEE1;
        }

        .btn-completed:hover {
            border: 1px solid #ccc;
            padding: 3px;
            padding-left: 5px;
            padding-right: 5px;
            background-color: #FCF93A;
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
