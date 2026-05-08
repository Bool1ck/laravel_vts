@extends('app.layouts.main')

@section('content')
    <div>
        @php
        if(Auth::user()->isCanEditRegion($region)) {
            $disabled = "";
            $me_disabled = "disabled";
        } else {
            $disabled = "disabled";
            $me_disabled = "";
        }
        @endphp
        <div class="">
            <form action="{{ route('connection_point.update', ['region' => $region, 'cp' => $cp]) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="flex flex-col tbl">
                    <div>
                        <label>Дані замовника</label>
                        <div>
                            <div class="flex flex-row">
                                <div class="form_title">Замовник</div>
                                <div><input type="text" name="customer" value="{{ $cp->customer }}" {{$disabled}}></div>
                            </div>
                            <div class="flex flex-row">
                                <div class="form_title">Технічні умови</div>
                                <div><input type="text" name="technical_conditions"
                                            value="{{ $cp->technical_conditions }}" {{$disabled}}>
                                </div>
                            </div>
                            <div class="flex flex-row">
                                <div class="form_title">Дата ТУ</div>
                                <div><input type="date" name="technical_conditions_date"
                                            value="{{ $cp->technical_conditions_date }}" {{$disabled}}></div>
                            </div>
                            <div class="flex flex-row">
                                <div class="form_title">Потужність(кВт)</div>
                                <div><input type="number" name="power" value="{{ $cp->power }}" {{$disabled}}>
                                </div>
                            </div>
                            <div class="flex flex-row">
                                <div class="form_title">Тип замовника</div>
                                <div>
                                    <fieldset class="flex flex-row">
                                        @foreach($customerTypes as $customerType)
                                            <div>
                                                <input type="radio" id="customer_type_id" name="customer_type_id"
                                                       value="{{$customerType->id}}" {{$cp->customerType->id == $customerType->id ? "checked":""}}  {{$disabled}}>
                                                <label for="customer_type_id">{{$customerType->name}}</label>
                                            </div>
                                        @endforeach
                                    </fieldset>
                                </div>
                            </div>
                            <div class="flex flex-row">
                                <div class="form_title">Дата договору</div>
                                <div>
                                    <input type="date" name="contract_date" value="{{ $cp->contract_date }}"
                                           @if(empty($cp->contract_date))
                                               style="background-color: #FFBDC1"
                                        @endif  {{$disabled}}>
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
                                    <input type="date" name="payment_date" value="{{ $cp->payment_date }}"
                                           @if(empty($cp->payment_date))
                                               style="background-color: #FFBDC1"
                                        @endif  {{$disabled}}>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="flex flex-row">
                                <div class="form_title">Виконати до</div>
                                <div>
                                    <input type="date" value="{{ $cp->perform_by_date }}"
                                           @if(empty($cp->perform_by_date))
                                               style="background-color: #FFBDC1"
                                           @endif disabled>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="flex flex-row">
                                <div class="form_title">Заплановано<br>на дату</div>
                                <div>
                                    <input type="date" value="{{ $cp->planning_date }}"
                                           @if(empty($cp->planning_date))
                                               style="background-color: #FFBDC1"
                                           @endif  {{$me_disabled}}>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="flex flex-row">
                                <div class="form_title">Виконано</div>
                                <div>
                                    <input type="date" name="performance_date" value="{{ $cp->performance_date }}"
                                           @if(empty($cp->performance_date))
                                               style="background-color: #FFBDC1"
                                        @endif  {{$disabled}}>
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
                                    <input type="date" name="materials_order_date"
                                           value="{{ $cp->materials_order_date }}"
                                           @if(empty($cp->materials_order_date))
                                               style="background-color: #FFBDC1"
                                        @endif {{$disabled}}>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="flex flex-row">
                                <div class="form_title">Отримано</div>
                                <div>
                                    <input type="date" name="materials_receipt_date"
                                           value="{{ $cp->materials_receipt_date }}"
                                           @if(empty($cp->materials_receipt_date))
                                               style="background-color: #FFBDC1"
                                        @endif  {{$disabled}}>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label>Місце знаходження об'єкту</label>
                        <div class="flex flex-col">
                            <div class="flex flex-row">
                                <div>
                                    <input style="width: 520px" name="point_place" type="text"
                                           value="{{ $cp->point_place }}"  {{$disabled}}>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label>Точка забезпечення потужності</label>
                        <div class="flex flex-col">
                            <div class="flex flex-row">
                                <div>
                                    <input style="width: 520px" name="power_point" type="text"
                                           value="{{ $cp->power_point }}"  {{$disabled}}>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label>Перелік робіт</label>
                        <div class="flex flex-col ms-4">
                            @foreach($workTypes as $workType)
                                <div>
                                    <input type="checkbox" name="workTypes[]" value="{{$workType->id}}"
                                           @foreach($cpWorkTypes as $cpWorkType)
                                               @if($cpWorkType->workType->id == $workType->id)
                                                   checked
                                        @endif
                                        @endforeach
                                        {{$disabled}}
                                    >
                                    <label for="04">{{$workType->name}}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label>Note</label>
                        <div class="flex flex-col">
                            <div><textarea name="note" {{$disabled}}>{{$cp->note}}</textarea></div>
                        </div>
                    </div>
                    @if(Auth::user()->isCanEditRegion($region) | Auth::user()->isMainEngineerInRegion($region))
                        <div>
                            <div class="p-2">
                                <input type="submit" value="Зберігти зміни" class="btn"></div>
                        </div>
                </div>
                @endif
            </form>
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
            /*border: 1px solid #AAA;*/
        }

        div.tbl > div {
            background-color: #FFFBD5;
            margin-top: 5px;
            padding-left: 5px;
            min-height: 30px;
            align-items: center;
        }

        div.tbl > div > div > div {
            background-color: #FFFBD5;
            border-top: 1px solid #AAA;
            padding-top: 2px;
        }

        input {
            width: 400px;
            background-color: #DDD;
            padding-left: 5px;
            border: 1px solid #CCC;
            margin-right: 5px;
        }

        input[type="submit"] {
            width: 130px;
        }

        input[type="radio"] {
            width: 10px;
            margin-left: 5px;
        }

        input[type="checkbox"] {
            width: 10px;
            /*margin-left: 5px;*/
        }

        textarea {
            background-color: #DDD;
            padding-left: 5px;
            width: 520px;
            min-height: 100px;
            border: 1px solid #FFFBD5;
        }

    </style>
@endpush
