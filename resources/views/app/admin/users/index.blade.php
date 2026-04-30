@extends('app.layouts.main')

@section('content')
    <div class="flex flex-col">
        <div><a href="{{ route('admin.users.create',['region' => $region]) }}">Додати нового користувача</a></div>
        <div>
            <table style="padding: 5px">
                <tr>
                    <th style="padding: 5px">Name</th>
                    <th style="padding: 5px">E-mail</th>
                    <th style="padding: 5px">Role</th>
                    <th style="padding: 5px">Action</th>
                </tr>
                @foreach($region->users as $user)
                    <tr>
                        <td style="padding: 5px">{{$user->name}}</td>
                        <td style="padding: 5px">{{$user->email}}</td>
                        <td style="padding: 5px">{{$user->roleInRegion($region)->name}}</td>
                        <td style="padding: 5px">edit</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
