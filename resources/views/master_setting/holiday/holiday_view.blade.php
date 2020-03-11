@extends('admin.master')
<!--Page Title-->
@section('page-title')
    Holiday 
@endsection

<!--Page Header-->
@section('page-header')
    Holiday 
@endsection

<!--Page Content Start Here-->
@section('page-content')

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                    <div class="main_part">
                        <br>
                        <div class="row" id="app1">
                            <div class="col-md-12">
                                <div class="col-md-10 col-md-offset-1">
                                    @if(Session::has('message'))
                                        <div class="alert {{Session::get('status')}}">
                                            <span>{{Session::get('message')}}</span>
                                        </div>
                                    @endif
                                </div>
                                <!-- Code Here.... -->
                                <div class="change_passport_body pull-left" style="margin:0px;">
                                    <p class="form_title_center">
                                        <i>Weekly Holiday</i>
                                    </p>
                                    <form action="{{ URL::to('setting/holiday/store') }}" method="POST">
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <label for="status"><i>SELECT DAY:</i></label>
                                            <select class="form-control selectpicker" multiple data-live-search="true" name="day[]"  required>
                                                <option value=""></option>
                                                <option value="Monday">Monday</option>
                                                <option value="Tuesday">Tuesday</option>
                                                <option value="Wednesday">Wednesday</option>
                                                <option value="Thursday">Thursday</option>
                                                <option value="Friday">Friday</option>
                                                <option value="Saturday">Saturday</option>
                                                <option value="Sunday">Sunday</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="form_date"><i>FORM DATE:</i></label>
                                            <input type="text" value="<?php echo date('d-m-Y'); ?>" class="form-control datepicker" name="from_date" data-date-format="dd/mm/yyyy" required autocomplete="off">
                                            <span id="status_response" style="font-size: 12px;float: right;"></span>
                                        </div>
                                        <div class="form-group">
                                            <label for="to_date"><i>TO DATE:</i></label>
                                            <input type="text" value="<?php echo date('d-m-Y'); ?>" class="form-control datepicker" name="to_date" data-date-format="dd/mm/yyyy" required autocomplete="off">
                                        </div>
                                        <hr>
                                        <div class="footer-box">
                                            <button type="submit" id="submit" class="btn btn-info pull-right">SUBMIT</button>
                                        </div>
                                    </form>
                                </div>
                                                    <!-- Code Here.... -->
                                <div class="change_passport_body  pull-right" style="margin:0px">
                                    <p class="form_title_center">
                                        <i>Date Wise Holiday</i>
                                    </p>
                                    <form action="{{ URL::to('setting/holiday/date/store') }}" method="POST">
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <label for="from_date"><i>SELECT DATE:</i></label>
                                            <input type="text" value="<?php echo date('d-m-Y'); ?>" class="form-control datepicker" name="from_date" data-date-format="dd/mm/yyyy" required autocomplete="off">
                                        </div>
                                        <label><i>TO DATE:</i></label> <input type="checkbox" id="checkmulholi" @click="multyHolidayCheck">
                                        
                                        <div v-show="mul_holiday" class="form-group">
                                            <input id="todate_mul_value" type="text" value="<?php echo date('d-m-Y'); ?>" class="form-control datepicker" name="to_date" data-date-format="dd/mm/yyyy" required autocomplete="off">
                                        </div>
                                        <div class="form-group">
                                            <label for="from_date"><i>Description:</i></label>
                                            <input type="text" value="" class="form-control " name="description" required>
                                        </div>
                                        <div class="footer-box">
                                            <button type="submit" id="submit" class="btn btn-info pull-right">SUBMIT</button>
                                        </div>
                                    </form>
                                </div>
                                
                            </div>
                            <br>

                            <div class="col-md-12">
                                <div class="container">
                                    <h3 class="text-center">Holiday</h3>
                                    <div class="holiday-data-area">
                                        @if(@$allHoliday)
                                        <table class="table table-responsive">
                                            <thead>
                                                <th>SL</th>
                                                <th>Holiday Date</th>
                                                <th>Description</th>
                                                <th>Action</th>
                                            </thead>
                                            <tbody>
                                                @foreach($allHoliday as $item)
                                                    <tr>
                                                        <td>{{$loop->iteration}}</td>
                                                        <td>{{$item->date}}</td>
                                                        <td>{{$item->description}}</td>
                                                        <td><a onclick="return confirm('Are you sure! You want to Delete?')" href="{{URL::to('setting/holiday/delete').'/'.$item->hday_id}}" class="btn btn-danger btn-sm">Delete</a></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </section>
    
    <link rel="stylesheet" href="{{asset('public/assets/css/bootstrap-select.css')}}" />
    <script src="{{asset('public/assets/js/bootstrap-select.js')}}"></script>
    <script>

        var app = new Vue({
            el:'#app1',
            data:{
                mul_holiday:false,
            },
            methods:{
                multyHolidayCheck:function(){
                    var checkbox = document.getElementById('checkmulholi');
                    
                    if(checkbox.checked == true){
                        this.mul_holiday = true;
                        var todateValue = document.getElementById('todate_mul_value');
                        todateValue.disabled = false;
                    }
                    else{
                        var todateValue = document.getElementById('todate_mul_value');
                        todateValue.disabled = true;
                        this.mul_holiday = false;
                    }
                }
            }
        });
    </script>

@endsection
