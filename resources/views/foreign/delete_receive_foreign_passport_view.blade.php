@extends('admin.master')
<!--Page Title-->
@section('page-title')
    
    Edit Receive Foreign Passport
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
        .reject_button-here {
            margin-top: 5px;
        }
        .input-group .input-group-addon {
            border-radius: 0;
            border-color: #d2d6de;
            background-color: #eee;
        }
    </style>

Edit Receive Foreign Passport
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
                    `@endif
                    <!-- Code Here.... -->

                        <div class="row">
                            <div class="col-md-12">
                                
                                    <div class="calltoken-area-right">
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

                                <div class="col-md-12">
                                    <div  v-for="(mainitem, i) in addMoreButtonArr" class="col-md-12">
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
                                                                    @if($fpServed->	gratis_status == 'yes')
                                                                        <input type="radio" name='gratis_status1' :id="'gratiseYes'+i" :data-id="i" class="input_values" value="yes" checked> Yes
                                                                    @else
                                                                        <input type="radio" name='gratis_status1' :id="'gratiseNo'+i" :data-id="i" class="input_values" value="no" checked> No
                                                                    @endif
                                                                </label>
                                                            </div>
                                                        </div>
                                                        
                                                    
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <select name="sticker_type" id="sticker_type" class="input_values form-control" style="" disabled>
                                                                    <option value=""> </option>
                                                                    @foreach($stickers as $sticker)
                                                                        @if($appServed->Sticker_type == $sticker->sticker) 
                                                                            <option value="{{$sticker->sticker}}" selected>{{$sticker->sticker}}</option>
                                                                        @else
                                                                            <option value="{{$sticker->sticker}}" >{{$sticker->sticker}}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <input @keyup="SpecialCharValidationFunc" @blur="checkValidStkrUpdateFunc" type="number" name="validStkr" :id="'validStkr'+i" :data-id="i" value={{$appServed->RoundSticker}} class="form-control input_values doubleStkr" placeholder="Sticker No." disabled  autocomplete="off">
                                                                <input id="sticker_no_hidden" type="hidden" value="{{$appServed->RoundSticker}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <input @keyup="SpecialCharValidationFunc" @blur="checkGratisStkrFunc" type="number"  :id="'gratisStrkNum'+i" :data-id="i" class="form-control input_values" placeholder="Gratis Sticker" style="display: none"    autocomplete="off">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                {{-- <input class="form-control" id="web_file_no" name="web_file_no" placeholder="Web file number" autocomplete="off" required> --}}
                                                        
                                                                <span :id="'webfile_p'+i" >
                                                                    <label for="">Webfile No.</label>
                                                                    <p><input  :id="'webfile'+i" :data-id="i" class="form-control input_values"  value="{{$fpServed->web_file_no}}"   disabled ></p>
                                                                    <p><input type="hidden" name="webfile"  class="form-control input_values"  value="{{$fpServed->web_file_no}}"   ></p>
                                                                    {{-- <p v-show="passportSearch">Passport: <input  name="PassportNo2" id="PassportNo2" style="width:200px" required autocomplete="off" class="input_values"></p> --}}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="">Applicant Name:</label>
                                                                <input class="form-control input_values" :id="'name'+i" :data-id="i" name="name" value="{{$fpServed->app_name}}"  autocomplete="off" disabled>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="">Contact</label>
                                                                <input class="form-control input_values" name="contact" :id="'contact'+i" :data-id="i" value="{{$fpServed->contact}}" pattern=".{10}" title="Minimum  & Maximum 10 digit"  disabled
                                                                    autocomplete="off" class="input_values" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Passport:</label>
                                                                <input class="form-control passport input_values" value="{{$fpServed->passport}}" :id="'passportNo'+i" :data-id="i" autocomplete="off" disabled>
                                                                <input  type="hidden" class="form-control passport input_values" name="passportNo" value="{{$fpServed->passport}}" :id="'passportNo'+i" :data-id="i" autocomplete="off" >
                                                            </div>
            
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="">Nationality</label>
                                                                <input class="form-control input_values" name="nationality" value="{{$fpServed->nationality}}" :id="'nationality'+i" :data-id="i"  autocomplete="off" disabled>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="">Total Amount</label>
                                                                <input class="form-control" name="total_amount"   :id="'total_fee_disable'+i" :data-id="i" value="{{$fpServed->total_amount}}"  placeholder="Total Amount" disabled>
                                                                <input type="hidden" class="form-control input_values" :id="'total_fee'+i" name="total_amount" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="{{URL::to('delete-foreign').'/'.$fpServed->web_file_no}}" onclick="return confirm('Are you sure! You want to Delete?')"  class="btn btn-danger">DELETE</a>
                                            <br>
                                            <br>
                                            <br>
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
        <input type="hidden" id="start_no"  value="{{@$book_no->start_no}}">
        <input type="hidden" id="end_no"  value="{{@$book_no->end_no}}">
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
                
                SpecialCharValidationFunc:function(event){
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
                checkGratisStkrFunc:function(event){
                    var id_name = event.target.getAttribute('id');
                    var id_index = event.target.getAttribute('data-id');
                    var validStkr = document.getElementById(id_name).value;
                    validStkr = Number(validStkr);
                    var is_alert_run = true;
                   
                    axios.get('check_gratis_sticker_axios',{params:{'validStkr':validStkr}})
                    .then(function(res){
                        if(res.data.validStatus == 'No'){
                            document.getElementById(id_name).value = '';
                            alert("This Gratis Stiker number already exists !");
                        };
                    })
                    .catch(function(error){
                        console.log('error');
                    });

                },
                checkValidStkrUpdateFunc:function(event){
                    var id_name = event.target.getAttribute('id');
                    var id_index = event.target.getAttribute('data-id');
                    var validStkr = document.getElementById(id_name).value;
                    validStkr = Number(validStkr);
                    var validStikerType = document.getElementById('sticker_type').value;
                    var is_alert_run = true;
                    
                    if(validStkr == ''){
                        
                    }
                    else{
                        axios.get('check_valid_sticker_axios',{params:{'validSticker':validStkr,'validStikerType':validStikerType}})
                        .then(function(res){
                            var old_stkr_no = document.getElementById('sticker_no_hidden').value;
                            old_stkr_no = Number(old_stkr_no);
                            if(old_stkr_no != validStkr){
                                if(res.data.validStatus == 'No'){
                                    document.getElementById(id_name).value = '';
                                    alert("This Stiker number already exists !");
                                };
                            }
                        })
                        .catch(function(error){
                            console.log('error');
                        });
                    }

                },
                gratisClickFunc:function(event){
                    var id_name = event.target.getAttribute('id');
                    var id_index = event.target.getAttribute('data-id');
                    var gratisYes = document.getElementById('gratiseYes'+id_index);
                    if(gratisYes.checked === true){
                        document.getElementById('gratisStrkNum'+id_index).value = '';
                        $('#gratisStrkNum'+id_index).show();
                    }
                    else{
                        document.getElementById('gratisStrkNum'+id_index).value = '';
                        $('#gratisStrkNum'+id_index).hide();
                    } 

                },
                receiptNoFunc:function(event){
                    var id_name = event.target.getAttribute('id');
                    var center_name = document.getElementById('center_name').value;
                    var receiptNo = document.getElementById(id_name).value;
                        receiptNo = Number(receiptNo);
                    var book_name =  document.getElementById('book_no').value;
                    var book_start =  document.getElementById('book_start_no').value;
                        book_start = Number(book_start);
                    var book_end =  document.getElementById('book_end_no').value;
                        book_end = Number(book_end);
                    var is_alert_run = true;
                    console.log(book_start+ ' '+book_end+' '+receiptNo);
                    if(receiptNo < book_start || receiptNo > book_end){
                        is_alert_run = false;
                        document.getElementById(id_name).value = '';
                        alert("Receipt No must be between " + book_start + " and " + book_end + "");
                    }
                    else{
                        axios.get('axios_check_foreign_pass_rcpt_valid',{params:{'receiptNo':receiptNo,'book_name':book_name,'center_name':center_name}})
                        .then(function(res){
                            if(res.data.status == 'invalid'){
                                document.getElementById(id_name).value = '';
                                alert("This Receipt number already exists !");
                            };
                        })
                        .catch(function(error){
                            console.log('error');
                        });
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
                    var id_name = event.target.getAttribute('id');
                    var id_index = event.target.getAttribute('data-id');
                    
                    this.rejectModalShow = true;
                    this.rejectItem = [];
                    if (this.rejectItem == '') {
                        this.selectRejectAll = false;
                    }
                },
               

                clearAddMoreItemFunc: function (event) {
                    var id_name = event.target.getAttribute('id');
                    var id_index = event.target.getAttribute('data-id');
                    $('#webfile_p'+id_index).show();
                    $('#webfile_disable'+id_index).hide();
                    document.getElementById('webfile'+id_index).value = '';
                    document.getElementById('webfile_disable_value'+id_index).value = '';
                    document.getElementById('name'+id_index).value = '';
                    document.getElementById('passport_show'+id_index).innerText = '';
                    document.getElementById('contact'+id_index).value = '';
                    document.getElementById('passportNo'+id_index).value = '';
                    document.getElementById('paytype'+id_index).value = '';
                    document.getElementById('visa_type'+id_index).value = '';
                    document.getElementById('nationality'+id_index).value = '';
                    document.getElementById('duration'+id_index).value = '';
                    document.getElementById('entry_type'+id_index).value = '';
                    document.getElementById('visaFee'+id_index).value = '';
                    document.getElementById('faxCharge'+id_index).value = '';
                    document.getElementById('icwf'+id_index).value = '';
                    document.getElementById('appCharge'+id_index).value = '';
                    document.getElementById('total_fee'+id_index).value = '';
                    document.getElementById('total_fee_disable'+id_index).value = '';
                    document.getElementById('old_pass'+id_index).value = '';
                    document.getElementById('tddDelDateId'+id_index).innerText = '';
                    document.getElementById('tddDelDateValue'+id_index).value = '';
                    document.getElementById('cor_item_fee_value'+id_index).value = '';
                    document.getElementById('sp_fee'+id_index).value = '';
                    for(var j=0; j < this.correctionList.length; j++){
                        var single_select_all = document.getElementById("signle-cor-item"+id_index+j);
                        if(single_select_all.checked === true){
                            single_select_all.checked = false;
                        }
                    }
                },

                visaTypeOnChange: function (event) {
                    var id_name = event.target.getAttribute('id');
                    var id_index = event.target.getAttribute('data-id');
                    // console.log(id_name);
                    var visa_type = document.getElementById(id_name).value;
                    if (!visa_type == '') {
                        var tdd_date = document.getElementById('tdd_' + visa_type).value;
                        document.getElementById('tddDelDateId'+id_index).innerText = '( '+tdd_date+' )';
                        document.getElementById('tddDelDateValue'+id_index).value = tdd_date;
                        // this.ttdDelDate = tdd_date;
                        // console.log(tdd_date);
                        _this = this;
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
                        document.getElementById('passportNo'+id_index).value = passNo;
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

                            // if(DoubleValidStkrArr.length > 1){
                            //     var obj = {};
                            //     $(".doubleStkr").each(function(){
                            //         if(obj.hasOwnProperty(this.value)) {
                            //             if(is_alert_already ==  true){
                            //                 is_alert_already = false;
                            //                 this.not_all_data_valid = false;
                            //                 this.submitModalShow = false;
                            //                 $('#validStkr'+id_index).focus();
                            //                 alert("there is a duplicate value " + this.value);
                            //             }
                            //         } 
                            //         else {
                            //             obj[this.value] = this.value;
                            //         }
                            //     });
                            // }
                            if(validStkrF < inputTSt || validStkr2F > InputTE)
                            {   
                                if(is_alert_already ==  true){
                                    is_alert_already = false;
                                    this.not_all_data_valid = false;
                                    this.submitModalShow = false;
                                    $('#validStkr'+id_index).focus();
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
                                    $('#name'+id_index).focus();
                                    alert('Please Enter Valid Name '+id_index);
                                }
                            }
                            else if(contact_num.length != 10)
                            {   
                                if(is_alert_already ==  true){
                                    is_alert_already = false;
                                    this.not_all_data_valid = false;
                                    this.submitModalShow = false;
                                    $('#contact'+id_index).focus();
                                    alert('Please Enter Valid Contact Number '+id_index);
                                }
                            }
                            else if (nationality == '')
                            {   
                                if(is_alert_already ==  true){
                                    is_alert_already = false;
                                    this.not_all_data_valid = false;
                                    this.submitModalShow = false;
                                    $('#nationality'+id_index).focus();
                                    alert('Please Enter Valid Nationality '+id_index);
                                }
                            }
                            else if(visa_type == '')
                            {   
                                if(is_alert_already ==  true){
                                    is_alert_already = false;
                                    this.not_all_data_valid = false;
                                    this.submitModalShow = false;
                                    $('#visa_type'+id_index).focus();
                                    alert('Please Select Visa Type '+id_index);
                                }
                            }
                            else if(duration == '')
                            {   
                                if(is_alert_already ==  true){
                                    is_alert_already = false;
                                    this.not_all_data_valid = false;
                                    this.submitModalShow = false;
                                    $('#duration'+id_index).focus();
                                    alert('Please Enter Valid Duration '+id_index);
                                }
                            }
                            else if(entry_type == '')
                            {   
                                if(is_alert_already ==  true){
                                    is_alert_already = false;
                                    this.not_all_data_valid = false;
                                    this.submitModalShow = false;
                                    $('#entry_type'+id_index).focus();
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
                                        $('#visaFee'+id_index).focus();
                                        alert('Please Enter Visa Fee '+id_index);
                                    }
                                    else if(faxCharge == ''){
                                        is_alert_already = false;
                                        this.not_all_data_valid = false;
                                        this.submitModalShow = false;
                                        $('#faxCharge'+id_index).focus();
                                        alert('Please Enter Fax Charge '+id_index);
                                    }
                                    else if(icwf == ''){
                                        is_alert_already = false;
                                        this.not_all_data_valid = false;
                                        this.submitModalShow = false;
                                        $('#icwf'+id_index).focus();
                                        alert('Please Enter ICWF '+id_index);
                                    }
                                    
                                    else if(old_pass == ''){   
                                        if(is_alert_already ==  true){
                                            is_alert_already = false;
                                            this.not_all_data_valid = false;
                                            this.submitModalShow = false;
                                            $('#old_pass'+id_index).focus();
                                            alert('Please Enter Old Passport Qty '+id_index);
                                        }
                                    }
                                    else if(paytype == ''){   
                                        if(is_alert_already ==  true){
                                            is_alert_already = false;
                                            this.not_all_data_valid = false;
                                            this.submitModalShow = false;
                                            $('#paytype'+id_index).focus();
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
                                        $('#old_pass'+id_index).focus();
                                        alert('Please Enter Old Passport Qty '+id_index);
                                    }
                                }
                                else if(paytype == ''){   
                                    if(is_alert_already ==  true){
                                        is_alert_already = false;
                                        this.not_all_data_valid = false;
                                        this.submitModalShow = false;
                                        $('#paytype'+id_index).focus();
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
                            _this.submitModalShow = true;
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
                    var is_confirm = confirm('Are you sure! You want to delete/remove?');
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

                    axios.post('foreign-webfile-data-save-axios', objectData,this.webfilePreloader = true)
                        .then(function (res) {
                            _this.webfilePreloader = false;
                            _this.addMoreButtonArr = [];
                            // _this.addMoreBtn = 1;
                            // _this.addMoreButtonArr.push(this.addMoreBtn);
                            // _this.cleanBtn = false;
                            document.getElementById('book_rcvpt_no').value = '';
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
                axios.get('get_data_onload_axios').then(function(res){
                    _this.rejectCauseData = res.data.rejectCause;
                    _this.correctionList = res.data.correctionFee;
                    _this.correctionFee = res.data.corFee.corrFee;

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
