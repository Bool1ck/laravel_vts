@extends('app.layouts.main')

@section('content')
    <div class="flex">
        <div class="flex flex-col">
            <div>
                <div class="text-center bg-blue-400 p-2">Редагування користувача</div>
            </div>
            <div class="flex flex-row mb-0 bg-gray-500">
                <form class="flex p-0 m-0" action="{{route('admin.users.update',['region' => $region, 'user' => $user])}}"
                      method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="table w-full border-collapse bg-white p-0 m-0">
                        <div class="table-header-group">
                            <div class="table-row">
                                <div class="table-cell p-1 text-left">Відділ</div>
                                <div class="table-cell p-1 text-left"><select id="role_id" name="role_id">
                                        @foreach($roles as $role)
                                            <option value="{{$role->id}}"
                                                    @if((old('role_id') == $role->id)|($role->id == $user->roleInRegion($region)->id))
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
                                <div class="table-cell p-1"><input type="text" name="name" placeholder="ПІБ"
                                                                   value="{{$user->name}}"></div>
                            </div>
                        </div>
                        <div class="table-row-group">
                            <div class="table-row border-b">
                                <div class="table-cell p-1">email</div>
                                <div class="table-cell p-1"><input style="background-color: #ccc;" type="email"
                                                                   name="email" placeholder="email"
                                                                   value="{{$user->email}}" disabled></div>
                            </div>
                        </div>
                        <div class="table-row-group">
                            <div class="table-row border-b">
                                <div class="table-cell p-1"><input type="submit" value="Оновити" class="btn"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="p-1 bg-white">
                <form action="{{route('admin.users.destroy', ['region' => $region, 'user' => $user])}}"
                      method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" value="{{$user->id}}">
                    <input type="submit" value="Видалити користувача" class="btn btn-danger">
                </form>
            </div>
            <div>
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
