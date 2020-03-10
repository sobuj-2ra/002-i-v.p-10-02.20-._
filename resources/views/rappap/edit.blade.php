@extends('admin.master')
<!--Page Title-->
@section('page-title')
    R.A.P. / P.A.P.
@endsection

<!--Page Header-->
@section('page-header')
    Edit R.A.P. / P.A.P.
@endsection

<!--Page Content Start Here-->
@section('page-content')
    <!--Calling Controller here-->
    @php use\App\Http\Controllers\rappapController; @endphp
    <div class="row" style="margin-left: 0px !important;margin-right: 0px !important; padding-top: 20px;padding-left:20px; padding-bottom: 20px">
        <div class="col-md-12">
            <div class="row" style="padding: 10px;margin-right: 0px;margin-left: 0px;">
                <div class="col-md-6">
                    @if (Session::has('message'))
                        <div class="alert alert-info">{{ Session::get('message') }}</div>
                    @endif
                </div>
            </div>
            <div class="row main_part">
                <div class="col-md-4" style="padding: 30px 30px 100px 30px">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Please Fill up the below field
                        </div>

                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    {!! Form::open(['url' => 'rap/pap/edit/action','id' => 'applicant_edit_form']) !!}
                                    <div class="form-group">
                                        <input class="form-control" name="PassportNo" placeholder="Enter Passport No"
                                               required="required">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Search</button>
                                    <button type="reset" class="btn btn-default">Reset</button>
                                    {!! Form::close() !!}
                                </div>
                            </div>

                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <br>
                    <a href="{{URL::to('/rap/pap/edit/search/passport')}}" style="padding-left: 16px"><button type="submit" class="btn btn-outline-info"> Refresh &nbsp;<i class="fa fa-refresh" aria-hidden="true"></i></button></a>
                </div>
                <div class="col-md-8"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            @php $edit_info = rappapController::edit_info($passport);
