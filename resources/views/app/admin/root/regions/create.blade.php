@extends('app.layouts.main')

@section('content')
    <div class="flex flex-col w-fit">
        <div>
            <div class="p-1 bg-blue-400 text-center font-bold border-b-1 p-2">Новий регіон</div>
        </div>
        <div>
            <form action="{{route('root.regions.store')}}" method="POST">
                @csrf
                @method('PUT')
                <div class="table w-full border-collapse bg-white">
                    <div class="table-row-group">
                        <div class="table-row border-b">
                            <div class="table-cell p-1">Назва</div>
                            <div class="table-cell p-1"><input type="text" name="name" placeholder="RegionName" value="{{old('name')}}"></div>
                        </div>
                    </div>
                    <div class="table-row-group">
                        <div class="table-row border-b">
                            <div class="table-cell p-1"><input type="submit" value="Створити" class="btn"></div>
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
            /*height: 30px;*/
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
