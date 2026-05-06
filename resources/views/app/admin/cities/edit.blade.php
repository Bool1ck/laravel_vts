@extends('app.layouts.main')

@section('content')
    <div class="flex flex-col w-fit">
        <div class="bg-blue-400 text-center font-bold border-b-1 p-2">Редагування населеного пункту</div>
        <div class="bg-white">
            <form class="flex" action="{{route('admin.cities.update',['region' => $region, 'city' => $city])}}" method="POST">
                @csrf
                @method('PATCH')
                <div class="table w-full border-collapse m-0">
                    <div class="table-row-group">
                        <div class="table-row">
                            <div class="table-cell p-1 text-left">Тип</div>
                            <div class="table-cell p-1 text-left">
                                <select id="city_type_id" name="city_type_id">
                                    @foreach($cityTypes as $cityType)
                                        <option value="{{$cityType->id}}"
                                                @if(old('city_type_id'))
                                                    @if(old('city_type_id') == $cityType->id)
                                                        selected
                                                @endif
                                                @else
                                                    @if($cityType->id == $city->city_type_id)
                                                        selected
                                                @endif
                                                @endif
                                        >{{$cityType->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="table-row-group">
                        <div class="table-row border-b">
                            <div class="table-cell p-1">Назва</div>
                            <div class="table-cell p-1">
                                <input type="text" name="name" placeholder="CityName"
                                       @if(old('name'))
                                           value="{{old('name')}}"
                                       @else
                                           value="{{$city->name}}"
                                    @endif></div>
                        </div>
                    </div>
                    <div class="table-row-group">
                        <div class="table-row border-b">
                            <div class="table-cell p-1"><input type="submit" value="Оновити" class="btn"></div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="p-1">
                @can('delete', $city)
                    <form action="{{route('admin.cities.destroy', ['region' => $region, 'city' => $city])}}"
                          method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" value="{{$city->id}}">
                        <input type="submit" value="Видалити населений пункт" class="btn btn-danger">
                    </form>
                @endcan
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
            width: 60px;
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
