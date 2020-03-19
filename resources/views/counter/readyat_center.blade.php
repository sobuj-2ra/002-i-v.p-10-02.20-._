@extends('admin.master')
<!--Page Title-->
@section('page-title')
    Ready At Center
@endsection

<!--Page Header-->
@section('page-header')
Ready at Center

@endsection

<!--Page Content Start Here-->
@section('page-content')
<style>
    #passfieldId{
        margin-bottom:10px;
        height: 30px;
        width: 190px;
        border-radius: 5px;
        border: 1px solid #777;
        padding: 0px 5px;
    }
    #remark_field_id{
        margin-bottom:10px;
        height: 30px;
        width: 250px;
        border-radius: 5px;
        border: 1px solid #777;
        padding: 0px 5px;
    }
</style>
    @php
        $curDate = Date('d-m-Y');
    @endphp
    <div id="app1">
        <section class="content ">
            <div class="row">
                <div class="col-md-12">
                    <div class="main_part countercall-area" >
                        @if(Session::has('message'))
                            <div class="row">
                                <div class="col-md-4 col-md-offset-4 alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</div>
                            </div>
                        @endif
                    <!-- Code Here.... -->

                        <div class="row">
                            <div class="col-md-12">
                                <div v-show="webfilePreloader" class="webfile-preloader"><img class="preloader" src="{{asset("public/assets/img/preloader.gif")}}" alt=""></div>

                                <div class="col-md-8 col-md-offset-2">
                                    <div class="readyat-data-info-area">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                <span>Select Date: <input name="selected_date" id="selected_date" class="datepicker " style="width:150px;color:black" type="text" value="{{$curDate}}"></span>
                                                <div class="float-right">
                                                    <span>Total Saved: <b>@{{ total_save }}</b></span> &nbsp;&nbsp;&nbsp;&nbsp;
                                                    <span>Total Data Not Found: <b>@{{ total_failed}}</b></span><br>
                                                </div>
                                                
                                            </div>
                                            <div class="panel-body">
                                                <div class="readyat-top-left">

                                                    Passport: <input @keyup.enter="passportEnterFunc" v-model="passInputVal" id="passfieldId" type="text"  placeholder="Press Enter"> <input @click="remarkParmitFunc" v-model="remarkParmitMod" type="checkbox"> <input @keyup.enter="passportEnterFunc" v-model="remarkInputVal" v-if="remarkParmitMod" type="text" id="remark_field_id" placeholder="Remarks ">
                                                    
                                                </div>
                                            </div>
                                            <div class="panel-footer">
                                                <span v-if="resMsgSuccess" style="color:green;font-weight:bold;text-align:center">@{{resMsg}}</span>
                                                <span v-if="resMsgFaild" style="color:red;font-weight:bold;text-align:center">@{{resMsg}}</span>
                                            </div>
                                        </div>
                                        {{--{{Hash::make('password')}}--}}
                                        
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
        @php
            $url = "url::()";
        @endphp
    <script>
        $( ".selector" ).datepicker({
            dateFormat: "yy-mm-dd"
        });
    </script>
    <script>

        var app = new Vue({
            el:'#app1',
            data:{
                webfilePreloader:false,
                remarkParmitMod:false,
                remarkInputVal: '',
                passInputVal: '',
                total_save:0,
                total_failed:0,
                resMsgSuccess:false,
                resMsgFaild:false,
                resMsg:'',

            },
            methods:{
                remarkParmitFunc: function(){
                    this.remarkParmitMod = true;
                    $('#remark_field_id').focus();
                    this.remarkInputVal = '';
                },
                passportEnterFunc: function(){
                    
                    var InVal = this.passInputVal.toUpperCase()
                    this.passInputVal = InVal.split(' ').join('');
                    this.remarkInputVal = this.remarkInputVal.toUpperCase()
                    var cuVal = this.passInputVal;
                    var selected_date = document.getElementById('selected_date').value;
                    // console.log(cuVal.length);
                    if(cuVal == ''){
                        $('#passfieldId').focus();
                        alert('Please Input Passport No.');
                    }
                    else if(cuVal.length <= 3){
                        $('#passfieldId').focus();
                        alert('Please Input Valid Passport No.');
                    }
                    else if(this.remarkParmitMod == true && this.remarkInputVal == ''){
                        // alert('sdfsd');
                        this.remarkParmitMod = true;
                        $('#remark_field_id').focus();
                    }
                    else{
                       var passport = this.passInputVal;
                       var remark = this.remarkInputVal;
                       this.webfilePreloader = true;
                       _this = this;
                        axios.post('ready-center-passport-datas',{passport:passport,remark:remark,selected_date:selected_date})
                            .then(function(res){
                                console.log(res);
                                _this.webfilePreloader = false;
                                _this.total_save = res.data.total_save;
                                _this.total_failed = res.data.total_fail;
                                _this.remarkParmitMod = false;
                                _this.remarkInputVal = '';
                                _this.passInputVal = '';
                                $('#passfieldId').focus();
                                _this.resMsg = res.data.statusMsg;
                                if(res.data.status == 'yes'){
                                    _this.resMsgSuccess = true;
                                    _this.resMsgFaild = false;
                                }
                                else{
                                    _this.resMsgFaild = true;
                                    _this.resMsgSuccess = false;
                                }
                            })
                            .catch(function(error){
                                console.log(error);
                                _this.webfilePreloader = false;
                                _this.resMsg = 'OPPS! SERVER ERROR';
                                _this.resMsgFaild = true;
                                _this.resMsgSuccess = false;
                            })
                            
                        $('#passfieldId').focus();
                        // this.remarkParmitMod = false;
                        // this.checkDouble = false;
                        // this.remarkInputVal = '';
                        // this.passInputVal = '';
                    }
                   

                },
                clearDataFunc: function()
                {
                    if(this.totalCount > 0)
                    {
                        var sure = confirm('Are you sure! You want to clear?');
                        if(sure)
                        {
                                this.dataArr = [];
                                this.readyatArr = [];
                                this.totalCount = 0;
                                this.remarkParmitMod = false;
                                this.checkDouble = false;
                                this.remarkInputVal = '';
                                this.passInputVal = '';
                        }
                    }
                    else{
                        alert('Opps! Nothing to clear');
                    }
                },
                removeItemFunc:function(index){
                    this.readyatArr.splice(index,1)
                },
                passReadySubFunc: function()
                {
                    var _this = this
//                    var data = this.readyatArr.serialize()
////                    console.log(data);
//                    const myObjStr = JSON.stringify(this.readyatArr);
//                    console.log(myObjStr)
                    var objectData = $('.input_values').serialize();
//                    console.log(objectData);
//                    $.post({
//                        type:"post"
//                    })
//                    $.post('ready-center-passport-datas',objectData,function(res){
////                        _this.webfilePreloader = false;
//                        _this.total_save = res.total_save;
//                        _this.total_failed = res.total_fail;
//                        _this.dataArr = [];
//                        _this.readyatArr = [];
//                        _this.totalCount = 0;
//                        _this.remarkParmitMod = false;
//                        _this.checkDouble = false;
//                        _this.remarkInputVal = '';
//                        _this.passInputVal = '';
//
//                    });
                    if(this.totalCount > 0){
                        axios.post('ready-center-passport-datas',objectData,this.webfilePreloader = true)
                            .then(function(res){
                                _this.webfilePreloader = false;
                                _this.total_save = res.data.total_save;
                                _this.total_failed = res.data.total_fail;
                                _this.dataArr = [];
                                _this.readyatArr = [];
                                _this.totalCount = 0;
                                _this.remarkParmitMod = false;
                               _this.checkDouble = false;
                                _this.remarkInputVal = '';
                                _this.passInputVal = '';
                                _this.recentFData = res.data.reject;
                                document.getElementById('rec_save').innerText = res.data.rec_save;
                                document.getElementById('rec_fail').innerText = res.data.rec_fail;
                                document.getElementById('rec_fail2').innerText = res.data.rec_fail;
                            })
                            .catch(function(error){
                                console.log(res);
                                _this.webfilePreloader = false;
                            })

                    }else{
                        alert('Empty Passport Number')
                    }
                }


            },
            created:function() {
                var _this = this;
                $('#passfieldId').focus();
                axios.get('onload-readyat-center-datas',{params:{}})
                    .then(function (res) {
                        _this.webfilePreloader = false;
                        _this.total_save = res.data.total_save;
                        _this.total_failed = res.data.total_fail;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            }
        });

    </script>

@endsection
