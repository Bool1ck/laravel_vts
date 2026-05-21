@extends('app.layouts.main')

@section('content')
    <div class="flex flex-col">
        <div><a href="{{ route('admin.users.create',['region' => $region]) }}">
                <div class="btn">Додати нового користувача</div>
            </a></div>
        <div>
            <table style="padding: 5px">
                <tr>
                    <th style="padding: 5px">Name</th>
                    <th style="padding: 5px">E-mail</th>
                    <th style="padding: 5px">Role</th>
                    <th style="padding: 5px">Action</th>
                </tr>
                @foreach($users as $user)
                    <tr>
                        <td style="padding: 5px">{{$user->name}}</td>
                        <td style="padding: 5px">
                            @if(!$user->isAdminInRegion($region))
                                {{$user->email}}
                            @else
                                **********
                            @endif
                        </td>
                        <td style="padding: 5px">{{$user->roleInRegion($region)->name}}</td>
                        <td style="padding: 5px; text-align: center;">
                            @if(!$user->isAdminInRegion($region))
                                <a href="{{route('admin.users.edit',['region' =>$region, 'user' => $user])}}">edit</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
{{--            {{ $users->links() }}--}}
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .btn {
            border: 1px solid #ccc;
            width: 300px;
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

        table {
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        th {
            border: 1px solid #ccc;
            text-align: center;
            padding: 3px;
        }

        td {
            text-align: left;
            padding: 3px;
            border: 1px solid #ccc;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        th {
            background-color: #38479E;
            color: white;
        }


        div.tbl > div {
            background-color: #FFF;
            padding: 5px;
            min-height: 30px;
            align-items: center;
        }


    </style>
@endpush
