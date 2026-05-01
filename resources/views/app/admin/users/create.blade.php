@extends('app.layouts.main')

@section('content')
    <div class="flex flex-col w-fit">
        <div class="bg-blue-400 text-center font-bold border-b-1 p-2">Новий користувач</div>
        <div>
            <form action="{{route('admin.users.store',['region' => $region])}}" method="POST">
                @csrf
                @method('PUT')
                <div class="table w-full border-collapse bg-white">
                    <div class="table-header-group">
                        <div class="table-row">
                            <div class="table-cell p-1 text-left">Відділ</div>
                            <div class="table-cell p-1 text-left"><select id="role_id" name="role_id">
                                    @foreach($roles as $role)
                                        <option value="{{$role->id}}"
                                                @if(old('role_id') == $role->id)
                                                    selected
                                            @endif
                                        >{{$role->name}}</option>
                                    @endforeach
                                </select></div>
                        </div>
                    </div>
                    <div class="table-row-group">
                        <div class="table-row border-b">
                            <div class="table-cell p-1">ПІБ</div>
                            <div class="table-cell p-1"><input type="text" name="name" placeholder="ПІБ" value="{{old('name')}}"></div>
                        </div>
                    </div>
                    <div class="table-row-group">
                        <div class="table-row border-b">
                            <div class="table-cell p-1">email</div>
                            <div class="table-cell p-1"><input type="email" name="email" placeholder="email" value="{{old('email')}}"></div>
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
            margin: 3px;
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

        select {
            height: 30px;
            padding-left: 5px;
            background-color: #FDFFC4;
            border: 1px solid #3498db;
            cursor: pointer;
        }

        input[type=text], [type=email] {
            height: 30px;
            background-color: #E1EEFF;
            padding-left: 5px;
            width: 300px;
            border: 1px solid #CCC;
            margin-right: 5px;
        }
    </style>
@endpush
