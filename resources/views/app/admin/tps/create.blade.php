@extends('app.layouts.main')

@section('content')
    <div class="flex flex-col w-fit">
        <div>
            <div class="p-1 bg-blue-400 text-center font-bold border-b-1 p-2">Новe ТП</div>
        </div>
        <form action="{{ route('admin.tps.store', ['region' => $region]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="table w-full border-collapse bg-white">
                <div class="table-row-group border-b">
                    <div class="table-cell p-1">Населений пункт</div>
                    <div class="table-cell p-1">
                        <select id="city_id" name="city_id">
                            @if(isset($city))
                                <option value="{{$city->id}}" selected>{{$city->fullName()}}</option>
                            @else
                                @foreach($region->cities as $city)
                                    <option value="{{$city->id}}"
                                            @if(old('city_id') == $city->id)
                                                selected
                                        @endif
                                    >{{$city->fullName()}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="table-row-group border-b">
                    <div class="table-cell p-1">Тип ТП</div>
                    <div class="table-cell p-1">
                        <select id="tp_type_id" name="tp_type_id">
                            @foreach($TpTypes as $TpType)
                                <option value="{{$TpType->id}}"
                                        @if(old('tp_type_id') == $TpType->id)
                                            selected
                                    @endif
                                >{{$TpType->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="table-row-group border-b">
                    <div class="table-cell p-1">Назва ТП</div>
                    <div class="table-cell p-1">
                        <input type="text" name="name" placeholder="TpName" value="{{old('name')}}">
                    </div>
                </div>
                <div class="table-row-group border-b">
                    <div class="table-cell p-1">
                        <input type="submit" value="Створити" class="btn-create">
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
        </form>
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
    </style>
@endpush
