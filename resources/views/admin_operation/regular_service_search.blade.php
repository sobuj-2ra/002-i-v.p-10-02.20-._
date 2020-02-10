@extends('admin.master')
<!--Page Title-->
@section('page-title')
Regular Service Search
@endsection

<!--Page Header-->
@section('page-header')
Regular Service Search

@endsection

<!--Page Content Start Here-->
@section('page-content')
    <div id="app2">
          <section class="content">
              <div class="row">
                  <div class="col-md-12">
                      <div class="main_part gray-back" >
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-3">
                                <br>
                                @if(Session::has('msg'))
                                    <div class="alert alert-{{Session::get('status')}}">
                                        <span>{{Session::get('msg')}}</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="col-md-10 col-md-offset-1">
                                <!-- Modal -->
                                <div class="panel panel-info">
                                    {{csrf_field()}}
                                    <div class="panel-heading"  style="text-align:center">
                                        <span class="modal-title" id="exampleModalLabel"><b>{{$center_name->center_name}}</b></span>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="panel panel-info">
                                                    <div class="panel-body">
                                                        <h4 style="text-align:center;font-weight:bold;margin-top:0px;color:#444"><i>-Ready Center-</i></h4>
                                                        <hr>
                                                        <p>Uploaded: <b>{{$redUploaded}}</b></p>
                                                        <p>Pending: <b>{{$redPending}}</b></p>
                                                        <p>Failed: <b>{{$redFaild}}</b></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="panel panel-info">
                                                    <div class="panel-body">
                                                        <h4 style="text-align:center;font-weight:bold;margin-top:0px;color:#444"><i>-Delivery-</i></h4>
                                                        <hr>
                                                        <p>Uploaded: <b>{{$delUploaded}}</b></p>
                                                        <p>Pending: <b>{{$delPending}}</b></p>
                                                        <p>Failed: <b>{{$delFaild}}</b></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="panel panel-info">
                                                    <div class="panel-body">
                                                        <h4 style="text-align:center;font-weight:bold;margin-top:0px;color:#444"><i>-Receive-</i></h4>
                                                        <hr>
                                                        <p>Uploaded: <b>{{$rcvUploaded}}</b></p>
                                                        <p>Pending: <b>{{$rcvPending}}</b></p>
                                                        <p>Failed: <b>{{$rcvFaild}}</b></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-md-offset-1">
                                <!-- Modal -->
                                <form method="POST" autocomplete="off" action="{{URL::to('/operation/regular-service-search')}}">
                                    <div class="panel panel-info">
                                        {{csrf_field()}}
                                        <div class="panel-heading ">
                                            <span class="modal-title" id="exampleModalLabel"></span>
                                        </div>
                                        <div class="panel-body">
                                            <div class="input-group">
                                            <input type="text" id="service_search" name="service_search" value="{{@$s_data}}" class="form-control" placeholder="Webfile Or Passport">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default"  type="submit">Search</button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6">
                                @if(@$dataStatus)
                                    <div class="panel panel-info">
                                        {{csrf_field()}}
                                        <div class="panel-heading ">
                                            <span class="modal-title" id="exampleModalLabel"></span>
                                        </div>
                                        <div class="panel-body">
                                            @if($appData != '')
                                                <table class="table table-bordered table-responsive">
                                                    <tr>
                                                        <td>DATA COLLECTED FROM</td>
                                                        <th>{{$getTable}}</th>
                                                    </tr>
                                                    <tr>
                                                        <td>Applicant Name</td>
                                                        <th>{{$appData->Applicant_name}}</th>
                                                    </tr>
                                                    <tr>
                                                        <td>Webfile No</td>
                                                        <th>{{$appData->WebFile_no}}</th>
                                                    </tr>
                                                    <tr>
                                                        <td>Passport No</td>
                                                        <th>{{$appData->Passport}}</th>
                                                    </tr>
                                                    <tr>
                                                        <td>Presence Status</td>
                                                        <th>
                                                            @if($appData->status == 0)
                                                                {{'Uploaded'}}
                                                            @elseif($appData->status == 1 || $appData->status == 3 || $appData->status == 2 || $appData->status == 4)
                                                                {{'Pending'}}
                                                            @elseif($appData->status == 5 || $appData->status == 6)
                                                                {{'Failed'}}
                                                            @endif
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td>Visa Type</td>
                                                        <th>{{$appData->Visa_type}}</th>
                                                    </tr>
                                                    <tr>
                                                        <td>Visa Type</td>
                                                        <th>{{$appData->Visa_type}}</th>
                                                    </tr>
                                                    <tr>
                                                        @if($getTable == 'Appointment List')
                                                            <td>Appointment Date</td>
                                                            <th>{{$appData->Appointment_Date}}</th>
                                                        @else
                                                            <td>Service Date</td>
                                                            <th>{{$appData->Service_Date}}</th>
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        <td>Reject Reason</td>
                                                        <th>{{@$appData->RejectCause}}</th>
                                                    </tr>
                                                    <tr>
                                                        <td>Reject By</td>
                                                        <th>{{@$appData->RejectBy}}</th>
                                                    </tr>
                                                    <tr>
                                                        <td>Reject Time</td>
                                                        <th>{{@$appData->RejectTime}}</th>
                                                    </tr>
                                                </table>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    @if($searchData)
                                    <div class="panel panel-info">
                                        <div class="panel-heading ">
                                            <span class="modal-title" id="exampleModalLabel"></span>
                                        </div>
                                        <div class="panel-body">
                                            <table class="table table-bordered table-responsive">
                                                <tr>
                                                    <td colspan="2"><h3 style="color:#ddd;text-align:center">No Data Found</h3></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                      </div>
                  </div>
              </div>
          </section>

    </div>
    <script>

        var app = new Vue({
            el:"#app2",
            data:{
               is_appData:false,
               appData:[],
            },
            methods:{
                serviceDataSearchFunc:function(){
                    var _this = this;
                    var s_serach = $('#service_search').val();
                    if(s_serach == ''){
                        alert('Please Enter Webfile Or Passport Number');
                    }
                }
            },
            created:function(){

            }
        });

    </script>
    <script type="text/javascript">
        $( ".selector" ).datepicker({
            dateFormat: "yy-mm-dd"
        });
    </script>



@endsection
