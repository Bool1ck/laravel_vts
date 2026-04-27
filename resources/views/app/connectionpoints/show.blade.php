@extends('app.layouts.main')

@section('content')
    <div>
        <div class="">
            <div class="flex flex-col tbl">
                <div>
                    <label>Дані замовника</label>
                    <div>
                        <div class="flex flex-row">
                            <div class="form_title">Замовник</div>
                            <div><input type="text" value="{{ $cp->customer }}" disabled></div>
                        </div>
                        <div class="flex flex-row">
                            <div class="form_title">Технічні умови</div>
                            <div><input type="text" value="{{ $cp->technical_conditions }}" disabled>
                            </div>
                        </div>
                        <div class="flex flex-row">
                            <div class="form_title">Дата ТУ</div>
                            <div><input type="date" value="{{ $cp->technical_conditions_date }}" disabled></div>
                        </div>

                        <div class="flex flex-row">
                            <div class="form_title">Потужність(кВт)</div>
                            <div><input type="number" value="{{ $cp->power }}" disabled>
                            </div>
                        </div>
                        <div class="flex flex-row">
                            <div class="form_title">Тип замовника</div>
                            <div>
                                <input type="text" value="{{ $cp->customerType->name }}" disabled>
                            </div>
                        </div>
                        <div class="flex flex-row">
                            <div class="form_title">Дата договору</div>
                            <div>
                                <input type="text" value="{{ $cp->contract_date }}" disabled
                                       @if(empty($cp->contract_date))
                                           style="background-color: #FFBDC1"
                                    @endif>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <label>Виконання</label>
                    <div>
                        <div class="flex flex-row">
                            <div class="form_title">Дата оплати</div>
                            <div>
                                <input type="text" value="{{ $cp->payment_date }}" disabled
                                       @if(empty($cp->payment_date))
                                           style="background-color: #FFBDC1"
                                    @endif>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="flex flex-row">
                            <div class="form_title">Виконати до</div>
                            <div>
                                <input type="text" value="{{ $cp->perform_by_date }}" disabled
                                       @if(empty($cp->perform_by_date))
                                           style="background-color: #FFBDC1"
                                    @endif>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="flex flex-row">
                            <div class="form_title">Виконано</div>
                            <div>
                                <input type="text" value="{{ $cp->performance_date }}" disabled
                                       @if(empty($cp->performance_date))
                                           style="background-color: #FFBDC1"
                                    @endif>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <label>Матеріали</label>
                    <div>
                        <div class="flex flex-row">
                            <div class="form_title">Замовлено</div>
                            <div>
                                <input type="text" value="{{ $cp->materials_order_date }}" disabled
                                       @if(empty($cp->materials_order_date))
                                           style="background-color: #FFBDC1"
                                    @endif>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="flex flex-row">
                            <div class="form_title">Отримано</div>
                            <div>
                                <input type="text" value="{{ $cp->materials_receipt_date }}" disabled
                                       @if(empty($cp->materials_receipt_date))
                                           style="background-color: #FFBDC1"
                                    @endif>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <label>Місце знаходження об'єкту</label>
                    <div class="flex flex-col">
                        <div class="flex flex-row"><div></div>
                            <div>
                                <input style="width: 520px" type="text" value="{{ $cp->point_place }}" disabled>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <label>Точка забезпечення потужності</label>
                    <div class="flex flex-col">
                        <div class="flex flex-row">
                            <div>
                                <input style="width: 520px" type="text" value="{{ $cp->power_point }}" disabled>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <label>Перелік робіт</label>
                    <div class="flex flex-col ms-4">
                        <div>
                            @foreach($cpWorkTypes as $cpWorkType)
                                <li>{{$cpWorkType->workType->name}}</li>
                            @endforeach
                        </div>

                    </div>
                </div>
                <div>
                    <label>Note</label>
                    <div class="flex flex-col">
                        <div><textarea disabled>{{$cp->note}}</textarea></div>
                    </div>
                </div>
                @if($region->IsUserCanEdit())
                    <div>
                            <div class="p-2"><a href="{{ route('connection_point.edit',['region' => $region->id, 'cp' => $cp->id]) }}" class="btn">Внести зміни</a></div>
                    </div>
                @endif
            </div>

        </div>
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
            min-width: 100px;
            border: 1px solid #ccc;
            padding: 3px;
            padding-left: 5px;
            padding-right: 5px;
            background-color: #FFFED3;
        }

        .btn:hover {
            border: 1px solid #ccc;
            padding: 3px;
            padding-left: 5px;
            padding-right: 5px;
            background-color: #FFFC4A;
        }

        div.tbl {
            /*border: 1px solid #AAA;*/
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

        input {
            width: 400px;
            background-color: #FFFBD5;
            padding-left: 5px;
            border: 1px solid #CCC;
            margin-right: 5px;
        }

        textarea {
            padding-left: 5px;
            width: 520px;
            min-height: 100px;
            border: 1px solid #CCC;
        }

    </style>
@endpush
