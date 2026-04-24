@extends('app.layouts.main')

@section('content')
    <div>
        <div class="p-2">
            {{$region->name}} >>
        </div>
        <div>
            <hr>
        </div>
        <div class="m-2">
            <p>Show Connection Point</p>
{{--            <form action="#">--}}
{{--                @csrf--}}
{{--                @method('PUT')--}}
{{--                <div class="flex flex-col tbl">--}}
{{--                    <div>--}}
{{--                        <label>Дані замовника</label>--}}
{{--                        <div>--}}
{{--                            <div class="flex flex-row">--}}
{{--                                <div class="form_title">Технічні умови</div>--}}
{{--                                <div><input type="text" placeholder="Номер техних умов"></div>--}}
{{--                            </div>--}}
{{--                            <div class="flex flex-row">--}}
{{--                                <div class="form_title">Дата ТУ</div>--}}
{{--                                <div><input type="date"></div>--}}
{{--                            </div>--}}
{{--                            <div class="flex flex-row">--}}
{{--                                <div class="form_title">Замовник</div>--}}
{{--                                <div><input type="text" placeholder="ПІБ"></div>--}}
{{--                            </div>--}}
{{--                            <div class="flex flex-row">--}}
{{--                                <div class="form_title">Потужність :</div>--}}
{{--                                <div><input type="number" placeholder="кВт"></div>--}}
{{--                            </div>--}}
{{--                            <div class="flex flex-row">--}}
{{--                                <div class="form_title">Тип замовника</div>--}}
{{--                                <div>--}}
{{--                                    <fieldset class="flex flex-row">--}}
{{--                                        <div>--}}
{{--                                            <input type="radio" id="huey" name="drone" value="huey" checked/>--}}
{{--                                            <label for="huey">Побутовий</label>--}}
{{--                                        </div>--}}
{{--                                        <div>--}}
{{--                                            <input type="radio" id="dewey" name="drone" value="dewey"/>--}}
{{--                                            <label for="dewey">Юридичний</label>--}}
{{--                                        </div>--}}
{{--                                    </fieldset>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div>--}}
{{--                        <label>Місце знаходження об'єкту</label>--}}
{{--                        <div class="flex flex-col">--}}
{{--                            <div class="flex flex-row">--}}
{{--                                <div class="form_title">Місто/Село</div>--}}
{{--                                <div>--}}
{{--                                    <select>--}}
{{--                                        <option>Малин</option>--}}
{{--                                        <option>Малин2</option>--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="flex flex-row">--}}
{{--                                <div class="form_title">Вулиця</div>--}}
{{--                                <div>--}}
{{--                                    <select>--}}
{{--                                        <option>Грушевського</option>--}}
{{--                                        <option>Грушевського2</option>--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="flex flex-row">--}}
{{--                                <div class="form_title">Будинок</div>--}}
{{--                                <div><input type="number" placeholder="№"></div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div>--}}
{{--                        <label>Точка забезпечення потужності</label>--}}
{{--                        <div class="flex flex-col">--}}
{{--                            <div class="flex flex-row">--}}
{{--                                <div class="form_title">ПЛ :</div>--}}
{{--                                <div>--}}
{{--                                    <fieldset class="flex flex-row">--}}
{{--                                        <div>--}}
{{--                                            <input type="radio" id="04" name="pl" value="04" checked/>--}}
{{--                                            <label for="04">0,4</label>--}}
{{--                                        </div>--}}
{{--                                        <div>--}}
{{--                                            <input type="radio" id="10" name="pl" value="10"/>--}}
{{--                                            <label for="10">10</label>--}}
{{--                                        </div>--}}
{{--                                    </fieldset>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="flex flex-row">--}}
{{--                                <div class="form_title">ТП :</div>--}}
{{--                                <div><input type="number" placeholder="000"></div>--}}
{{--                            </div>--}}
{{--                            <div class="flex flex-row">--}}
{{--                                <div class="form_title">Лінія :</div>--}}
{{--                                <div><input type="text" placeholder="Л-"></div>--}}
{{--                            </div>--}}
{{--                            <div class="flex flex-row">--}}
{{--                                <div class="form_title">Опора №:</div>--}}
{{--                                <div><input type="number" placeholder="№"></div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                    </div>--}}

{{--                    <div>--}}
{{--                        <div class=>--}}
{{--                            <label>Перелік робіт</label>--}}
{{--                        </div>--}}
{{--                        <div class="flex">--}}
{{--                            <fieldset class="flex flex-col ms-5">--}}
{{--                                <div>--}}
{{--                                    <input type="checkbox" id="subscribe" name="newsletter" value="yes">--}}
{{--                                    <label for="04">11111</label>--}}
{{--                                </div>--}}
{{--                                <div>--}}
{{--                                    <input type="checkbox" id="subscribe" name="newsletter" value="yes">--}}
{{--                                    <label for="10">22222</label>--}}
{{--                                </div>--}}
{{--                                <div>--}}
{{--                                    <input type="checkbox" id="subscribe" name="newsletter" value="yes">--}}
{{--                                    <label for="10">33333</label>--}}
{{--                                </div>--}}
{{--                                <div>--}}
{{--                                    <input type="checkbox" id="subscribe" name="newsletter" value="yes">--}}
{{--                                    <label for="10">444444</label>--}}
{{--                                </div>--}}
{{--                                <div>--}}
{{--                                    <input type="checkbox" id="subscribe" name="newsletter" value="yes">--}}
{{--                                    <label for="10">555555</label>--}}
{{--                                </div>--}}
{{--                            </fieldset>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="flex flex-row">--}}
{{--                        <div class="form_title">Note</div>--}}
{{--                        <div><textarea placeholder="..."></textarea></div>--}}
{{--                    </div>--}}
{{--                    <div class="flex flex-row">--}}
{{--                        <div class="m-1"><input type="button" value="Додати" class="btn"></div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </form>--}}
        </div>
    </div>
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
