@extends('admin.master')
<!--Page Title-->
@section('page-title')
    Counter
@endsection

<!--Page Header-->
@section('page-header')
    <style type="text/css">
        .passport-label{
            -webkit-user-select:none;
            -moz-user-select:none;
            user-select:none;
        }
        .alert-item:focus{
            border:1px solid red;
        }
    </style>
    @if(isset($counter_no) && $counter_no > 0)
        <div id="page-header">
            Counter: <span class="bold_text">{{$counter_no}}</span> &nbsp;&nbsp;&nbsp; Floor No: <span class="bold_text">{{$floor_id}}</span> &nbsp;&nbsp;&nbsp; User ID: <span class="bold_text" >{{$user_id}}</span> &nbsp;&nbsp;&nbsp; Center: <span class="bold_text" >{{@$center_name->center_name}}</span> &nbsp;&nbsp;&nbsp; Service Fee: <span class="bold_text" >{{$ivac_svc_fee->Svc_Fee}}Tk</span> <span class="time float-right ">Time: <span class="bold_text">@{{ time }}</span></span></p>

        <!-- <p class="date">@{{ date }}</p> -->

        </div>
        <script>
            var clock = new Vue({
                el: '#page-header',
                data: {
                    time: '',
                    date: ''
                }
            });
            var week = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
            var timerID = setInterval(updateTime, 1000);
            updateTime();
            function updateTime() {
                var cd = new Date();
                clock.time = zeroPadding(cd.getHours(), 2) + ':' + zeroPadding(cd.getMinutes(), 2) + ':' + zeroPadding(cd.getSeconds(), 2);
                clock.date = zeroPadding(cd.getFullYear(), 4) + '-' + zeroPadding(cd.getMonth()+1, 2) + '-' + zeroPadding(cd.getDate(), 2) + ' ' + week[cd.getDay()];
            };

            function zeroPadding(num, digit) {
                var zero = '';
                for(var i = 0; i < digit; i++) {
                    zero += '0';
                }
                return (zero + num).slice(-digit);
            }
        </script>
    @else
        <h1 class="text-center">Opps! This Counter Is Not Registered</h1>
    @endif

@endsection

