@extends('app.layouts.main')

@section('content')
    <div class="flex flex-col">
        <div>
            <a href="{{route('root.regions.create')}}">
                <div class="btn">Додати новий регіон</div>
            </a>
        </div>
        <div>
            <table>
                <tr>
                    <th>Регіон</th>
                    <th>Action</th>
                </tr>
                @foreach($regions as $regionItem)
                    <tr>
                        <td>{{$regionItem->name}}</td>
                        <td>
                            <div><a href="{{route('root.regions.edit', $regionItem->id)}}">edit</a></div>
                            <div>@can('delete', $regionItem)
                                    <form action="{{ route('root.regions.destroy', $regionItem) }}" method="POST" onsubmit="return confirm('Ви впевнені, що хочете перенести цей регіон в архів?');">
                                        @csrf
                                        @method('DELETE') <!-- Примусово підміняємо метод на DELETE для ядра Laravel -->

                                        <button type="submit" class="text-red-600 hover:text-red-900 bg-transparent border-none p-0 cursor-pointer font-medium">
                                            Видалити
                                        </button>
                                    </form>
                                @endcan</div>
                        </td>
                    </tr>
                @endforeach
                <tr class="no-hover">
                    <td colspan="4" style="border: 0px;">
                        <div class="pagination-links  flex flex-col">
{{--                            {{ $regions->links('vendor.pagination.custom') }}--}}
                        </div>
                    </td>
                </tr>
            </table>

        </div>

    </div>
@endsection

@push('styles')
    <style>
        .no-hover:hover {
            background-color: #E5E7EB;
        }

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
            min-width: 500px;
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

        div.pagination-links > span {
            background-color: #4b0600;
        }

    </style>
@endpush
