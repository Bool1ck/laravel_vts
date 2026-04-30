@extends('app.layouts.main')

@section('content')
    <div class="flex">
        <div class="flex flex-col tbl">
            <div>
                <div class="text-center">Редагування street</div>
            </div>
            <form action="{{ route('admin.streets.update',['region' => $region, 'street' => $street]) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="flex flex-col tbl">
                    <div class="flex flex-row">
                        <div class="p-1">
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
                        <div class="p-1">
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
                        <div>
                            <input type="text" name="name" placeholder="StreetName" value="{{$street->name}}">
                        </div>
                        <div>
                            <input type="submit" value="Оновити" class="btn">
                        </div>
            </form>
                        <form action="{{ route('admin.streets.destroy', ['region' => $region, 'street' => $street]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="text" name="street" value="{{$street->id}}" hidden>
                            <input type="submit" value="Видалити" class="ms-1 btn btn-danger">
                        </form>
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
        .btn {
            border: 1px solid #ccc;
            padding: 3px;
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


        div.tbl > div {
            background-color: #FFF;
            padding: 5px;
            min-height: 30px;
            align-items: center;
        }


        select {
            height: 30px;
            padding-left: 5px;
            background-color: #FDFFC4;
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
