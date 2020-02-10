@extends('admin.master')
<!--Page Title-->
@section('page-title')
   Visa Requirement
@endsection

<!--Page Header-->
@section('page-header')
Visa Requirement
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
                                Add Visa Requirement
                              </button>

                              <!-- Modal -->
                              <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                    <form method="POST" autocomplete="off" action="{{URL::to('operation/visa-requirement')}}">
                                      {{csrf_field()}}
                                        <div class="modal-header">
                                          <span class="modal-title" id="exampleModalLabel">Add Visa Requirement</span>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                          </button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="form_title_center">
                                          <b>-ADD VISA REQUIREMENT-</b>
                                        </p>
                                          <div class="row">
                                            <div class="col-md-10 col-md-offset-1">
                                              <div class="form-group">
                                                <label for="username"><b>Visa Type:</b></label>
                                                  <select class="form-control" name="visa_type" required>
                                                    <option value=""></option>
                                                    @foreach ($visaType as $item)
                                                      <option value="{{$item->visa_type}}">{{$item->visa_type}}</option>
                                                    @endforeach
                                                  </select>
                                              </div>
                                            </div>
                                            <div class="col-md-10 col-md-offset-1">
                                              <div class="form-group">
                                                <label for="username"><b>Check Point:</b></label>
                                                  <input type="text" class="form-control" name="check_point" required>
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
                            <h3 class="text-center">ALL VISA REQUIREMENT </h3>
                            <div class="sanctioned-view-area" style="background: #FFF;margin:15px">
                              <table class="table table-responsive">
                                <thead>
                                  <tr>
                                    <th>Sl</th>
                                    <th>Visa Type</th>
                                    <th>Check Point</th>
                                    <th>Saved By</th>
                                    <th>Saved Time</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                    if(count($visaCheck) > 0){
                                      $i = 1;
                                      foreach ($visaCheck as $Item) {
                                  ?>
                                      <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $Item->Visa_type; ?></td>
                                        <td><?php echo $Item->parameter; ?></td>
                                        <td><?php echo $Item->SavedBy; ?></td>
                                        <td><?php echo Date('d-m-Y',strtotime($Item->SaveTime)); ?></td>
                                        <td><a onclick="return confirm('Are you sure! You want to Edit?')" class="btn btn-sm btn-primary" href="{{URL::to('/operation/visa-requirement/edit').'/'.$Item->Sl}}"><i class="fa fa-edit"></i></a> <a onclick="return confirm('Are you sure! You want to Delete?')" class="btn btn-sm btn-danger" href="{{URL::to('/operation/visa-requirement/destroy').'/'.$Item->Sl}}"><i class="fa fa-trash"></i></a></td>
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