if(!empty($edit_info)){
       $common   = $edit_info['passport_info_common'];

       $entry_port = explode(',',$common[0]->OldPort);

       $area_info  = explode(' ', $common[0]->area);
       $exit_port  = explode(',', $common[0]->NewPort);
       $mode_info  = explode(',', $common[0]->mode);

       $edit_inf = $edit_info['passport_info'];

            @endphp
        </div>
    </div>

    @php if($flg=='passport_view') { @endphp
    {!! Form::open(['url' => "rap/pap/edit/save/$passport",'id' => 'applicant_edit_save_form']) !!}

    <input type="hidden" name="mpass" value="{{$passport}}">

    <input type="hidden" name="centerName" value="{{$center_name[0]->center_name}}">
    <input type="hidden" name="region" value="{{$center_name[0]->region}}">

    <div class="row" style="background: #fff; padding: 10px;margin-right: 0px;margin-left: 0px;">
        <div class="col-md-3">
            <input type="hidden" name="mpassport" id="mp4">
            <div class="form-group">
                <div class="routearea">
                    <label>Entry Port</label>
                    <div id="routeDiv">
                        <ul class="list-group" style="margin:0;padding: 0;">
                            @foreach ($routes as $routes_value)
                                <li style="list-style-type:none;padding:0;margin:0;">
                                    <label style="font-weight:normal;cursor: pointer;"> <input type="checkbox"
                                                                                               id="eport" name='eport[]'
                                                                                               value=" {{$routes_value->route_name}}"
                                                                                               @php if(in_array($routes_value->route_name, array_map("trim", $entry_port))) { @endphp checked @php } @endphp/> {{$routes_value->route_name}}
                                    </label>
                                </li>
                            @endforeach
                        </ul>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="routearea">
                <div class="form-group">
                    <label>Area</label>
                    <div id="areaDiv">
                        @foreach ($port as $port_value)
                            <ul class="list-group" style="margin:0;padding: 0;">
                                <li style="list-style-type:none;padding:0;margin:0;">
                                    <label style="font-weight:normal;cursor: pointer;"> <input type="checkbox"
                                                                                               id="areaport"
                                                                                               name="areaport[]"
                                                                                               @php if(in_array($port_value->port_name,  array_map("trim",$area_info))) { @endphp checked
                                                                                               @php } @endphp value="{{$port_value->port_name}}"/> {{$port_value->port_name}}
                                    </label>
                                </li>
                            </ul>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="routearea">
                <div class="form-group">
                    <label>Exit Port</label>
                    <div id="exitDiv">
                        @foreach ($routes as $routes_value)
                            <ul class="list-group" style="margin:0;padding: 0;">
                                <li style="list-style-type:none;padding:0;margin:0;">
                                    <label style="font-weight:normal;cursor: pointer;"> <input type="checkbox"
                                                                                               id="exitport"
                                                                                               name="exitport[]"
                                                                                               @php if(in_array($routes_value->route_name,  array_map("trim",$exit_port))) { @endphp checked
                                                                                               @php } @endphp value=" {{$routes_value->route_name}}"/> {{$routes_value->route_name}}
                                    </label>
                                </li>
                            </ul>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="routearea">
                <div class="form-group">
                    <label>Mode</label>
                    <div id="modeDiv">
                        @php $i=0; @endphp

                        <ul class="list-group" style="margin:0;padding: 0;">
                            @foreach ($mode as $mode_value)
                                <li style="list-style-type:none;padding:0;margin:0;">
                                    <label style="font-weight:normal;cursor: pointer;"><input type="checkbox"
                                                                                              id="modeid"
                                                                                              name="mode_val[]"
                                                                                              @php if(in_array($mode_value->mode, array_map("trim",$mode_info))) { @endphp checked
                                                                                              @php } @endphp value="{{$mode_value->mode}}"/> {{$mode_value->mode}}
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="background: #fff; padding: 10px;margin-right: 0px;margin-left: 0px;">
        <div class="col-sm-6">
            <div class="row">
                <div class="col-sm-6"><b>Arrival Date</b> <br/><input type="text" name="arrivalDate" id="dt"
                                                                      class="form-control margintop5"
                                                                      onchange="mydate1();" required="required"
                                                                      value="{{$common[0]->arrivalDate}}"/></div>
                <div class="col-sm-6"><b>Departure Date</b><br/> <input type="text" name="derpartureDate"
                                                                        value="{{$common[0]->departureDate}}" id="dt"
                                                                        class="form-control margintop5"
                                                                        required="required" onchange="mydate2();"></div>
            </div>
        </div>
    </div>


    @foreach($edit_inf as $e_val)
        <div class="control-group margintop15" id="q1" >
            <div class="row" style="background: #fff; padding: 10px;margin-right: 0px;margin-left: 0px;">
                <div class="col-md-2">
                    <div style="float:left;width:42px;margin-top:5px;"><label style="font-weight:normal;"> <input
                                    type="radio" id="dr{{$e_val->serial_no}}"
                                    @php if($e_val->MasterPP==$e_val->passport) { @endphp checked="checked"
                                    @php } @endphp  name="master_passport"> M.P</label></div>
                    <div style="float:right;width:100px;"><input type="text" id="v{{$e_val->serial_no}}"
                                                                 name="passportNo[]" class="form-control mpass_val"
                                                                 value="{{$e_val->passport}}" required="required"
                                                                 placeholder="P.P. No"></div>
                </div>
                <div class="col-md-1" style="padding:0;">
                    <input type="text" name="visa_no[]" value="{{$e_val->visa_no}}" required="required"
                           class="form-control vsano" placeholder="Visa No">
                </div>
                <input type="hidden" name="id_serial_no" value="{{$e_val->serial_no}}">
                <div class="col-md-2">
                    <div style="float:left;"><input type="text" required="required" style="width:70px;"
                                                    name="stickerNo[]" class="form-control stck_no_chk"
                                                    value="{{$e_val->sticker}}" placeholder="Stic.No"></div>
                    <div style="float:right;"><input style="width:70px;" type="text" name="fee[]" class="form-control"
                                                     placeholder="Fee" value="{{$e_val->Fee}}" required="required">
                    </div>
                </div>
                <div class="col-md-3" style="padding:0;">
                    <div style="float:left;width:148px;"><label style="font-weight:normal;">
                            <input class="form-control" type="text" name="applicant_name[]" placeholder="Applicant Name"
                                   required="required" value="{{$e_val->applicant_name}}">
                    </div>
                    <div style="float:right;width:105px;">
                        <select class="form-control" name="designation[]" required="required">
                            <option value="">Des/Res</option>
                            @foreach ($designation as $desig_value)
                                <option @php if($e_val->designation==$desig_value->designation) { @endphp selected="selected"
                                        @php } @endphp value="{{$desig_value->designation}}">{{$desig_value->designation}}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select class="form-control" name="visaType[]">
                            <option value="">-- Visa Type --</option>
                            @foreach($visatype as $visatype_value)
                                <option @php if($visatype_value->visa_type==$e_val->visa_type) { @endphp selected="selected"
                                        @php } @endphp  value="{{$visatype_value->visa_type}}">{{$visatype_value->visa_type}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2" style="padding-left:0;">
                    <div style="float: left;width: 120px;"><input type="text" pattern=".{10}" title="Minimum  & Maximum 10 digit" required="required" name="contactNo[]"
                                                                  class="form-control contact_valid"
                                                                  value="{{$e_val->contact}}"
                                                                  placeholder="Contact (1XXXXXXXXX)"><span
                                style="margin-top: 10px;float:right;" class="btn btn-primary"
                                onclick="printDiv('printableArea @php echo $e_val->serial_no;@endphp')">Print</span>
                    </div>@php if($e_val->MasterPP!==$e_val->passport) { @endphp <a title="Delete"
                                                                                    href='{{url("/rap/pap/delete/$e_val->serial_no/$passport")}}'
                                                                                    style="color:red;text-decoration: none;"
                                                                                    onclick="return confirm('Are you sure to Delete ?');">
                        <div style="float: right;width: 20px;color:red;text-align: center;margin-top: 6px;border:1px solid red;margin-left: 5px;">
                            X
                        </div>
                    </a>@php } @endphp </div>

            </div>
        </div>

        <style>
            /*Print Slip*/


            @media print {
                .noprint {
                    display: none;
                }

                #printableArea {
                    display: block !important;
                    font-size: 11px !important;
                }

                .topborder {
                    width: 100%;
                    border-top: 2px solid #000;
                    margin: 5px 0;
                }

                .topmargin {
                    margin-top: 0px;
                }

                .topmargin:first-child {
                    margin-top: 0px;
                }

                .tclass {
                    font-size: 11px !important;
                }

                .centerdiv {
                    padding: 5px 0;
                }

                .centerdiv2 {
                    margin-bottom: 5px;
                }

                table { /* Or specify a table class */
                    overflow: hidden;
                    page-break-after: always;
                    font-size: 11px !important;

                }

            }

        </style>


        @php if(!empty(rappapController::slip_print($e_val->serial_no))) { @endphp
        @foreach ( rappapController::slip_print($e_val->serial_no) as $val)

            <div id="printableArea {{$val->serial_no}}" style="display: none;">

                <table border="0" width="100%" style="padding: 0; font-size: 11px;">
                    <tr>
                        <td colspan="2" class=text-center><strong>Indian Visa Application Center </strong></td>
                    </tr>
                    <tr>
                        <td colspan="2" class=text-center>
                            <div class="centerdiv">{{$val->center_name}}</div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class=text-center>
                            <div class="centerdiv2">R.A.P./P.A.P. Application</div>
                        </td>
                    </tr>
                    <tr>
                        <td>@php echo $now = date('d-M-Y'); @endphp</td>
                        <td><span class=pull-right>@php echo date("h:i:s A"); @endphp</span></td>
                    </tr>
                    <tr>
                        <td><hr style="font-weight: bold;color: #000;margin: 0px !important;border: 1px solid #000;"></td>
                        <td><hr style="font-weight: bold;color: #000;margin: 0px !important;border: 1px solid #000;"></td>
                    </tr>
                    <tr>
                        <td colspan=2 class="text-center">
                            <div class="centerdiv2">
                                <center><svg id="bar_id"></svg></center>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style='margin: 105px 0'>
                            <strong>Name:</strong> {{ $val->applicant_name}} <br/>
                            <strong>Contact:</strong> {{ $val->contact}} <br/>
                            <strong>Passport:</strong> {{ $val->passport}} <br/>
                            <strong>Visa No:</strong> {{ $val->visa_no}} <br/>
                            <strong>Visa Type:</strong> {{ $val->visa_type}} <br/>
                            <strong>Entry Port:</strong> {{ $val->OldPort}} <br/>
                            <strong>Exit Port:</strong> {{ $val->NewPort}} <br/>
                            <strong>Area:</strong> {{ $val->area}} <br/>
                            <strong>Mode:</strong> {{ $val->mode}} <br/>
                            <strong>Sticker:</strong> {{ $val->sticker}} <br/>
                            <strong>Fee:</strong> {{ $val->Fee}} <br/>
                            <strong>Info:</strong> {{$val->center_info}} <br/>
                            <?php //$c_date = date('Y-m-d', strtotime($val->created_at));  ?>
                            <strong>Delivery on or
                                after:</strong> {{date('d-m-Y', strtotime($val->tdd))}} {{$val->del_time}}

                        </td>
                    </tr>
                    <tr>
                        <td><hr style="font-weight: bold;color: #000;margin: 0px !important;border: 1px solid #000;"></td>
                        <td><hr style="font-weight: bold;color: #000;margin: 0px !important;border: 1px solid #000;"></td>
                    </tr>
                    <tr>
                        <td>{{$val->center_web}}</td>
                        <td><span class=pull-right></span></td>
                    </tr>
                </table>
                <script>
                    JsBarcode("#bar_id", "<?php echo $val->passport; ?>", {
                            height: 25,
                            width: 1.5,
                            margin: 10,
                            fontSize: 11,
                        }
                    );
                </script>
                <br/><br/>
                <table border="0" width="100%" style="padding: 0;font-size: 11px">
                    <tr>
                        <td colspan="2" class=text-center><strong>Indian Visa Application Center</strong></td>
                    </tr>
                    <tr>
                        <td colspan="2" class=text-center>
                            <div class="centerdiv">{{$val->center_name}}</div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class=text-center>
                            <div class="centerdiv2">R.A.P./P.A.P. Application</div>
                        </td>
                    </tr>
                    <tr>
                        <td>@php echo $now = date('d-M-Y'); @endphp</td>
                        <td><span class=pull-right>@php echo date("h:i:s A"); @endphp</span></td>
                    </tr>
                    <tr>
                        <td><hr style="font-weight: bold;color: #000;margin: 0px !important;border: 1px solid #000;"></td>
                        <td><hr style="font-weight: bold;color: #000;margin: 0px !important;border: 1px solid #000;"></td>
                    </tr>
                    <tr>
                        <td colspan=2 class="text-center">
                            <div class="centerdiv2">
                                <center><svg id="bar_id"></svg></center>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style='margin: 105px 0'>
                            <div class="topborder"></div>
                            <strong>Name:</strong> {{ $val->applicant_name}} <br/>
                            <strong>Contact:</strong> {{ $val->contact}} <br/>
                            <strong>Passport:</strong> {{ $val->passport}} <br/>
                            <strong>Visa No:</strong> {{ $val->visa_no}} <br/>
                            <strong>Visa Type:</strong> {{ $val->visa_type}} <br/>
                            <strong>Entry Port:</strong> {{ $val->OldPort}} <br/>
                            <strong>Exit Port:</strong> {{ $val->NewPort}} <br/>
                            <strong>Area:</strong> {{ $val->area}} <br/>
                            <strong>Mode:</strong> {{ $val->mode}} <br/>
                            <strong>Sticker:</strong> {{ $val->sticker}} <br/>
                            <strong>Fee:</strong> {{ $val->Fee}} <br/>
                            <strong>Info:</strong> {{$val->center_info}} <br/>
                            <strong>Delivery on or
                                after:</strong> {{date('d-m-Y', strtotime($val->tdd))}} {{$val->del_time}}

                        </td>
                    </tr>
                    <tr>
                        <td><hr style="font-weight: bold;color: #000;margin: 0px !important;border: 1px solid #000;"></td>
                        <td><hr style="font-weight: bold;color: #000;margin: 0px !important;border: 1px solid #000;"></td>
                    </tr>
                    <tr>
                        <td>{{$val->center_web}}</td>
                        <td><span class=pull-right></span></td>
                    </tr>
                </table>
                <script>
                    JsBarcode("#bar_id", "<?php echo $val->passport; ?>", {
                            height: 25,
                            width: 1.5,
                            margin: 10,
                            fontSize: 11,
                        }
                    );
                </script>
            </div>
        @endforeach
        @php } @endphp


        <script type="text/javascript">
            $(document).ready(function () {
                $("#dr@php echo $e_val->serial_no; @endphp").click(function () {
                    var mp = $("#v{{$e_val->serial_no}}").val();
                    $("#mp4").val(mp);

                });
            });
        </script>

    @endforeach

    <div class="addmorepart">

        <div class="control-group after-add-more margintop5" id="q1">
            <div class="row" style="background: #fff; padding: 10px;margin-right: 0px;margin-left: 0px;">
                <div class="col-md-2">
                    <input type="text" id="v1" name="passportNo[]" class="form-control mpass_val" placeholder="P.P. No">
                </div>
                <div class="col-md-1" style="padding:0;">
                    <input type="text" name="visa_no[]" class="form-control vsano" placeholder="Visa No">
                </div>
                <div class="col-md-2">
                    <div style="float:left;"><input type="text" style="width:70px;" name="stickerNo[]"
                                                    class="form-control stck_no_chk" placeholder="Stic.No"></div>
                    <div style="float:right;"><input style="width:70px;" type="text" name="fee[]" class="form-control"
                                                     placeholder="Fee" value="{{$fee[0]->Svc_Fee}}"></div>
                </div>
                <div class="col-md-3" style="padding:0;">
                    <div style="float:left;width:148px;"><label style="font-weight:normal;">
                            <input class="form-control" type="text" name="applicant_name[]"
                                   placeholder="Applicant Name">
                    </div>
                    <div style="float:right;width:105px;">
                        <select class="form-control" name="designation[]">
                            <option value="">Des/Res</option>
                            @foreach ($designation as $desig_value)
                                <option value="{{$desig_value->designation}}">{{$desig_value->designation}}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select class="form-control" name="visaType[]">
                            <option value="">-- Visa Type --</option>
                            @foreach($visatype as $visatype_value)
                                <option @php if($visatype_value->visa_type=='TOURIST') { @endphp selected="selected"
                                        @php } @endphp  value="{{$visatype_value->visa_type}}">{{$visatype_value->visa_type}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2" style="padding-left:0;"><input type="text" pattern=".{10}" title="Minimum  & Maximum 10 digit" name="contactNo[]"
                                                                     class="form-control contactvalid"
                                                                     placeholder="Contact (1XXXXXXXXX)"></div>
            </div>
            <div class="row" style="background: #fff; padding: 10px;margin-right: 0px;margin-left: 0px;">
                <!--<div class="col-md-2"></div>-->
                <div class="col-sm-2" style="float:right;text-align: right;">
                    <div class="input-group-btn">
                        <button class="btn btn-success add-more" type="button" style="border-radius:4px;"><i
                                    class="glyphicon glyphicon-plus"></i>Add
                        </button>
                    </div>
                </div>
            </div>

        </div>


        <div class="copy hide" style="clear:both;">
            <div class="control-group input-group" id="temp"
                 style="border-top:1px dashed #ccc;clear:both; background-color: #fff;width: 100%;">
                <div class="row" style="margin-top:15px; background: #fff; padding: 10px;margin-right: 0px;margin-left: 0px;">
                    <div class="col-md-2">
                        <input type="text" name="passportNo[]" id="nID2" class="form-control mpass_val"
                               placeholder="P.P. No">
                    </div>
                    <div class="col-md-1" style="padding:0;">
                        <div class="form-group"><input type="text" id="vid" name="visa_no[]" class="form-control vsano"
                                                       placeholder="Visa No"></div>
                    </div>
                    <div class="col-md-2">
                        <div style="float:left;"><input type="text" id="stid" style="width:70px;" name="stickerNo[]"
                                                        class="form-control stck_no_chk" placeholder="Stic.No"></div>
                        <div style="float:right;"><input style="width:70px;" type="text" id="feeID" name="fee[]"
                                                         value="{{$fee[0]->Svc_Fee}}" class="form-control"
                                                         placeholder="Fee"></div>
                    </div>
                    <div class="col-md-3" style="padding:0;">

                        <div style="float:left;width:148px;"><label style="font-weight:normal;">
                                <input class="form-control" type="text" id="appliD" name="applicant_name[]"
                                       placeholder="Applicant Name">
                        </div>
                        <div style="float:right;width:105px;">
                            <select class="form-control" id="desigId" name="designation[]">
                                <option value="">Des/Res</option>
                                @foreach ($designation as $desig_value)
                                    <option value="{{$desig_value->designation}}">{{$desig_value->designation}}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <select class="form-control" id="vtypeID" name="visaType[]">
                                <option value="">-- Visa Type --</option>
                                @foreach($visatype as $visatype_value)
                                    <option @php if($visatype_value->visa_type=='TOURIST') { @endphp selected="selected"
                                            @php } @endphp  value="{{$visatype_value->visa_type}}">{{$visatype_value->visa_type}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2" style="padding-left:0;"><input id="contID" pattern=".{10}" title="Minimum  & Maximum 10 digit" type="text" name="contactNo[]"
                                                                         class="form-control contactvalid"
                                                                         placeholder="Contact (1XXXXXXXXX)">

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group-btn" style="text-align: right;">
                            <button class="btn btn-danger remove margintop15" type="button" style="border-radius:4px;">
                                <i class="glyphicon glyphicon-remove"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>






    <div class="row" style="background: #fff; padding: 10px;margin-right: 0px;margin-left: 0px;">
        <div class="col-sm-6">
            <div class="row">
                <div class="col-sm-6"><input class="form-control" type="text" name="remarks"
                                             value="{{$common[0]->Remarks}}" placeholder="Remarks"/></div>
            </div>
        </div>

        <div class="row" style="text-align:right;">
            <div class="col-md-12">
                <button type="submit" class="btn btn-success">Update</button>&nbsp;

            </div>


        </div>

    {!! Form::close() !!}

    @php } @endphp
    @php } @endphp

    @endsection
    <!--Page Content End Here-->


        @section('page-script')
            <script type="text/javascript">


                function printDiv(pa) {
                    // document.getElementById("printableArea").style.display = "block";

                    var printContents = document.getElementById(pa).innerHTML;
                    // var originalContents = document.body.innerHTML;

                    document.body.innerHTML = printContents;

                    window.print();

                    // document.body.innerHTML = originalContents;
                }


                $(document).ready(function () {


                    /*Contact No Validation Start here*/
                    function validate_Phone_Number(getinput) {

                        var contact_number = getinput;
                        for (var i = 0; i < contact_number.length; i++) {

                            var reqularExp = /^[1-9]\d{9}$|^[1-9]\d{9}$/g;

                            var cont = contact_number[i];

                            var matchval = cont.match(reqularExp);
                        }
                        return matchval;
                    }

                    /*Contact No Validation End here*/

                    /*Form Submit*/

// $("#applicant_edit_save_form").submit(function(event){

// /*Contact No Validation Start*/
// contactNo=[];
// $(".contactvalid").each(function(){
//     var value = $(this).val();
//     contactNo.push(value);
// });

// var contact_list=contactNo.slice(0, -1);

// alert(contact_list);

// if(contact_list.length>0)
// {
// var contact=validate_Phone_Number(contact_list);

// if(!contact)
// {
//   alert('Check your contact no');
//   return false;
// }
// }


// alert('This submit');
// return false;

// });


                    $(".add-more").click(function () {
                        var html = $(".copy").html();
                        $(".after-add-more").append(html);
                    });
                    $("body").on("click", ".remove", function () {

                        $(this).parents(".input-group").remove();
                    });
                });

            </script>
            <script>
                //window.onload = printDiv('printableArea');
                $(window).on('afterprint', function () {
                    window.location.href="{{ url("/rap/pap/edit/search/passport") }}";
                });
            </script>
@endsection