<!--Page Content Start Here-->
@section('page-content')
    @if(isset($counter_no) && $counter_no > 0)
    <div id="app1">
        <section class="content ">
            <div class="row">
                <div class="col-md-12">
                    <div class="main_part countercall-area" >
                        <br>
                        @if(Session::has('message'))
                            <div class="row">
                                <div class="col-md-4 col-md-offset-4 alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</div>
                            </div>
                    @endif
                    <!-- Code Here.... -->

                        <div class="row">
                            <div class="col-md-2">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="service_type" style="font-weight:normal" >Service Type</label>
                                        <select @change="svcNameFunc($event)" name="service_type" id="service_type" class="form-control input_values">
                                            <option value=""></option>
                                            @foreach($counter_services as $service)
                                                <option value="{{$service}}">{{$service}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input name="counter_id" id="counter_id" type="hidden" value="{{$counter_no}}"  class="input_values">
                                    <input name="center_name" id="center_name" type="hidden" value="{{$center_name->center_name}}"  class="input_values">
                                    <input name="user_id" id="user_id" type="hidden" value="{{$user_id}}"  class="input_values">
                                    <input name="curSvcFee" id="curSvcFee" type="hidden" value="{{$ivac_svc_fee->Svc_Fee}}"  class="input_values">
                                    <input name="floor_id" id="floor_id" type="hidden" value="{{$floor_id}}" class="input_values">
                                    <input name="selected_token" id="selected_token" type="hidden" :value="selectedTokenval" class="input_values">
                                    <input name="selected_token_qty" id="selected_token_qty" type="hidden" :value="selectedTokenQty" class="input_values">
                                </div>
                                <div class="single-datadisplaybox-left">
                                    <div @click="regularAreaCLickFunc" class="single-datadisplaybox">
                                        <p class="datadisplaybox-header regular-header">Current Q</p>
                                        <div class="regular-area datadisplaybox-regular">
                                            <li v-for="regular in regularDataList">@{{ regular.token_number }}</li>
                                        </div>
                                    </div>
                                </div>

                                <div  class="single-datadisplaybox-right">
                                    <div class="single-datadisplaybox">
                                        <button @click="sendToWaitingFunc"  class="btn btn-info">To Wait</button>
                                        <p class="datadisplaybox-header waiting-header">Waiting Q</p>
                                        <div id="waiting_list" class="waiting-area datadisplaybox-waiting">
                                            <li v-for="waiting in waitingDataList"><a @click="waitingItemClick">@{{ waiting.token_number }}</a></li>

                                        </div>
                                    </div>
                                    <div  class="single-datadisplaybox">
                                        <input @keyup.enter="sendToRecallFunc" style="width:70px" type="text" id="send_recall_id" placeholder="Press Enter">
                                        <p class="datadisplaybox-header recall-header">Recall Q</p>
                                        <div class="recall-area datadisplaybox-recall">
                                            <li v-for="recall in recallDataList"><a @click="recallItemClick">@{{ recall.token_number }}</a></li>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-10" >
                                <div class="calltoken-area-center">
                                    <div v-show="webfilePreloader" class="webfile-preloader"><img class="preloader" src="{{asset("public/assets/img/preloader.gif")}}" alt=""></div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="calltoken-right-content">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div style=" margin-bottom:30px;"><span  style="width:200px;display:inline-block;color:#000;font-size:25px;font-weight:300">Token No: &nbsp;<b id="selectedTokenDisplay">@{{selectedTokenval}}</b></span> &nbsp;&nbsp;&nbsp; <span style="font-size:14px;">Qty: <b >@{{selectedTokenQty}}</b></span> </div>
                                                        <div class="calltoken-sticker-area">
                                                            <table>
                                                                <tr>
                                                                    <td>Sticker Type: </td>
                                                                    <td style="width:20px">
                                                                        <select name="sticker_type" id="sticker_type" class="input_values" style="width:100px;height:26px" required>
                                                                            <option value=""> </option>
                                                                            @foreach($stickers as $sticker)
                                                                                <option value="{{$sticker->sticker}}">{{$sticker->sticker}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td><input v-model="stkr_str" id="sticker_no_from" name="sticker_no_from" type="number" style="width:65px" class="input_values" required autocomplete="off"> </td>
                                                                    <td> To: </td>
                                                                    <td><input v-model="stkr_end"  id="sticker_no_to" name="sticker_no_to" type="number"  style="width:65px" class="input_values" required autocomplete="off"> </td>
                                                                    
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="calltoken-area-right">
                                                            <span style=""><button class="btn btn-primary tdd-text"  data-toggle="modal" data-target="#tddModal">TDD</button> &nbsp;&nbsp;&nbsp; <span class="total-save">Total Save: <b id="total_save_count">0</b></span></span>
                                                            <br><span style="margin-left:91px;" class="total-rejected total-save ">Rejected: <b id="total_reject_count">0</b></span>
                                                            <!-- Modal -->
                                                            <div  class="modal fade" id="tddModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="exampleModalLabel" style="display:inline-block;font-weight:bold">TDD LIST</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div  class="reject-cause-area">
                                                                                <table class="table table-responsive  table-hover">
                                                                                    <thead>
                                                                                        <th>Serial</th>
                                                                                        <th>Tentative Delivery Date</th>
                                                                                        <th>Symbol</th>
                                                                                        <th>Visa Type</th>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        @foreach($tdd_list as $tdd)
                                                                                            <tr>
                                                                                                <td><span>{{$loop->iteration}}</span></td>
                                                                                                <td><span>{{date('Y-m-d',strtotime($tdd->tdd))}}</span><input id="{{'tdd_'.$tdd->visa_type}}" type="hidden" value="{{date('Y-m-d',strtotime($tdd->tdd))}}"></td>
                                                                                                <td><span>{{$tdd->symbol}}</span></td>
                                                                                                <td><span>{{$tdd->visa_type}}</span></td>
                                                                                            </tr>
                                                                                        @endforeach
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <br>
                                                        <table>
                                                            <tr>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <input class="form-control input_values" 
                                                                        value="Book no-<?php if (isset($book_no->book_no) && !empty($book_no->book_no)) {
                                                                                echo $book_no->book_no;
                                                                            } ?>" name="book_no"
                                                                            placeholder="Book No" autocomplete="off" style="width:100px;height:30px" disabled>
                                                                        <input type="hidden" class="form-control input_values"
                                                                            value="<?php if (isset($book_no->book_no) && !empty($book_no->book_no)) {
                                                                                echo $book_no->book_no;
                                                                            } ?>" name="book_no" id="book_no"
                                                                            placeholder="Book No" autocomplete="off">
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <input class="form-control input_values" name="book_rcvpt_no" id="book_rcvpt_no" placeholder="Receipt No" style="width:100px;height:30px" autocomplete="off" required>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div  v-for="(mainitem, i) in addMoreButtonArr" class="col-md-12">
                                                        <br>
                                                        
                                                        <br>
                                                        <div  class="webfile-bottom-area">
                                                            <div class="webfile-success-area">
                                                                
                                                                <div class="foreign_data_area">                                                                    
                                                                    <div class="row">
                                                                        <div class="col-md-1" style="padding-right: 0px">
                                                                            <h4>GRATIS:</h4>
                                                                        </div>
                                                                        <div class="col-md-1" style="padding-right: 0px;padding-left: 0px">
                                                                            <div class="checkbox">
                                                                                <label>
                                                                                    <input type="radio" :name='i+"gratis_status1[]"' :id="'gratiseYes'+i" class="input_values" value="yes" required> Yes
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-1" style="padding-left: 0px;">
                                                                            <div class="checkbox">
                                                                                <label>
                                                                                    <input type="radio" :name='i+"gratis_status1[]"' :id="'gratiseNo'+i" class="input_values" value="no"> No
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                       
                                                                        <div class="col-md-2">
                                                                            <div class="form-group">
                                                                                <input @keypress="checkvalidStickerNoFunc" type="number" name="validStkr[]" :id="'validStkr'+i" :data-id="i" class="form-control input_values doubleStkr" placeholder="Sticker No." required   autocomplete="off">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                {{-- <input class="form-control" id="web_file_no" name="web_file_no" placeholder="Web file number" autocomplete="off" required> --}}
                                                                            
                                                                                <span :id="'webfile_p'+i">
                                                                                    <label for=""></label>
                                                                                    <p ><input @keyup.enter="webfileSubmit" name="webfile[]" :id="'webfile'+i" :data-id="i" class="form-control input_values" placeholder="Enter Webfile" required autocomplete="off"></p>
                                                                                    {{-- <p v-show="passportSearch">Passport: <input  name="PassportNo2" id="PassportNo2" style="width:200px" required autocomplete="off" class="input_values"></p> --}}
                                                                                </span>
                                                                                <span :id="'webfile2_p'+i" style="display: none" >
                                                                                    <p><label :for="'webfileNo2'+i">Webfile</label><input @keyup.enter="webfileSubmit2"  name="webfile2" :id="'webfileNo2'+i" :data-id="i" style="background:#ffff87"  class="form-control input_values " required autocomplete="off"></p>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label for=""></label>
                                                                                <input class="form-control input_values" :id="'name'+i" :data-id="i" name="name[]" placeholder="Name of Applicant" autocomplete="off" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label for=""></label>
                                                                                <input class="form-control input_values" name="contact[]" :id="'contact'+i" :data-id="i" pattern=".{10}" title="Minimum  & Maximum 10 digit" placeholder="Contact (1XXXXXXXXX)"
                                                                                    autocomplete="off" class="input_values" required>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label :id="'passport_show'+i" class="passport-label"></label>
                                                                                <input class="form-control passport input_values" name="passportNo[]" placeholder="Passport" :id="'passportNo'+i" :data-id="i" autocomplete="off" required>
                                                                            </div>

                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label for=""></label>
                                                                                <input class="form-control input_values" name="nationality[]" :id="'nationality'+i" :data-id="i" placeholder="Nationality" autocomplete="off" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label for=""><span :id="'tddDelDateId'+i"  style="height:10px;display: inline-block;"></span> </label>
                                                                                <input name="tddDelDateValue[]" :id="'tddDelDateValue'+i" type="hidden" class="input_values">
                                                                                {{-- @{{i}} --}}
                                                                                <select class="form-control input_values visa_type_value" @change="visaTypeOnChange" name="visa_type[]" :id="'visa_type'+i" :data-id="i" required>
                                                                                    <option value="">Visa Type</option>
                                                                                    <?php foreach ($visa_type as $item) { ?>
                                                                                    <option value="<?php  echo $item->visa_type; ?>"><?php  echo $item->visa_type; ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </div>

                                                                        </div>
                                        
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <select class="form-control input_values" name="duration[]" :id="'duration'+i" :data-id="i" required>
                                                                                    <option value="">Select Duration</option>
                                                                                    <?php foreach ($duration as $item) { ?>
                                                                                    <option value="<?php  echo $item->duration; ?>"><?php  echo $item->duration; ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <select class="form-control input_values" name="entry_type[]" :id="'entry_type'+i" :data-id="i" required>
                                                                                    <option value="">Entry Type</option>
                                                                                    <?php foreach ($entry_type as $item) { ?>
                                                                                    <option value="<?php  echo $item->entry_type; ?>"><?php  echo $item->entry_type; ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <input class="form-control input_values datepicker" value="<?php echo date('d-m-Y'); ?>" name="date_of_checking[]" :id="'check_date'+i" :data-id="i" placeholder="Date of Checking" required>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div id="show">
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                <div class="input-group">
                                                                                <input @keyup="TotalFeeFunc"   type="text" class="form-control input_values" name="visa_fee[]" :id="'visaFee'+i" :data-id="i" placeholder="Visa Fee" required autocomplete="off">
                                                                                    <span class="input-group-addon">Taka</span>
                                                                                </div>
                                                                                {{--<div class="form-group">--}}
                                                                                {{--<input class="form-control" name="visa_fee" id="visaFee"--}}
                                                                                {{--placeholder="Visa Fee" required>--}}
                                                                                {{--</div>--}}
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="input-group">
                                                                                    <input @keyup="TotalFeeFunc"   class="form-control input_values" name="fax_trans_charge[]" :id="'faxCharge'+i" :data-id="i" placeholder="Fax Trans. Charge" required>
                                                                                    <span class="input-group-addon">Taka</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="input-group">
                                                                                    <input @keyup="TotalFeeFunc"  class="form-control input_values" name="icwf[]" :id="'icwf'+i" :data-id="i" placeholder="ICWF"
                                                                                        required>
                                                                                    <span class="input-group-addon">Taka</span>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                        <br>
                                                                        
                                                                    <div class="row ">
                                                                        <div class="col-md-8">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <div class="input-group">
                                                                                        <input @keyup="TotalFeeFunc" class="form-control input_values" name="visa_app_charge[]" :id="'appCharge'+i" :data-id="i" placeholder="Visa App. Charge">
                                                                                        <span class="input-group-addon">Taka</span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <div class="form-group">
                                                                                        <input class="form-control"  :id="'total_fee_disable'+i" :data-id="i"  placeholder="Total Amount" disabled>
                                                                                        <input type="hidden" class="form-control input_values" :id="'total_fee'+i" name="total_amount[]" >
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <div class="form-group">
                                                                                        <input @keyup="oldPassQtyFunc" required class="form-control input_values" name="old_pass[]" :id="'old_pass'+i" :data-id="i" placeholder="Old Passport Qty">
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                            </div>
                                                                            <div class="row">
                                                                                
                                                                                <div class="col-md-3">
                                                                                    <div class="form-group">
                                                                                        <label :for="'paytype'+i"></label>
                                                                                        <select name="paytype[]" :id="'paytype'+i" :data-id="i" class="form-control input_values" >
                                                                                            <option>Pay Type</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <div class="form-group">
                                                                                        <label :for="'proc_fee'+i" :id="'proc_fee_res'+i"></label>
                                                                                        <input type="hidden" :id="'proc_fee_res_value'+i" name="proc_fee[]" class="input_values">
                                                                                        <input :id="'sp_fee'+i" :data-id="i" name="sp_fee[]" class="form-control input_values" type="text" value=""  autocomplete="off">
                                                                                        
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <div  class="form-group" >
                                                                                        <label for="">Corr Fee</label>
                                                                                        <input class="form-control" :id="'cor_item_fee_value'+i" style="display: none;" type="button" value="{{$getCorFee}}">
                                                                                        <input class="form-control" :id="'cor_item_fee_value2'+i"  type="button" value="0" disabled>
                                                                                        <input class="form-control input_values" name="corr_fee[]"  type="hidden" value="{{$getCorFee}}">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <div class="input-group">
                                                                                        <div :id="'rejected_button_show'+i" class="reject_button-here" style="display:none">
                                                                                            <button @click="rejectBtnFunc" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal"  style="margin-left:20px;">Reject</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <br>
                                                                                <br>
                                                                                
                                                                                    <div v-show="webfileData" class="reject-area">
                                                                                       
                                                                                        <!-- Modal -->
                                                                                        <div v-show="rejectModalShow" class="custom-modal-area">
                                                                                            <div class="custom-modal">
                                                                                                <div class="custom-modal-header">
                                                                                                    <p>SELECT REJECT CAUSES</p>
                                                                                                </div>
                                                                                                <div class="custom-modal-body">
                                                                                                    <div  class="reject-cause-area">
                                                                                                        <p><input id="reject-select-all" @click="selectRejectAllFunc" v-model="selectRejectAll" type="checkbox"> <label for="reject-select-all">SELECT ALL</label></p>
                                                                                                        <div class="reject-box">
                                                                                                            <p v-for="item in rejectCauseData"><input :id="item.Sl"  type="checkbox" v-model="rejectItem" :value="item.reason"> <label :for="item.Sl">@{{ item.reason }}</label></p>
                                                                                                        </div>
                                                                                                    </div>
        
                                                                                                </div>
                                                                                                <div class="custom-modal-footer">
                                                                                                    <button @click="rejectSubmitFunc" :id="'rejectsubmit'+i" :data-id="i" class="btn btn-danger float-left">Reject</button>
                                                                                                    <button @click="rejectModalShow = !rejectModalShow" class="btn btn-warning float-right">Cancel</button>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-12">
                                                                                    <div  class="sslapi-res-area">
                                                                                        <h4 style="font-weight:bold" :id="'sslres_msg'+i" :data-id="i" /></h4>
                                                                                        <input type="hidden" :id="'txn_number'+i" name="txn_number[]" class="input_values">
                                                                                        <input type="hidden" :id="'txn_date'+i" name="txn_date[]" class="input_values">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="col-md-12">
                                                                                    <div class="correction-area">
                                                                                        <p><input @click="correctionShowFunc"  :id="'correction-box-show'+i" :data-id="i" type="checkbox" > <label  :for="'correction-box-show'+i" >CORRECTION</label></p>
                                                                                        <div :id="'correction-box'+i" class="correction-box" style="display: none;">
                                                                                            <p v-for="(correctionItem,i_in) in correctionList">
                                                                                                <input class="input_values" :name='i+"correction_name[]"' :id="'signle-cor-item'+i+i_in"  type="checkbox" :value="correctionItem.Correction" @click="correctionFeeFunc" :data-id="i"> 
                                                                                                <label :for="'signle-cor-item'+i+i_in" @click="correctionFeeFunc" :data-id="i">@{{ correctionItem.Correction }}</label>
                                                                                            </p>
                                                                                        </div>
                                                                                        <p :id="'correction-box-select-all'+i" style="display: none;" ><input :id="'correction-all-select'+i" :data-id="i" @click="corrSelectAllFunc"  type="checkbox"> <label :for="'correction-all-select'+i">SELECT ALL</label></p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="webfile-not-found">
                                                                <h3 v-if="webfileDataNull" class="text-center">Opps! No Data Found</h3>
                                                                <h3 v-if="webfileDataNull" class="text-center"></h3>
                                                            </div>
                                                            <div class="webfile-not-found">
                                                                <h3 v-if="storeResStatus" class="text-center">@{{ storeResMsg }}</h3>
                                                                <h3 :id="'rejected_res_msg'+i" :data-id="i" style="color:red;text-align:center"></h3>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                               
                                                <div class="col-md-12">
                                                    <button @click="addMoreButtonFunc" class="btn btn-info btn-sm"><i class="fa fa-plus"></i></button>
                                                    <button @click="clearButtonFunc" class="btn btn-default btn-sm">Remove</button>
                                                </div>
                                                
                                                <div class="col-md-12">
                                                    <div  class="webfile-submit-area">
                                                        <button  @click="submitFunc" type="button" class="btn btn-primary" data-toggle="modal" data-target="#submitModal">Submit</button> 
                                                        <!-- Modal -->
                                                        <div v-show="submitModalShow" class="custom-modal-area">
                                                            <div class="custom-modal">
                                                                <div class="custom-modal-header">
                                                                    <p>CHECK BEFORE THE SUBMIT</p>
                                                                </div>
                                                                <div class="custom-modal-body">
                                                                    <table>
                                                                        <tr v-for="vcItem in visaChecklist">
                                                                            <td><span>@{{ vcItem }}</span></td>
                                                                        </tr>
                                                                    </table>

                                                                </div>
                                                                <div class="custom-modal-footer">
                                                                    <button @click="DataSubmitFunc" class="btn btn-info float-left">YES</button>
                                                                    <button @click="submitModalShow = !submitModalShow" class="btn btn-warning float-right">Cancel</button>
                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <h2 v-if="storeResStatusf" id="data_store_msg" style="text-align:center">@{{storeResMsg}}</h2> 
                                                <p class="text-center" v-for="failsd in failToSaveArr" style="font-weight:bold;color:red;">@{{failsd}}</p>   
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        
        var app = new Vue({
            el:'#app1',
            data:{
                selectedToken:true,
                svc_name:'',
                regular:true,
                stkr_str:'',
                stkr_end:'',
                regularData:'',
                regularDataList:[],
                waitingDataList:[],
                recallDataList:[],
                passType:0,
                tokenNumber:'',
                test:'',
                webfileData:false,
                webfileDataNull:false,
                webfilePreloader:false,
                passportSearch:false,
                webfileUC:'',
                webfile2Value:'',
                cleanBtn:false,
                submitBtn:false,
                correctionShow:false,
                correctionList:[],
                corAllSelected:false,
                corItem:[],
                paytypeOptions:[],
                rejectCauseData:[],
                rejectItem:[],
                selectRejectAll: false,
                sslResMessage:'',
                submitModalShow:false,
                rejectModalShow:false,
                ivac_svc_fee:0,
                ttdDelDate:'',
                correctionFee:'',
                visaChecklist:[],
                txnNumber:'',
                txnDate:'',
                storeResMsg:'',
                storeResStatus:false,
                storeResStatusf:false,
                rejectResMsg:'',
                rejectResStatus:false,
                selectedTokenval:'',
                selectedTokenQty:'',
                styleRelative:'relative',
                styleIndex:'-2',
                vasa_feeF:'',
                faxTnsChargeFee:'',
                icwfFee:'',
                visaAppChargeFee:'',
                totalFeeValue:0,
                addMoreButtonArr:[1],
                addMoreBtn:-1,
                is_all_data_valid:false,
                not_all_data_valid:true,
                failToSaveArr:[],

            },
            methods: {
                svcNameFunc: function (event) {
                    this.svc_name = event.target.value;
                    this.getdataserver = true;
                },
                clickFunc: function (event) {
                    console.log(event);
                },
                regularAreaCLickFunc: function () {
                    _this = this;
                    var service_type = document.getElementById('service_type').value;
                    var counter_id = document.getElementById('counter_id').value;
                    var user_id = document.getElementById('user_id').value;
                    var floor_id = document.getElementById('floor_id').value;
                    floor_id = Number(floor_id);
                    axios.get('call_token_data_axios', {
                        params: {
                            svc_name: service_type,
                            token_type: '1',
                            counter_id: counter_id,
                            floor_id: floor_id
                        }
                    })
                        .then(function (res) {
                            var obj = res.data.token_res[0];
                            const resultArray = Object.keys(obj).map(function (key) {
                                return [Number(key), obj[key]];
                            });
                            _this.selectedTokenval = resultArray[0][1];
                            _this.selectedTokenQty = resultArray[1][1];
                        })
                        .catch(function (error) {
                            console.log(error);
                        })

                },
                waitingItemClick: function () {
                    _this = this;
                    var wattkn_no = event.target;
                    wattkn_no = wattkn_no.innerHTML;
                    var service_type = document.getElementById('service_type').value;
                    var counter_id = document.getElementById('counter_id').value;
                    var user_id = document.getElementById('user_id').value;
                    var floor_id = document.getElementById('floor_id').value;
                    axios.get('call_token_data_axios', {
                        params: {
                            svc_name: service_type,
                            token_type: '2',
                            counter_id: counter_id,
                            user_id: user_id,
                            tkn_no: wattkn_no,
                            floor_id:floor_id
                        }
                    })
                        .then(function (res) {
                            var obj = res.data.token_res[0];
                            const resultArray = Object.keys(obj).map(function (key) {
                                return [Number(key), obj[key]];
                            });
                            _this.selectedTokenval = resultArray[0][1];
                            _this.selectedTokenQty = resultArray[1][1];
                        })
                        .catch(function (error) {
                            console.log(error);
                        })
                },
                recallItemClick: function () {
                    _this = this;
                    var retkn_no = event.target;
                    retkn_no = retkn_no.innerHTML;
                    var service_type = document.getElementById('service_type').value;
                    var counter_id = document.getElementById('counter_id').value;
                    var user_id = document.getElementById('user_id').value;
                    var floor_id = document.getElementById('floor_id').value;
                    axios.get('call_token_data_axios', {
                        params: {
                            svc_name: service_type,
                            token_type: '2',
                            counter_id: counter_id,
                            user_id: user_id,
                            tkn_no: retkn_no,
                            floor_id:floor_id
                        }
                    })
                        .then(function (res) {
                            var obj = res.data.token_res[0];
                            const resultArray = Object.keys(obj).map(function (key) {
                                return [Number(key), obj[key]];
                            });
                            _this.selectedTokenval = resultArray[0][1];
                            _this.selectedTokenQty = resultArray[1][1];
                        })
                        .catch(function (error) {
                            console.log(error);
                        })
                },
                checkvalidStickerNoFunc:function(event){
                    var id_name = event.target.getAttribute('id');
                    $("#"+id_name).on("keypress keyup blur", function (event) {
                        $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
                        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                            event.preventDefault();
                        }
                    });
                    $("#"+id_name).on("input", function() {
                        if (/^0/.test(this.value)) {
                            this.value = this.value.replace(/^0/, "")
                        }
                    });
                },
                webfileSubmit:function(event) {
                    var id_index = event.target.getAttribute('data-id');
                    this.webfileDataNull = false;
                    this.webfileData = false;
                    this.submitBtn = false;
                    this.cleanBtn = false;
                    this.cleanBtn = false;
                    this.passportSearch = false;
                    this.storeResStatus = false;
                    this.storeResStatusf = false;
                    this.rejectResStatus = false;
                    this.styleIndex = '-2';
                    this.correctionShow = false;
                    this.corItem = [];
                    this.corAllSelected = false;
                    document.getElementById('visa_type'+id_index).value = '';
                    document.getElementById('name'+id_index).value = '';
                    document.getElementById('passportNo'+id_index).value = '';
                    document.getElementById('proc_fee_res_value'+id_index).value = '';
                    // document.getElementById('Spfee').value = '';
                    // document.getElementById('visa_type').value = '';
                    document.getElementById('contact'+id_index).value = '';
                    this.txnNumber = '';
                    this.txnDate = '';
                    this.ttdDelDate = '';
                    document.getElementById('old_pass'+id_index).value = '';
                    document.getElementById('paytype'+id_index).value = '';
                    var service_type = document.getElementById('service_type').value;
                    var gratiseYes = document.getElementById('gratiseYes'+id_index);
                    var gratiseNo = document.getElementById('gratiseNo'+id_index);
                    var validStkr = document.getElementById('validStkr'+id_index).value;

                    _this = this;
                    var sticker = document.getElementById('sticker_type').value;
                    var book_rcvpt_no = document.getElementById('book_rcvpt_no').value;
                    var stkrNumST = Number(this.stkr_str);
                    var stkrNumEND = Number(this.stkr_end);
                    if (service_type == '') {
                        alert('Please Select Service Type');
                    }
                    else if (this.selectedToken == false) {
                        alert('Please Select Token');
                    }
                    else if (sticker == '') {
                        alert('Please Select Sticker Type')
                    }
                    else if (stkrNumST == '' || stkrNumEND == '') {
                        alert('Please Input Sticker Starting and Ending Number');
                    }
                    else if (stkrNumST >= stkrNumEND) {
                        alert('Please Input Valid Sticker Number');
                    }
                    else if(gratiseYes.checked === false && gratiseNo.checked === false){
                        alert('Please Select a GRATIS');
                    }
                    else if(validStkr == ''){
                        alert('Please Enter Sticker Number');
                    }
                    else if(validStkr < stkrNumST || validStkr > stkrNumEND){
                        alert('Please Enter Valid Sticker No.');
                    }
                    else {
                        var webfile = document.getElementById('webfile'+id_index).value;
                        var webfile = webfile.split(' ').join('');
                        if (!webfile == '') {
                            var webfileDataIn = webfile.toUpperCase();
                            var webfileCheck = webfileDataIn.substring(0, 3);
                            this.webfile2focus = true;
                            if (webfileCheck == 'BGD') {
                                this.passportSearch = false;
                                this.styleIndex = '-2';
                                document.getElementById('webfile'+id_index).value = webfileDataIn;
                                var user_id = document.getElementById('user_id').value;
                                axios.get('get_app_list_for_rcvd_by_webfile', {
                                    params: {
                                        webfile: webfileDataIn,
                                        user: user_id,
                                        save: 'N',
                                        checkBy:'web'
                                    }
                                }, this.webfilePreloader = true)
                                    .then(function (res) {
                                        var webfileData = res.data.webfileData;
                                        if (!webfileData == '') {
                                            _this.webfileData = true;
                                            _this.webfileDataNull = false;
                                            _this.submitBtn = true;
                                            _this.cleanBtn = true;
                                            $('#rejected_button_show'+id_index).show();

                                            document.getElementById('webfile'+id_index).value = res.data.webfileData.WebFile_no;
                                            // document.getElementById('web_file_no').value = res.data.webfileData.WebFile_no;
                                            document.getElementById('name'+id_index).value = res.data.webfileData.Applicant_name;
                                            document.getElementById('passport_show'+id_index).innerText = res.data.webfileData.Passport;
                                            document.getElementById('contact'+id_index).value = res.data.webfileData.Contact;
                                            document.getElementById('paytype'+id_index).value = res.data.paytype;


                                            var ssldata = res.data.sllData.split(',');
                                            document.getElementById('paytype'+id_index).innerHTML = res.data.paytype;

                                            document.getElementById('txn_number'+id_index).value  = ssldata[3];
                                            document.getElementById('txn_date'+id_index).value = ssldata[4];
                                            if (ssldata[0] == 'Yes') {
                                                if (ssldata[2] == '') {
                                                    document.getElementById('sslres_msg'+id_index).innerText = ssldata[3] + ' ' + ssldata[4];
                                                    document.getElementById('proc_fee_res'+id_index).innerText = ssldata[6];
                                                    document.getElementById('proc_fee_res_value'+id_index).value = ssldata[6];
                                                }
                                                else if (!ssldata[1] == '0') {
                                                    document.getElementById('sslres_msg'+id_index).innerText = 'Already checked ' + ssldata[1] + ' on ' + ssldata[2] + ' amount ' + ssldata[6];
                                                    document.getElementById('proc_fee_res'+id_index).innerText = ssldata[6];
                                                    document.getElementById('proc_fee_res_value'+id_index).value = ssldata[6];
                                                }

                                            }
                                            else if (ssldata[0] == 'No') {
                                                document.getElementById('sslres_msg'+id_index).innerText = 'No Payment Data Found';
                                                document.getElementById('proc_fee_res'+id_index).innerText = '0.00';
                                                document.getElementById('proc_fee_res_value'+id_index).value = '0.00';
                                            }
                                            else {
                                                document.getElementById('sslres_msg'+id_index).innerText = 'SSL Server Not Found';
                                            }

                                            _this.specialCharBlock();

                                        }
                                        else {
                                            _this.webfileDataNull = true;
                                            _this.webfileData = false;
                                            _this.submitBtn = false;
                                            _this.cleanBtn = false;
                                            document.getElementById('webfile'+id_index).value = '';
                                        }
                                        _this.webfilePreloader = false;

                                    })
                                    .catch(function (error) {
                                        console.log(error);
                                        _this.webfilePreloader = false;
                                        _this.cleanBtn = true;
                                    });

                            }
                            else {

                                this.webfile2Value = '';
                                $('#webfile_p'+id_index).hide();
                                $('#webfile2_p'+id_index).show();
                                $('#webfileNo2'+id_index).focus();
                                document.getElementById('passportNo'+id_index).value = webfileDataIn;
                                document.getElementById('webfileNo2'+id_index).value = '';
                                this.passportSearch = true;
                                this.styleIndex = '1';
                                // document.getElementById('PassportNo2').value = webfileDataIn;
                                this.cleanBtn = true;
                                this.submitBtn = false;
                                this.webfileDataNull = false;
                            }
                        }
                    }
                },
                webfileSubmit2: function(event) {
                    var id_index = event.target.getAttribute('data-id');

                    this.storeResStatus = false,
                    this.storeResStatusf = false,
                    this.rejectResStatus = false,
                    _this = this;
                    this.correctionShow = false;
                    this.corItem = [];
                    this.corAllSelected = false;

                    var service_type = document.getElementById('service_type').value;
                    var gratiseYes = document.getElementById('gratiseYes'+id_index);
                    var gratiseNo = document.getElementById('gratiseNo'+id_index);
                    var validStkr = document.getElementById('validStkr'+id_index).value;
                    var sticker = document.getElementById('sticker_type').value;
                    var book_rcvpt_no = document.getElementById('book_rcvpt_no').value;
                    var stkrNumST = Number(this.stkr_str);
                    var stkrNumEND = Number(this.stkr_end);
                    if (service_type == '') {
                        alert('Please Select Service Type');
                    }
                    else if (this.selectedToken == false) {
                        alert('Please Select Token');
                    }
                    else if (sticker == '') {
                        alert('Please Select Sticker Type')
                    }
                    else if (stkrNumST == '' || stkrNumEND == '') {
                        alert('Please Input Sticker Starting and Ending Number');
                    }
                    else if (stkrNumST >= stkrNumEND) {
                        alert('Please Input Valid Sticker Number');
                    }
                    else if(gratiseYes.checked === false && gratiseNo.checked === false){
                        alert('Please Select a GRATIS');
                    }
                    else if(validStkr == ''){
                        alert('Please Enter Sticker Number');
                    }
                    else if(validStkr < stkrNumST || validStkr > stkrNumEND){
                        alert('Please Enter Valid Sticker No.');
                    }
                    else {
                        var webfileNo2 = document.getElementById('webfileNo2'+id_index).value;
                        webfileNo2 = webfileNo2.split(' ').join('');
                        if (!webfileNo2 == '') {
                            var webfileNo2 = webfileNo2.toUpperCase();
                            var webfile2Check = webfileNo2.substring(0, 3);
                            document.getElementById('webfileNo2'+id_index).value = webfileNo2;
                            if (webfile2Check == 'BGD') {
                                this.passportSearch = false;
                                this.styleIndex = '-2';
                                var user_id = document.getElementById('user_id').value;
                                var checkByPass = document.getElementById('passportNo'+id_index).value;


                                axios.get('get_app_list_for_rcvd_by_webfile', {
                                    params: {
                                        webfile: webfileNo2,
                                        user: user_id,
                                        save: 'N',
                                        checkBy:checkByPass
                                    }
                                }, this.webfilePreloader = true)
                                    .then(function (res) {
                                        console.log(res.data);


                                        var webfileData = res.data.webfileData;
                                        if (!webfileData == '') {
                                            _this.webfileData = true;
                                            _this.webfileDataNull = false;
                                            _this.submitBtn = true;
                                            _this.cleanBtn = true;
                                            document.getElementById('webfile'+id_index).value = webfileNo2;
                                            document.getElementById('webfileNo2'+id_index).value = '';
                                            $('#webfile_p'+id_index).show();
                                            $('#webfile2_p'+id_index).hide();
                                            $('#webfile'+id_index).focus();
                                            $('#rejected_button_show'+id_index).show();

                                            document.getElementById('webfile'+id_index).value = res.data.webfileData.WebFile_no;
                                            // document.getElementById('web_file_no').value = res.data.webfileData.WebFile_no;
                                            document.getElementById('name'+id_index).value = res.data.webfileData.Applicant_name;
                                            document.getElementById('passport_show'+id_index).innerText = res.data.webfileData.Passport;
                                            document.getElementById('contact'+id_index).value = res.data.webfileData.Contact;
                                            document.getElementById('paytype'+id_index).value = res.data.paytype;


                                            var ssldata = res.data.sllData.split(',');
                                            document.getElementById('paytype'+id_index).innerHTML = res.data.paytype;

                                            document.getElementById('txn_number'+id_index).value  = ssldata[3];
                                            document.getElementById('txn_number'+id_index).value  = ssldata[3];
                                            document.getElementById('txn_date'+id_index).value = ssldata[4];
                                            if (ssldata[0] == 'Yes') {
                                                if (ssldata[2] == '') {
                                                    document.getElementById('sslres_msg'+id_index).innerText = ssldata[3] + ' ' + ssldata[4];
                                                    document.getElementById('proc_fee_res'+id_index).innerText = ssldata[6];
                                                    document.getElementById('proc_fee_res_value'+id_index).value = ssldata[6];
                                                }
                                                else if (!ssldata[1] == '0') {
                                                    document.getElementById('sslres_msg'+id_index).innerText = 'Already checked ' + ssldata[1] + ' on ' + ssldata[2] + ' amount ' + ssldata[6];
                                                    document.getElementById('proc_fee_res'+id_index).innerText = ssldata[6];
                                                    document.getElementById('proc_fee_res_value'+id_index).value = ssldata[6];
                                                }

                                            }
                                            else if (ssldata[0] == 'No') {
                                                document.getElementById('sslres_msg'+id_index).innerText = 'No Payment Data Found';
                                                document.getElementById('proc_fee_res'+id_index).innerText = '0.00';
                                                document.getElementById('proc_fee_res_value'+id_index).value = '0.00';
                                            }
                                            else {
                                                document.getElementById('sslres_msg'+id_index).innerText = 'SSL Server Not Found';
                                            }

                                            _this.specialCharBlock();
                                        }
                                        else {
                                            _this.webfileDataNull = true;
                                            _this.webfileData = false;
                                            _this.submitBtn = false;
                                            _this.cleanBtn = false;

                                            document.getElementById('webfile').value = '';

                                        }
                                        _this.webfilePreloader = false;

                                    })
                                    .catch(function (error) {
                                        console.log(error);
                                        _this.webfilePreloader = false;
                                        _this.cleanBtn = true;
                                        console.log('hello');
                                    })
                            }
                            else {
                                document.getElementById('webfile'+id_index).value = '';
                                $('#webfile_p'+id_index).hide();
                                $('#webfile2_p'+id_index).show();
                                $('#webfileNo2'+id_index).focus();
                                alert('Please Input Valid Webfile No');

                            }

                        }
                    }
                },
                oldPassQtyFunc:function(event){
                    var id_name = event.target.getAttribute('id');
                    $("#"+id_name).on("keypress keyup blur", function (event) {
                        $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
                        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                            event.preventDefault();
                        }
                    });
                },
                TotalFeeFunc:function(event){
                    var id = event.target.getAttribute('id');
                    var id_index = event.target.getAttribute('data-id');
                    var id_name = event.target.getAttribute('id');
                    $("#"+id_name).on("keypress keyup blur", function (event) {
                        $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
                        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                            event.preventDefault();
                        }
                    });


                    var visaFee = document.getElementById('visaFee'+id_index).value;
                    var faxCharge = document.getElementById('faxCharge'+id_index).value;
                    var icwf = document.getElementById('icwf'+id_index).value;
                    var appChange = document.getElementById('appCharge'+id_index).value;
                    var totalFeeSum = Number(visaFee)+Number(faxCharge)+Number(icwf)+Number(appChange);
                    document.getElementById('total_fee_disable'+id_index).value = totalFeeSum;
                    document.getElementById('total_fee'+id_index).value = totalFeeSum;
                    // $('#total_fee_disable'+id_index).val() = totalFeeSum;
                    // this.totalFeeValue = Number(this.vasa_feeF)+Number(this.faxTnsChargeFee)+Number(this.icwfFee)+Number(this.visaAppChargeFee);
                },
                corrSelectAllFunc: function (event) {
                    var id_name = event.target.getAttribute('id');
                    var id_index = event.target.getAttribute('data-id');
                    var corSelectAll = document.getElementById(id_name);
                    var is_cor_fee = false;
                    for(var k = 0; k < this.correctionList.length; k++){
                        var cor_item = document.getElementById(id_name);
                        if(cor_item.checked === true){
                            is_cor_fee = true;
                        }
                    }

                    if(is_cor_fee == true){
                        $('#cor_item_fee_value2'+id_index).hide();
                        $('#cor_item_fee_value'+id_index).show();
                    }
                    else{
                        $('#cor_item_fee_value'+id_index).hide();
                        $('#cor_item_fee_value2'+id_index).show();
                    }

                    if(corSelectAll.checked === true){
                        for(var j=0; j < this.correctionList.length; j++){
                            var single_select_all = document.getElementById("signle-cor-item"+id_index+j);
                            if(single_select_all.checked === false){
                                single_select_all.checked = true;
                            }
                        }
                    }
                    else if(corSelectAll.checked === false){
                        for(var j=0; j < this.correctionList.length; j++){
                            var single_select_all = document.getElementById("signle-cor-item"+id_index+j);
                            if(single_select_all.checked === true){
                                single_select_all.checked = false;
                            }
                        }
                    }

                },
                correctionFeeFunc: function(event){
                    var id_name = event.target.getAttribute('id');
                    var id_index = event.target.getAttribute('data-id');
                    var is_cor_fee = false;
                    for(var j = 0; j < this.correctionList.length; j++){
                        var cor_item = document.getElementById('signle-cor-item'+id_index+j);
                        if(cor_item.checked === true){
                            is_cor_fee = true;
                        }
                    }

                    if(is_cor_fee == true){
                        $('#cor_item_fee_value2'+id_index).hide();
                        $('#cor_item_fee_value'+id_index).show();
                    }
                    else{
                        $('#cor_item_fee_value'+id_index).hide();
                        $('#cor_item_fee_value2'+id_index).show();
                    }


                },
                correctionShowFunc: function (event) {
                    var id_name = event.target.getAttribute('id');
                    var id_index = event.target.getAttribute('data-id');
                    var curBoxShow = document.getElementById(id_name);

                    if(curBoxShow.checked === true){
                        $('#correction-box'+id_index).show();
                        $('#correction-box-select-all'+id_index).show();
                        document.getElementById(id_name).checked = true;
                    }
                    else if(curBoxShow.checked === false){
                        $('#correction-box'+id_index).hide();
                        $('#correction-box-select-all'+id_index).hide();
                        document.getElementById(id_name).checked = false;

                        for(var j=0; j < this.correctionList.length; j++){
                            var single_select_all = document.getElementById("signle-cor-item"+id_index+j);
                            if(single_select_all.checked === true){
                                single_select_all.checked = false;
                                $('#cor_item_fee_value'+id_index).hide();
                                $('#cor_item_fee_value2'+id_index).show();
                            }
                        }
                        document.getElementById("correction-all-select"+id_index).checked = false;

                    }
                    
                },
                
                selectRejectAllFunc: function () {
                    this.rejectItem = [];
                    if (!this.selectRejectAll) {
                        for (item in this.rejectCauseData) {
                            this.rejectItem.push(this.rejectCauseData[item].reason);
                            console.log(this.rejectCauseData[item].reason);
                        }
                    }
                },
                rejectBtnFunc: function () {
                    this.rejectModalShow = true;
                    this.rejectItem = [];
                    if (this.rejectItem == '') {
                        this.selectRejectAll = false;
                    }
                },

                visaTypeOnChange: function (event) {
                    var id_name = event.target.getAttribute('id');
                    var id_index = event.target.getAttribute('data-id');
                    // console.log(id_name);
                    var visa_type = document.getElementById(id_name).value;
                    if (!visa_type == '') {
                        var tdd_date = document.getElementById('tdd_' + visa_type).value;
                        document.getElementById('tddDelDateId'+id_index).innerText = tdd_date;
                        document.getElementById('tddDelDateValue'+id_index).value = tdd_date;
                        // this.ttdDelDate = tdd_date;
                        // console.log(tdd_date);
                        var visaTypeData = $('.visa_type_value').serialize();
                        axios.post('visatype-foreign-check-axios', visaTypeData)
                            .then(function (res) {
                                _this.visaChecklist = res.data.visaChecklist;
                                console.log(res);
                            })
                            .catch(function (error) {
                                console.log(error);
                            })
                    }
                    else {
                        document.getElementById('tddDelDateId'+id_index).innerText = '';
                    }
                },

                submitFunc: function (event) {
                    this.submitModalShow = false;
                    this.not_all_data_valid = true;
                    var is_alert_already = true;
                    var DoubleValidStkrArr = [];
                    for(var k=0;k < this.addMoreButtonArr.length; k++){
                        var id_index = k;
                        var getPass = document.getElementById('passportNo'+id_index).value;
                        var passNo = getPass.trim();
                        passNo = passNo.toUpperCase();
                        passNo = passNo.split(' ').join('');
                        var getShowPass = document.getElementById('passport_show'+id_index).innerText;
                        getShowPass = getShowPass.trim();
                        var showPass = getShowPass.split(' ').join('');
                        var gratisVal = $("input[name='gratis_status1']:checked").val();
                        var validStkr = document.getElementById('validStkr'+id_index).value;
                        var validStkrF = Number(validStkr);
                        DoubleValidStkrArr.push(validStkrF);
                        var inputTSt = Number(this.stkr_str);
                        var validStkr2 = document.getElementById('validStkr'+id_index).value;
                        var validStkr2F = Number(validStkr2);
                        var InputTE = Number(this.stkr_end);
                        var validSticker = document.getElementById('validStkr'+id_index).value;
                        var validStikerType = document.getElementById('sticker_type').value;
                        var gratiseYes = document.getElementById('gratiseYes'+id_index);
                        var gratiseNo = document.getElementById('gratiseNo'+id_index);
                        var webfile = document.getElementById('webfile'+id_index).value;
                        var contact_num = document.getElementById('contact'+id_index).value;
                        var name = document.getElementById('name'+id_index).value;
                        var contact_num = document.getElementById('contact'+id_index).value;
                        var visa_type = document.getElementById('visa_type'+id_index).value;
                        var nationality = document.getElementById('nationality'+id_index).value;
                        var duration = document.getElementById('duration'+id_index).value;
                        var entry_type = document.getElementById('entry_type'+id_index).value;
                        var visaFee = document.getElementById('visaFee'+id_index).value;
                        var faxCharge = document.getElementById('faxCharge'+id_index).value;
                        var icwf = document.getElementById('icwf'+id_index).value;
                        var appCharge = document.getElementById('appCharge'+id_index).value;
                        var paytype = document.getElementById('paytype'+id_index).value;
                        var old_pass = document.getElementById('old_pass'+id_index).value;
                        
                        if(webfile != '' && showPass != ''){

                            if(DoubleValidStkrArr.length > 1){
                                var obj = {};
                                $(".doubleStkr").each(function(){
                                    if(obj.hasOwnProperty(this.value)) {
                                        if(is_alert_already ==  true){
                                            is_alert_already = false;
                                            this.not_all_data_valid = false;
                                            this.submitModalShow = false;
                                            alert("there is a duplicate value " + this.value);
                                        }
                                    } 
                                    else {
                                        obj[this.value] = this.value;
                                    }
                                });
                            }
                            else if(validStkrF < inputTSt || validStkr2F > InputTE)
                            {   
                                if(is_alert_already ==  true){
                                    is_alert_already = false;
                                    this.not_all_data_valid = false;
                                    this.submitModalShow = false;
                                    alert('Please Input Valid Sticker Number '+id_index);
                                }
                            }
                           
                            else if(passNo != showPass)
                            {   
                                if(is_alert_already ==  true){
                                    is_alert_already = false;
                                    this.not_all_data_valid = false;
                                    this.submitModalShow = false;
                                    $('#passportNo'+id_index).focus();
                                    alert('Please Enter Valid Passport Number '+id_index);
                                }
                            }
                            else if (name == '')
                            {   
                                if(is_alert_already ==  true){
                                    is_alert_already = false;
                                    this.not_all_data_valid = false;
                                    this.submitModalShow = false;
                                    alert('Please Enter Valid Name '+id_index);
                                }
                            }
                            else if(contact_num.length != 10)
                            {   
                                if(is_alert_already ==  true){
                                    is_alert_already = false;
                                    this.not_all_data_valid = false;
                                    this.submitModalShow = false;
                                    alert('Please Enter Valid Contact Number '+id_index);
                                }
                            }
                            else if (nationality == '')
                            {   
                                if(is_alert_already ==  true){
                                    is_alert_already = false;
                                    this.not_all_data_valid = false;
                                    this.submitModalShow = false;
                                    alert('Please Enter Valid Nationality '+id_index);
                                }
                            }
                            else if(visa_type == '')
                            {   
                                if(is_alert_already ==  true){
                                    is_alert_already = false;
                                    this.not_all_data_valid = false;
                                    this.submitModalShow = false;
                                    alert('Please Select Visa Type '+id_index);
                                }
                            }
                            else if(duration == '')
                            {   
                                if(is_alert_already ==  true){
                                    is_alert_already = false;
                                    this.not_all_data_valid = false;
                                    this.submitModalShow = false;
                                    alert('Please Enter Valid Duration '+id_index);
                                }
                            }
                            else if(entry_type == '')
                            {   
                                if(is_alert_already ==  true){
                                    is_alert_already = false;
                                    this.not_all_data_valid = false;
                                    this.submitModalShow = false;
                                    alert('Please Enter Valid entry_type '+id_index);
                                }
                            }     
                            else{

                                if(gratiseYes.checked == false && gratiseNo.checked == true)
                                {   
                                    if(visaFee ==  ''){
                                        is_alert_already = false;
                                        this.not_all_data_valid = false;
                                        this.submitModalShow = false;
                                        alert('Please Enter Visa Fee '+id_index);
                                    }
                                    else if(faxCharge == ''){
                                        is_alert_already = false;
                                        this.not_all_data_valid = false;
                                        this.submitModalShow = false;
                                        alert('Please Enter Fax Charge '+id_index);
                                    }
                                    else if(icwf == ''){
                                        is_alert_already = false;
                                        this.not_all_data_valid = false;
                                        this.submitModalShow = false;
                                        alert('Please Enter ICWF '+id_index);
                                    }
                                    
                                    else if(old_pass == ''){   
                                        if(is_alert_already ==  true){
                                            is_alert_already = false;
                                            this.not_all_data_valid = false;
                                            this.submitModalShow = false;
                                            alert('Please Enter Old Passport Qty '+id_index);
                                        }
                                    }
                                    else if(paytype == ''){   
                                        if(is_alert_already ==  true){
                                            is_alert_already = false;
                                            this.not_all_data_valid = false;
                                            this.submitModalShow = false;
                                            alert('Please Select Payment Type '+id_index);
                                        }
                                    }
                                    
                                    else{
                                        this.is_all_data_valid = true;
                                    }
                                }
                                else if(old_pass == ''){   
                                    if(is_alert_already ==  true){
                                        is_alert_already = false;
                                        this.not_all_data_valid = false;
                                        this.submitModalShow = false;
                                        alert('Please Enter Old Passport Qty '+id_index);
                                    }
                                }
                                else if(paytype == ''){   
                                    if(is_alert_already ==  true){
                                        is_alert_already = false;
                                        this.not_all_data_valid = false;
                                        this.submitModalShow = false;
                                        alert('Please Select Payment Type '+id_index);
                                    }
                                }
                                
                                else{
                                    this.is_all_data_valid = true;
                                }

                            }  
                        }

                    }


                    if(this.is_all_data_valid){
                        if(this.not_all_data_valid){
                            _this = this;
                            axios.get('check_foreign_valid_sticker_axios',{params:{validSticker:validSticker,validStikerType:validStikerType}})
                                .then(function(res){
                                    console.log(res.data.validStatus);
                                    if(res.data.validStatus == 'Yes'){
                                        _this.submitModalShow = true;
                                    }
                                    else if(res.data.validStatus == 'No'){
                                        alert('This sticker number already used');
                                    }
                                })
                                .catch(function(error){
                                    console.log(error);
                                })
                        }
                    }

                    validStkr = '';
                    validStkr2 = '';
                    this.is_all_data_valid = false;


                },
                addMoreButtonFunc:function(){
                    
                    this.addMoreButtonArr.push({});
                    this.storeResStatusf = false;
                    this.storeResMsg = '';
                    this.failToSaveArr = [];
                },
                removeMoreButtonFuncElemnt:function(index){
                    this.addMoreButtonArr.splice(index,1);
                },
                clearButtonFunc:function(i){
                    var is_confirm = confirm('Are you sure! You want to clear?');
                    if(is_confirm == true){
                        // this.addMoreButtonArr = [];
                        // this.addMoreBtn = 1;
                        // this.addMoreButtonArr.push(this.addMoreBtn);
                        // console.log(i);
                        const itemIndex = this.addMoreButtonArr.indexOf(i)
                        this.addMoreButtonArr.splice(itemIndex,1);
                    }
                },
                DataSubmitFunc: function () {
                     this.submitModalShow = false;
                    _this = this;
                    // var cust_name = document.getElementById('name').value;
                    // var webfile = document.getElementById('webfile').value;
                    // var passportNo = document.getElementById('passportNo').value;
                    // var passport = passportNo.toUpperCase();
                    // passport = passport.split(' ').join('');
                    // var user_id = document.getElementById('user_id').value;
                    // var counter_id = document.getElementById('counter_id').value;

                    var objectData = $('.input_values').serialize();

                    axios.post('foreign-webfile-data-save-axios', objectData,)
                        .then(function (res) {
                            _this.addMoreButtonArr = [];
                            // _this.addMoreBtn = 1;
                            // _this.addMoreButtonArr.push(this.addMoreBtn);
                            // _this.cleanBtn = false;
                            _this.storeResStatusf = true;
                            _this.storeResMsg = res.data.status;
                            console.log(res);
                            var saves = res.data.save;
                            if(saves == 'yes'){
                                document.getElementById('total_save_count').innerText = res.data.saveCount;
                                window.open('foreign-pass-receive-print', '_blank');
                                
                            }
                            else if(saves == 'notall'){
                                document.getElementById('total_save_count').innerText = res.data.saveCount;
                                _this.failToSaveArr = res.data.rejectArr;
                                window.open('foreign-pass-receive-print', '_blank');
                                
                            }
                            else if(saves == 'no'){
                                _this.failToSaveArr = res.data.rejectArr;
                            }

                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                },
                clearBtnFunc: function () {
                    if (this.passportSearch == true) {
                        this.styleIndex = '-2';
                        this.passportSearch = false;
                        this.webfile2Value = '';

                    }
                    document.getElementById('name').value = '';
                    document.getElementById('passportNo').value = '';
                    document.getElementById('validStkr').value = '';
                    // document.getElementById('proc_fee').value = '';
                    // document.getElementById('Spfee').value = '';
                    // document.getElementById('visa_type').value = '';
                    document.getElementById('contact').value = '';
                    this.txnNumber = '';
                    this.txnDate = '';
                    document.getElementById('ttdDelDate').value = '';
                    document.getElementById('old_pass').value = '';
                    document.getElementById('paytype').value = '';


                    this.webfileData = false;
                    this.submitBtn = false;
                    this.selectRejectAll = false;
                    this.corItem = [];
                    this.rejectItem = [];
                    this.sslResMessage = '';
                    document.getElementById('webfile').value = '';
                },
                rejectSubmitFunc: function(event) {
                    this.rejectModalShow = false;
                    var id_name = event.target.getAttribute('id');
                    var id_index = event.target.getAttribute('data-id');
                    var webfile = document.getElementById('webfile'+id_index).value;
                    _this = this;
                    axios.get('foreign-reject-submit-axios', {
                        params: {
                            webfile: webfile,
                            rejectedCauses: this.rejectItem,
                            save: 'Y'
                        }
                    }, this.webfilePreloader = true)
                        .then(function (res) {
                            _this.webfilePreloader = false;
                            console.log(res);
                            if (res.data.status = 'yes') {
                                if(_this.addMoreButtonArr.length <= 1){
                                    _this.addMoreButtonArr = [];
                                    _this.storeResStatusf = true;
                                    _this.storeResMsg = res.data.statusMsg;
                                }

                                _this.rejectModalShow = false;
                                document.getElementById('total_reject_count').innerText = res.data.rejectCount;
                                //console.log('d')
                                _this.rejectResStatus = true;
                                document.getElementById('rejected_res_msg'+id_index).innerText = res.data.statusMsg;
                                _this.clearBtnFunc();
                                _this.cleanBtn = false;

                            }

                        })
                        .catch(function (error) {

                        })
                },
                sendToWaitingFunc: function () {
                    if (this.selectedTokenval != '') {
                        console.log(this.selectedTokenval);
                        var service_type = document.getElementById('service_type').value
                        var floor_id = document.getElementById('floor_id').value
                        axios.get('send-token-to-waiting-axios', {
                            params: {
                                token: this.selectedTokenval,
                                type: '2',
                                service_type: service_type,
                                floor_id: floor_id
                            }
                        })
                            .then(function (res) {
                                console.log(res);
                            })
                            .catch(function (error) {
                                console.log(error);
                            });
                    }
                    else {
                        alert('Please Select Token')
                    }
                },
                sendToRecallFunc: function () {
                    var recallVal = document.getElementById('send_recall_id').value;
                    var service_type = document.getElementById('service_type').value;
                    var floor_id = document.getElementById('floor_id').value;
                    if(service_type != ''){
                        axios.get('send-token-to-recall-axios', {
                            params: {
                                token: recallVal,
                                type: '3',
                                service_type: service_type,
                                floor_id: floor_id
                            }
                        })
                            .then(function (res) {
                                console.log(res);
                                document.getElementById('send_recall_id').value = '';
                            })
                            .catch(function (error) {
                                console.log(error);
                            });
                    }
                    else{
                        alert('Please Select Service Type');
                    }

                },
                specialCharBlock:function(){
                    $('#webfile').on('keypress', function (event) {
                        var regex = new RegExp("^[a-zA-Z0-9]+$");
                        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
                        if (!regex.test(key)) {
                           event.preventDefault();
                           return false;
                        }
                    });
                    $('#passportNo').on('keypress', function (event) {
                      var regex = new RegExp("^[a-zA-Z0-9]+$");
                      var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
                      if (!regex.test(key)) {
                        event.preventDefault();
                        return false;
                      }
                    });
                },
            },
            created:function(){

                var _this = this;
                this.svc_name = document.getElementById('service_type').value;


                setInterval(function(){
                    var svc_isset = _this.svc_name;
                    if(!svc_isset == ''){
                        axios.get('counter_call_get_data',{params:{svc_name:_this.svc_name}})
                            .then(function(res){
                                _this.regularDataList = res.data.regulars;
                                _this.waitingDataList = res.data.waitings;
                                _this.recallDataList = res.data.recalls;
                            })
                            .catch(function(error){
                                console.log(error);
                            })
                    }


                    if(_this.selectedTokenval == ''){
                        _this.selectedToken = false;
                    }else{
                        _this.selectedToken = true;
                    }

                },5000);

                _this = this;
                axios.get('get_data_onload_axios').then(function(res){
                    _this.rejectCauseData = res.data.rejectCause;
                    _this.correctionList = res.data.correctionFee;
                    _this.correctionFee = res.data.corFee.corrFee;
                    document.getElementById('total_save_count').innerText = res.data.total_save;
                    document.getElementById('total_reject_count').innerText = res.data.rejectCount;

                })
                .catch(function(error){
                    console.log(error);
                    // location.reload(true);
                })
                
            }

        });

    </script>
    <script>

        $('#webfile').on('keypress', function (event) {
            var regex = new RegExp("^[a-zA-Z0-9]+$");
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
            if (!regex.test(key)) {
               event.preventDefault();
               return false;
            }
        });
        $('#webfileNo2').on('keypress', function (event) {
          var regex = new RegExp("^[a-zA-Z0-9]+$");
          var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
          if (!regex.test(key)) {
            event.preventDefault();
            return false;
          }
        });
        $('#PassportNo2').on('keypress', function (event) {
            var regex = new RegExp("^[a-zA-Z0-9]+$");
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
            if (!regex.test(key)) {
               event.preventDefault();
               return false;
            }
        });
        $('#passportNo').on('keypress', function (event) {
          var regex = new RegExp("^[a-zA-Z0-9]+$");
          var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
          if (!regex.test(key)) {
            event.preventDefault();
            return false;
          }
        });
        $("#sticker_no_from").on("keypress keyup blur", function (event) {
            $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
        $("#sticker_no_from").on("input", function() {
            if (/^0/.test(this.value)) {
                this.value = this.value.replace(/^0/, "")
            }
        });

        $("#book_rcvpt_no").on("keypress keyup blur", function (event) {
            $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
        $("#book_rcvpt_no").on("input", function() {
            if (/^0/.test(this.value)) {
                this.value = this.value.replace(/^0/, "")
            }
        });


        $("#sticker_no_to").on("keypress keyup blur", function (event) {
            $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
        $("#sticker_no_to").on("input", function() {
            if (/^0/.test(this.value)) {
                this.value = this.value.replace(/^0/, "")
            }
        });





        $("#Spfee").on("keypress keyup blur", function (event) {
            $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
        $("#Spfee").on("input", function() {
            if (/^0/.test(this.value)) {
                this.value = this.value.replace(/^0/, "")
            }
        });
    </script>


    @endif
@endsection
