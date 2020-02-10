@extends('admin.master')
<!--Page Title-->
@section('page-title')
   Queue Service
@endsection

<!--Page Header-->
@section('page-header')
Queue Service

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
                              <form method="POST" autocomplete="off" action="{{URL::to('/operation/visa-requirement/update')}}">
                              <div class="panel panel-info">
                                  {{csrf_field()}}
                                <div class="panel-heading ">
                                  <span class="modal-title" id="exampleModalLabel"></span>
                                </div>
                                <div class="panel-body">
                                  <p class="form_title_center">
                                    <b>-UPDATE QUEUE SERVICE-</b>
                                  </p>
                                  <div class="row">
                                    <div class="col-md-10 col-md-offset-1">
                                      <div class="form-group">
                                        <label for="username"><b>Visa Type:</b></label>
                                          <select class="form-control" name="visa_type" required>
                                            <option value=""></option>
                                            @foreach ($visaType as $item)
                                              @if($item->visa_type == $visaCheck->Visa_type)
                                                <option value="{{$item->visa_type}}" selected>{{$item->visa_type}}</option>
                                              @else
                                                <option value="{{$item->visa_type}}">{{$item->visa_type}}</option>
                                              @endif
                                            @endforeach
                                          </select>
                                      </div>
                                    </div>
                                    <div class="col-md-10 col-md-offset-1">
                                      <div class="form-group">
                                        <label for="username"><b>Check Point:</b></label>
                                      <input type="text" class="form-control" name="check_point" value="{{$visaCheck->parameter}}" required>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="panel-footer">
                                  <input type="hidden" name="update_id" value="{{$visaCheck->Sl}}">
                                  <button type="reset" class="btn btn-secondary" >Reset</button>
                                  <button type="submit" id="submit" class="btn btn-info">Update</button>
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

            },
            methods:{

            },
            created:function(){

            }
        });

    </script>
    <script type="text/javascript">

    </script>



@endsection
