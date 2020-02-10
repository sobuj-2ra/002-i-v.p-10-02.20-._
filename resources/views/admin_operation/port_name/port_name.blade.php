@extends('admin.master')
<!--Page Title-->
@section('page-title')
  Port Name
@endsection

<!--Page Header-->
@section('page-header')
Manage Port Name
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
                          <div class="col-md-8">
                              &nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                                Add Port Name
                              </button>

                              <!-- Modal -->
                              <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                    <form method="POST" autocomplete="off" action="{{URL::to('operation/port-name')}}">
                                      {{csrf_field()}}
                                        <div class="modal-header">
                                          <span class="modal-title" id="exampleModalLabel">Add Port Name</span>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                          </button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="form_title_center">
                                          <b>-ADD PORT NAME-</b>
                                        </p>
                                          <div class="row">
                                            <div class="col-md-8">
                                              <div class="form-group">
                                                  <label for="correction"><b>Port Name:</b></label>
                                                  <input type="text" id="correction" class="form-control" name="port_name" required>
                                              </div>
                                            </div>
                                            <div class="col-md-4">
                                              <div class="form-group">
                                                <label for="amount"><b>Fee (BDT):</b></label>
                                                  <input type="number" id="amount" class="form-control" name="fee" required>
                                              </div>
                                            </div>
                                            <div class="col-md-12">
                                              <div class="form-group">
                                                <label for="service_type"><b>Service Type:</b></label>
                                                  <select name="service_type" id="service_type" class="form-control" required>
                                                    <option value=""></option>
                                                    <option value="Port Endorsement">Port Endorsement</option>
                                                    <option value="R.A.P/P.A.P">R.A.P/P.A.P</option>
                                                  </select>
                                              </div>
                                            </div>
                                            
                                          </div>
                                        </div>
                                        <div class="modal-footer">
                                          <button type="reset" class="btn btn-secondary" >Reset</button>
                                          <button type="submit" id="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </form>
                                  </div>
                                </div>
                              </div>
                          </div>
                          <div class="col-md-4">
                            <div class="flash-message-area">

                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            <h3 class="text-center">ALL PORT NAME </h3>
                            <div class="sanctioned-view-area" style="background: #FFF;margin:15px">
                              <table class="table table-responsive">
                                <thead>
                                  <tr>
                                    <th>Sl</th>
                                    <th>Port Name</th>
                                    <th>Fee</th>
                                    <th>Service Type</th>
                                    <th>Save Time</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                    if(count($portName) > 0){
                                      $i = 1;
                                      foreach ($portName as $Item) {
                                  ?>
                                      <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $Item->port_name; ?></td>
                                        <td><?php echo $Item->fee; ?></td>
                                        <td><?php echo $Item->service_type; ?></td>
                                        <td><?php echo Date('d-m-Y',strtotime($Item->save_time)); ?></td>
                                        <td><a onclick="return confirm('Are you sure! You want to Edit?')" class="btn btn-sm btn-primary" href="{{URL::to('/operation/port-name/edit').'/'.$Item->port_id}}"><i class="fa fa-edit"></i></a> <a onclick="return confirm('Are you sure! You want to Delete?')" class="btn btn-sm btn-danger" href="{{URL::to('/operation/port-name/destroy').'/'.$Item->port_id}}"><i class="fa fa-trash"></i></a></td>
                                      </tr>
                                  <?php
                                    $i++;
                                      }
                                  }
                                  ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                  </div>
              </div>
          </section>

    </div>
    <script src="https://rawgit.com/Eonasdan/bootstrap-datetimepicker/master/build/js/bootstrap-datetimepicker.min.js"></script>



    <script type="text/javascript">
      $(document).ready(function(){
           $('input.timepicker').timepicker({
              timeFormat: 'hh:',
              minTime: '11:45:00' // 11:45:00 AM,
              maxHour: 20,
              maxMinutes: 30,
              startTime: new Date(0,0,0,15,0,0) // 3:00:00 PM - noon
              interval: 15 // 15 minutes
          });
      });
    </script>



@endsection
