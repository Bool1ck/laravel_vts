@extends('connectionpoints.layouts.main')

@section('content')
    <div>
        @if($points)
            <table class="text-xs font-medium tracking-wider">
                <tr>
                    <td class="text-left">Технічні умови</td>
                    <td>Дата ТУ</td>
                    <td>Замовник</td>
                    <td>Місце знаходження об'єкту</td>
                    <td>Точка забезпечення потужності</td>
                    <td>Перелік робіт</td>
                    <td>Примітка</td>
                </tr>
                @foreach($points as $point)
                    <tr>
                        <td>{{$point->technical_conditions}}</td>
                        <td>{{$point->technical_conditions_date}}</td>
                        <td>{{$point->customer}}</td>
                        <td>{{$point->city->name}}</td>
                        <td>{{$point->street->name}}</td>
                        <td>{{$point->building_number}}</td>
                        <td>{{$point->note}}</td>
                    </tr>
                @endforeach
            </table>
        @else
            <div>Empty</div>
        @endif

    </div>
@endsection
