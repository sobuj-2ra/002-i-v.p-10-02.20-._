@extends('admin.master')
<!--Page Title-->
@section('page-title')
Foreign Passport
@endsection

<!--Page Header-->
@section('page-header')
<span style="color:#1a8e46">Foreign Passport</span>

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
                          <div class="col-md-6 col-md-offset-3">


                              <!-- Modal -->
                              <form method="POST" autocomplete="off" action="{{URL::to('operation/change-status/foreign/edit')}}">
                              <div class="panel panel-info">
                                  {{csrf_field()}}
                                <div class="panel-heading ">
                                  <span class="modal-title" id="exampleModalLabel"></span>
                                </div>
                                <div class="panel-body" style="background:#c7c7c7;">
                                  <p class="form_title_center">
                                  <b style="color:#1a8e46">-STATUS CHANGE-</b>
                                </p>
                                  <div class="row">
                                    <div class="col-md-6 col-md-offset-3">
                                      <div class="form-group">
                                          <label for="webfile">Enter Webfile:</label>
                                          <input type="text" class="form-control" name="webfile">
                                      </div>
                                      <div class="form-group">
                                        <button type="reset" class="btn btn-secondary" >Reset</button>
                                        <input style="background:#1a8e46;border:1px solid #1a8e46" type="submit" value="Search" class="btn btn-primary pull-right">
                                      </div>
                                    </div>
                                </div>
                                </div>
                                <div class="panel-footer">
                                  
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
