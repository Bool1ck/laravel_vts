@extends('app.layouts.main')

@section('content')
    <div class="flex">
        <div class="flex flex-col tbl">
            <div>
                <div class="text-center">Редагування користувача</div>
            </div>
            <div class="flex flex-row">
                <form class="flex" action="{{route('admin.users.update',['region' => $region, 'user' => $user])}}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="flex flex-col tbl">
                        <div class="flex flex-row">
                            <div class="p-1">
                                <select id="role_id" name="role_id">
                                    @foreach($roles as $role)
                                        <option value="{{$role->id}}"
                                        @if($role->id == $user->roleInRegion($region)->id)
                                            selected
                                        @endif
                                        >{{$role->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <input type="text" name="name" placeholder="ПІБ" value="{{$user->name}}">
                            </div>
                            <div>
                                <input type="email" name="email" placeholder="email" value="{{$user->email}}" disabled>
                            </div>
                            <div>
                                <input type="submit" value="Оновити" class="btn">
                            </div>
                        </div>
                    </div>
                </form>
                <div class="ps-3">
{{--                    @can('delete', $city)--}}
                        <form action="{{route('admin.users.destroy', ['region' => $region, 'user' => $user])}}"
                              method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{$user->id}}">
                            <input type="submit" value="Видалити користувача" class="btn btn-danger">
                        </form>
{{--                    @endcan--}}
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

        input[type=text],[type=email] {
            height: 30px;
            background-color: #E1EEFF;
            padding-left: 5px;
            width: 200px;
            border: 1px solid #CCC;
            margin-right: 5px;
        }
    </style>
@endpush
