@extends('app.layouts.main')

@section('content')
    <div class="flex">
        <form action="{{route('admin.users.store',['region' => $region])}}" method="POST">
            @csrf
            @method('PUT')
            <div class="flex flex-col tbl">
                <div>
                    <div class="text-center">Новий користувач</div>
                </div>
                <div class="flex flex-row">
                    <div class="p-1">
                        <select id="role_id" name="role_id">
                            @foreach($roles as $role)
                                <option value="{{$role->id}}">{{$role->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <input type="text" name="name" placeholder="ПІБ">
                    </div>
                    <div>
                        <input type="email" name="email" placeholder="email">
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

        input[type=text],[type=email] {
            height: 30px;
            background-color: #E1EEFF;
            padding-left: 5px;
            width: 300px;
            border: 1px solid #CCC;
            margin-right: 5px;
        }
    </style>
@endpush
