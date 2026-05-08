@extends('app.layouts.main')

@section('content')
    <div>
        <div class="m-2">
            <form action="{{ route('connection_point.store', ['region' => $region]) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="region_id" value="{{$region->id}}">
                <div class="flex flex-col tbl">
                    <div>
                        <label>Дані замовника</label>
                        <div>
                            <div class="flex flex-row">
                                <div class="form_title">Замовник</div>
                                <div><input type="text" name="customer" placeholder="ПІБ" value="{{ old('customer') }}">
                                </div>
                            </div>
                            <div class="flex flex-row">
                                <div class="form_title">Технічні умови</div>
                                <div><input type="text" name="technical_conditions"
                                            value="{{ old('technical_conditions') }}" placeholder="Номер техних умов">
                                </div>
                            </div>
                            <div class="flex flex-row">
                                <div class="form_title">Дата ТУ</div>
                                <div><input type="date" name="technical_conditions_date"
                                            value="{{ old('technical_conditions_date') }}"></div>
                            </div>
                            <div class="flex flex-row">
                                <div class="form_title">Потужність :</div>
                                <div><input type="number" name="power" placeholder="кВт" value="{{ old('power') }}">
                                </div>
                            </div>
                            <div class="flex flex-row">
                                <div class="form_title">Тип замовника</div>
                                <div>
                                    <fieldset class="flex flex-row">
                                        <?php
                                        $checked = "checked";
                                        ?>
                                        @foreach($customerTypes as $customerType)
                                            @if(old('customer_type_id'))
                                                <div>
                                                    <input type="radio" id="customer_type_id" name="customer_type_id"
                                                           value="{{$customerType->id}}" {{old('customer_type_id') == $customerType->id ? "checked":""}}/>
                                                    <label for="customer_type_id">{{$customerType->name}}</label>
                                                </div>
                                            @else
                                                <div>
                                                    <input type="radio" id="customer_type_id" name="customer_type_id"
                                                           value="{{$customerType->id}}" {{$checked}}/>
                                                    <label for="customer_type_id">{{$customerType->name}}</label>
                                                </div>
                                                    <?php
                                                    $checked = "";
                                                    ?>
                                            @endif
                                        @endforeach
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label>Місце знаходження об'єкту</label>
                        <div class="flex flex-col">
                            <div class="flex flex-row">
                                <div class="form_title">Місто/Село</div>
                                <div>
                                    <select name="city_id">
                                        <option value="0" disabled selected hidden></option>
                                        @foreach($cities as $city)
                                            <option
                                                {{old('city')==$city->id?"selected":""}} value="{{$city->id}}">{{$city->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="flex flex-row">
                                <div class="form_title">Вулиця</div>
                                <div>
                                    <select name="street_id">
                                        <option value="0" disabled selected hidden></option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex flex-row">
                                <div class="form_title">Будинок</div>
                                <div><input type="text" name="build_number" value="{{ old('build_number') }}"
                                            placeholder="№"></div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label>Точка забезпечення потужності</label>
                        <div class="flex flex-col">
                            <div class="flex flex-row">
                                <div class="form_title">ПЛ :</div>
                                <div>
                                    <fieldset class="flex flex-row">
                                        <?php
                                        $checked = "checked";
                                        ?>
                                        @foreach($powerLineTypes as $powerLineType)
                                            <div>
                                                <input type="radio" id="powerLineType" name="powerLineType"
                                                       value="{{$powerLineType->name}}"
                                                @if(old('powerLineType'))
                                                    {{old('powerLineType') == $powerLineType->name ? "checked":""}}
                                                    @else
                                                    {{$checked}}
                                                    @endif
                                                />
                                                <label for="powerLineType">{{$powerLineType->name}}</label>
                                            </div>
                                                <?php
                                                $checked = "";
                                                ?>
                                        @endforeach
                                    </fieldset>
                                </div>
                            </div>
                            <div class="flex flex-row">
                                <div class="form_title">ТП :</div>
                                <div><input type="number" name="tp" value="{{ old('tp') }}" placeholder="000"></div>
                            </div>
                            <div class="flex flex-row">
                                <div class="form_title">Лінія :</div>
                                <div><input type="text" name="power_line" value="{{ old('power_line') }}"
                                            placeholder="Л-"></div>
                            </div>
                            <div class="flex flex-row">
                                <div class="form_title">Опора №:</div>
                                <div><input type="number" name="pole" value="{{ old('pole') }}" placeholder="№"></div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class=>
                            <label>Перелік робіт</label>
                        </div>
                        <div class="flex">
                            <fieldset class="flex flex-col ms-5">
                                @foreach($workTypes as $workType)
                                    <div>
                                        <input type="checkbox" name="workTypes[]" value="{{$workType->id}}"
                                               @if(old('workTypes') !== null)
                                                   @foreach(old('workTypes') as $owt)
                                                       @if($owt == $workType->id)
                                                           checked
                                            @endif
                                            @endforeach
                                            @endif

                                        >
                                        <label for="04">{{$workType->name}}</label>
                                    </div>
                                @endforeach
                            </fieldset>
                        </div>
                    </div>
                    <div class="flex flex-row">
                        <div class="form_title">Note</div>
                        <div><textarea placeholder="..." name="note">{{old('note')}}</textarea></div>
                    </div>
                    <div class="flex flex-row">
                        <div class="m-1"><input type="submit" value="Додати" class="btn"></div>
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
            </form>
        </div>
    </div>
    <script type="module">
        $(document).ready(function() {
            $('select[name=city_id]').on('change', function() {
                $.ajax({
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{route('api.v1.city-streets', [ 'region' => 2, 'city' => 103])}}',
                    type: "GET", // Specifies the request method
                    data: {
                        _token: "{{ csrf_token() }}",
                    }, // Parameters sent in the URL
                    success: function(response) {
                        alert('click');
                        console.log("Success:", response);
                    },
                    error: function(xhr) {
                        alert('error click');
                        console.log("Error:", xhr.responseText);
                    }
                });
            });
        });
    </script>
@endsection

@push('styles')
    <style>
        div.tbl > div > label {
            font-weight: bold;
            margin-left: 20px;
        }

        .form_title {
            min-width: 120px;
            padding: 2px;
        }

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

        div.tbl {
            border: 1px solid #AAA;
        }

        div.tbl > div {
            background-color: #FFF;
            margin-top: 5px;
            padding-left: 5px;
            min-height: 30px;
            align-items: center;
        }

        div.tbl > div > div > div {
            background-color: #fff;
            border-top: 1px solid #AAA;
            padding-top: 2px;
        }

        select {
            padding-left: 5px;
            width: 400px;
            /*appearance: none; !* Removes default arrow *!*/
            background-color: #FDFFC4;
            border: 1px solid #3498db;
            /*background-image: url('arrow.svg'); !* Add custom arrow *!*/
            background-repeat: no-repeat;
            background-position: right 15px center;
            cursor: pointer;
        }

        input {
            background-color: #E1EEFF;
            padding-left: 5px;
        }

        input[type="text"] {
            width: 400px;
            border: 1px solid #CCC;
            margin-right: 5px;
        }

        input[type="number"] {
            width: 400px;
            border: 1px solid #CCC;
            margin-right: 5px;
        }

        input[type="radio"] {
            width: 10px;
            margin-left: 5px;
        }

        input[type="checkbox"] {
            width: 10px;
            /*margin-left: 5px;*/
        }

        input[type="date"] {
            border: 1px solid #CCC;
        }

        textarea {
            padding-left: 5px;
            width: 400px;
            border: 1px solid #CCC;
        }

    </style>
@endpush
