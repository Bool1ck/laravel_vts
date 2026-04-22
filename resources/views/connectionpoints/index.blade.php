@extends('connectionpoints.layouts.main')

@section('content')
    <div>
        <div class="p-2">
            {{$region->name}} >> <a href="#">Нове приєднання</a>
        </div>
        <div>
            <hr>
        </div>
        @if($points)
            <table class="text-xs font-medium tracking-wider">
                <tr>
                    <td class="text-left">Технічні умови</td>
                    <td>Дата ТУ</td>
                    <td>Замовник</td>
                    <td>Місце знаходження об'єкту</td>
                    <td>Точка забезпечення потужності</td>
                    <td>Потужність</td>
                    <td>Перелік робіт</td>
                    <td>Примітка</td>
                </tr>
                @foreach($points as $point)
                    <tr>
                        <td>{{$point->technical_conditions}}</td>
                        <td>{{$point->technical_conditions_date}}</td>
                        <td>{{$point->customer}}</td>
                        <td>{{$point->point_place}}</td>
                        <td>{{$point->power_point}}</td>
                        <td>{{$point->power}} кВт</td>
                        <td>{{$point->note}}</td>
                        <td>{{$point->note}}</td>
                    </tr>
                @endforeach
            </table>
        @else
            <div>Empty</div>
        @endif

    </div>
@endsection
