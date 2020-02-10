@extends('admin.master')
<!--Page Title-->
@section('page-title')
Sync Maintenance
@endsection

<!--Page Header-->
@section('page-header')
Sync Maintenance

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

                            <div class="col-md-8 col-md-offset-2">
                              <!-- Modal -->
                              <form method="POST" autocomplete="off" action="{{URL::to('operation/sync-maintenance')}}">
                              <div class="panel panel-info">
                                  {{csrf_field()}}
                                <div class="panel-heading ">
                                  <span class="modal-title" id="exampleModalLabel"></span>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                                <label for="date_from">From:</label>
                                                <input type="text" class="form-control datepicker" name="date_from" id="date_from" value="<?php if(isset($from)){ echo $from;}else{echo Date('d-m-Y');} ?>">
                                        </div>
                                        <div class="col-md-5">
                                            <label for="webfile">Type:</label>
                                            <select name="sync_type" id="sync_type" class="form-control" required>
                                                <option value=""></option>
                                                <option value="Received" <?php if(@$type == 'Received'){ echo 'selected';}?>>Received</option>
                                                <option value="Ready@Center" <?php if(@$type == 'Ready@Center'){ echo 'selected';}?>>Ready@Center</option>
                                                <option value="Delivery" <?php if(@$type == 'Delivery'){ echo 'selected';}?>>Delivery</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="date_to">To:</label>
                                        <input type="text" class="form-control datepicker" name="date_to" id="date_to" value="<?php if(isset($to)){ echo $to;}else{echo Date('d-m-Y');} ?>">
                                        </div>
                                        <div class="col-md-5">
                                            <label for="webfile">Status:</label>
                                            <select name="sync_status" id="sync_type" class="form-control" required>
                                                <option value=""></option>
                                                <option value="Success" <?php if(@$status == 'Success'){ echo 'selected';}?>>Success</option>
                                                <option value="Pending" <?php if(@$status == 'Pending'){ echo 'selected';}?>>Pending</option>
                                                <option value="Failed" <?php if(@$status == 'Failed'){ echo 'selected';}?>>Failed</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <br>
                                            <input type="submit" value="Show" class=" btn btn-primary">
                                            @isset($servedData)
                                                @if(count($servedData) > 0)
                                                    <br>
                                                    <br>
                                                    <input onclick="return confirm('Are You sure! You want to Upload?')" type="submit" value="Upload Again" class="btn btn-info"  form="sync_data_form">
                                                @endif
                                            @endisset
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                  
                                </div>
                              </div>
                            </form>
                          </div>

                        <form id="sync_data_form"  method="post" action="{{URL::to('operation/sync-maintenance-update')}}">
                            <div class="col-md-12">
                              <!-- Modal -->
                              @isset($servedData)
                                <div class="panel panel-info">
                                    {{csrf_field()}}
                                    <div class="panel-heading ">
                                    <span class="modal-title" id="exampleModalLabel"></span>
                                    </div>
                                    <div class="panel-body">
                                        <table class="table table-responsive">
                                            <thead>
                                                <tr>
                                                    <th>Sl</th>
                                                    <th>Sevice Date</th>
                                                    <th>ServiceBy</th>
                                                    <th>Name</th>
                                                    <th>Webfile</th>
                                                    <th>Passport</th>
                                                    <th>Visa Type</th>
                                                    <th>Contact</th>
                                                    <th>Sticker</th>
                                                    <th>Status</th>
                                                    <th>Active</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @if(count($servedData) > 0)
                                                    @foreach ($servedData as $item)
                                                        <tr>
                                                            <td>{{$loop->iteration}}</td>
                                                            <td>
                                                                @if($type == 'Received')
                                                                    {{$item->Service_Date}}
                                                                @elseif($type == 'Ready@Center')
                                                                    {{$item->ReadyCentertime}}
                                                                @elseif($type == 'Delivery')
                                                                    {{$item->DelFinaltime}}
                                                                @endif
                                                            </td>
                                                            <td>{{$item->service_by}}</td>
                                                            <td>{{$item->Applicant_name}}</td>
                                                            <td>{{$item->WebFile_no}}</td>
                                                            <td>{{$item->Passport}}</td>
                                                            <td>{{$item->Visa_type}}</td>
                                                            <td>{{$item->Contact}}</td>
                                                            <td>{{$item->RoundSticker}}</td>
                                                            <td>{{$item->status}}</td>
                                                            <td>{{$item->active}}</td>
                                                        </tr>
                                                    <input type="hidden" name="h_id[]" value="{{$item->app_sl}}">
                                                    @endforeach
                                                    <input type="hidden" name="h_type" value="{{$type}}">
                                                @else
                                                <tr>
                                                    <td colspan="11"><h2 style="text-align:center;color:#ddd">No Data Found</h2></td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="panel-footer">
                                    </div>
                                </div>
                            @endisset
                          </div>
                        </form>
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
               
            },
            methods:{
               
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
