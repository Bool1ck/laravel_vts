@extends('app.layouts.main')

@section('content')
    <div class="flex flex-col w-fit">
        <div>
            <div class="p-1 bg-blue-400 text-center font-bold border-b-1 p-2">Редагування ТП</div>
        </div>
        <form class="p-0 m-0" action="{{ route('admin.tps.update',['region' => $region, 'tp' => $tp]) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="table w-full border-collapse bg-white m-0">
                <div class="table-row-group border-b">
                    <div class="table-cell p-1">Населений пункт</div>
                    <div class="table-cell p-1">
                        <select id="city_id" name="city_id">
                            @foreach($cities as $city)
                                <option value="{{$city->id}}"
                                        @if(old('city_id'))
                                            @if(old('city_id') == $city->id)
                                                selected
                                        @endif
                                        @else
                                            @if($city->id == $tp->city_id)
                                                selected
                                        @endif
                                        @endif
                                >{{$city->fullName()}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="table-row-group border-b">
                    <div class="table-cell p-1">Тип ТП</div>
                    <div class="table-cell p-1">
                        <select id="tp_type_id" name="tp_type_id">
                            @foreach($TpTypes as $TpType)
                                <option value="{{$TpType->id}}"
                                        @if(old('tp_type_id'))
                                            @if(old('tp_type_id') == $TpType->id)
                                                selected
                                        @endif
                                        @else
                                            @if($TpType->id == $tp->tp_type_id)
                                                selected
                                        @endif
                                        @endif
                                >{{$TpType->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="table-row-group border-b">
                    <div class="table-cell p-1">Назва ТП</div>
                    <div class="table-cell p-1">
                        <input type="text" name="name" placeholder="TpName" value="@if(old('name')){{old('name')}}@else{{$tp->name}}@endif">
                    </div>
                </div>
                <div class="table-row-group border-b">
                    <div class="table-cell p-1">
                        <input type="submit" value="Оновити" class="btn btn-create">
                    </div>
                </div>
            </div>
        </form>
        <div class="bg-white">
            <form class="p-1 m-0" action="{{ route('admin.tps.destroy', ['region' => $region, 'tp' => $tp]) }}" method="POST">
                @csrf
                @method('DELETE')
                <input type="text" name="tp" value="{{$tp->id}}" hidden>
                <input type="submit" value="Видалити" class="btn btn-danger">
            </form>
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
        select {
            /*height: 30px;*/
            padding-left: 5px;
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

        .btn-create {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 3px !important;
            background-color: #cfc !important;
        }

        .btn-create:hover {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 3px !important;
            background-color: #3f3 !important;
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
    </style>
@endpush
