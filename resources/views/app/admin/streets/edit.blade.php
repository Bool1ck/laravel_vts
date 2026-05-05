@extends('app.layouts.main')

@section('content')
    <div class="flex w-fit bg-white">
        <div class="flex flex-col">
            <div>
                <div class="p-1 bg-blue-400 text-center font-bold border-b-1 p-2">Редагування street</div>
            </div>
                <div class="table border-collapse p-0 m-0">
                    <form action="{{ route('admin.streets.update',['region' => $region, 'street' => $street]) }}" method="POST">
                        @csrf
                        @method('PATCH')
                    <div class="table-row-group border-b-1">
                        <div class="table-cell p-1">
                            Населенний пункт
                        </div>
                        <div class="table-cell p-1">
                            <select id="city_id" name="city_id">
                                @foreach($region->cities as $city)
                                    <option value="{{$city->id}}"
                                            @if($city->id == $street->city_id)
                                                selected
                                        @endif
                                    >{{$city->fullName()}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="table-row-group border-b-1">
                        <div class="table-cell p-1">
                            Тип вулиці
                        </div>
                        <div class="table-cell p-1">
                            <select id="street_type_id" name="street_type_id">
                                @foreach($streetTypes as $streetType)
                                    <option value="{{$streetType->id}}"
                                            @if($streetType->id == $street->street_type_id)
                                                selected
                                        @endif
                                    >{{$streetType->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="table-row-group border-b-1">
                        <div class="table-cell p-1">
                            Назва вулиці
                        </div>
                        <div class="table-cell p-1">
                            <input type="text" name="name" placeholder="StreetName" value="{{$street->name}}">
                        </div>
                    </div>
                    <div class="table-row-group border-b-1">
                        <div class="table-cell p-1">
                            <input type="submit" value="Оновити" class="btn btn-submit">
                        </div>
                    </div>
                    </form>
                    <div class="table-row-group">
                        <div class="table-cell p-0">
                            <form class="p-1 m-0" action="{{ route('admin.streets.destroy', ['region' => $region, 'street' => $street]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="text" name="street" value="{{$street->id}}" hidden>
                                <input type="submit" value="Видалити" class="btn btn-danger">
                            </form>
                        </div>
                    </div>
                </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .btn-submit {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 3px;
            padding-left: 5px;
            padding-right: 5px;
            background-color: #cfc;
        }

        .btn-submit:hover {
            border: 1px solid #ccc;
            padding: 3px;
            padding-left: 5px;
            padding-right: 5px;
            background-color: #3f3;
        }

        .btn-danger {
            border: 1px solid #ccc;
            padding: 3px;
            padding-left: 5px;
            padding-right: 5px;
            background-color: #D01B1B;
        }

        .btn-danger:hover {
            border: 1px solid #ccc;
            padding: 3px;
            padding-left: 5px;
            padding-right: 5px;
            background-color: #FF0000;
            color: #FFF;
        }

        select {
            background-color: #FDFFC4 !important;
            border: 1px solid #3498db;
            cursor: pointer;
        }

        input[type=text] {
            height: 30px;
            background-color: #E1EEFF;
            padding-left: 5px;
            width: 400px;
            border: 1px solid #CCC;
            margin-right: 5px;
        }
    </style>
@endpush
