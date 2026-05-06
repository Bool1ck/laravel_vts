@extends('app.layouts.main')

@section('content')
    <div class="flex flex-col w-fit bg-white">
        <div class="">
            <div class="p-1 bg-blue-400 text-center font-bold border-b-1 p-2">Додати нову вулицю</div>
        </div>
        <form action="{{ route('admin.streets.store', ['region' => $region]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="table border-collapse p-0 m-0">
                <div class="table-row-group border-b-1">
                    <div class="table-cell p-1">Населенний пункт</div>
                    <div class="table-cell p-1">
                        <select id="city_id" name="city_id">
                            @foreach($region->cities as $city)
                                <option value="{{$city->id}}"
                                        @if(old('city_id') == $city->id)
                                            selected
                                    @endif
                                >{{$city->fullName()}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="table-row-group border-b-1">
                    <div class="table-cell p-1">Тип вулиці</div>
                    <div class="table-cell p-1">
                        <select id="street_type_id" name="street_type_id">
                            @foreach($streetTypes as $streetType)
                                <option value="{{$streetType->id}}"
                                        @if(old('street_type_id') == $streetType->id)
                                            selected
                                    @endif
                                >{{$streetType->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="table-row-group border-b-1">
                    <div class="table-cell p-1">Назва вулиці</div>
                    <div class="table-cell p-1"><input type="text" name="name" placeholder="StreetName" value="{{old('name')}}"></div>
                </div>
                <div class="table-row-group border-b-1">
                    <div class="table-cell p-1"><input type="submit" value="Створити"></div>
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
        input[type=submit] {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 3px;
            padding-left: 5px;
            padding-right: 5px;
            background-color: #cfc;
        }

        input[type=submit]:hover {
            border: 1px solid #ccc;
            padding: 3px;
            padding-left: 5px;
            padding-right: 5px;
            background-color: #3f3;
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
