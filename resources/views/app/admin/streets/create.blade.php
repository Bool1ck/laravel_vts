@extends('app.layouts.main')

@section('content')
    <div class="flex">
        <form action="{{ route('admin.streets.store', ['region' => $region]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="flex flex-col tbl">
                <div>
                    <div class="text-center">Нова вулиця</div>
                </div>
                <div class="flex flex-row">
                    <div class="p-1">
                        <select id="city_id" name="city_id">
                            @foreach($region->cities as $city)
                                <option value="{{$city->id}}">{{$city->fullName()}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="p-1">
                        <select id="street_type_id" name="street_type_id">
                            @foreach($streetTypes as $streetType)
                                <option value="{{$streetType->id}}">{{$streetType->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <input type="text" name="name" placeholder="StreetName">
                    </div>
                    <div>
                        <input type="submit" value="Створити" class="btn">
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
