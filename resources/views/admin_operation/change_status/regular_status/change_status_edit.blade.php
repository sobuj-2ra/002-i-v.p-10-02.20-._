@extends('admin.master')
<!--Page Title-->
@section('page-title')
Regular Passport Edit Status
@endsection

<!--Page Header-->
@section('page-header')
Regular Passport Edit Status

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
                              <form method="POST" autocomplete="off" action="{{URL::to('operation/change-status/update')}}">
                              <div class="panel panel-info">
                                  {{csrf_field()}}
                                <div class="panel-heading ">
                                  <span class="modal-title" id="exampleModalLabel"></span>
                                </div>
                                <div class="panel-body">
                                  <p class="form_title_center">
                                  <b>-CHANGE STATUS-</b>
                                </p>

                                <div class="row">
                                  <div class="col-md-9">
                                    <table class="table table-responsive">
                                      <tbody>
                                        <th>Webfile</th>
                                        <th>Passport</th>
                                        <th>Phone</th>
                                        <th>Presence Status</th>
                                      </tbody>
                                      <tbody>
                                        <tr>
                                          <td>{{$webfileData->WebFile_no}}</td>
                                          <td>{{$webfileData->Passport}}</td>
                                          <td>{{$webfileData->Contact}}</td>
                                          <td>{{$webfileData->Presence_Status}}</td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>
                                  <div class="col-md-3">
                                    <div class="form-group">
                                      <label for="change_status"><b>Change Status:</b></label>
                                       <select class="form-control" name="present_status" id="change_status" required>
                                           <option value=""></option>
                                          @foreach ($resArr as $i=>$item)
                                            <option value="{{$item}}">{{$item}}</option>
                                          @endforeach
                                       </select>
                                    </div>
                                  </div>
                                  <input type="hidden" name="webfile" value="{{$webfileData->WebFile_no}}">
                                  <input type="hidden" name="old_status" value="{{$webfileData->Presence_Status}}">
                              </div>
                                <div class="panel-footer">
                                  <a href="{{URL::to('/operation/change-status')}}" class="btn btn-default">Back</a>&nbsp;&nbsp;&nbsp;
                                  <button type="submit" id="submit" class="btn btn-primary">Update</button>
                                </div>
                              </div>
                            </form>
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
                resDataArr:[],
            },
            methods:{
                presentStatusFunc:function(){
                    _this = this;
                    var present_status = $('#present_status').val();
                    axios.get('present_status_getdata',{params:{present_status:present_status}})
                    .then(function(res){
                        console.log(res)
                        _this.resDataArr = res.data.resArr
                    })
                    .catch(function(error){
                        console.log(error)
                    })
                }
            },
            created:function(){

            }
        });

    </script>
    <script type="text/javascript">

    </script>



@endsection
