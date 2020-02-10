@extends('admin.master')
<!--Page Title-->
@section('page-title')
  Correction Fee
@endsection

<!--Page Header-->
@section('page-header')
  Correction Fee
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
                                Add Correction Fee
                              </button>

                              <!-- Modal -->
                              <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                    <form method="POST" autocomplete="off" action="{{URL::to('operation/correction-fee')}}">
                                      {{csrf_field()}}
                                        <div class="modal-header">
                                          <span class="modal-title" id="exampleModalLabel">Add Correction Fee</span>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                          </button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="form_title_center">
                                          <b>-ADD CORRECTION FEE-</b>
                                        </p>
                                          <div class="row">
                                            <div class="col-md-8">
                                              <div class="form-group">
                                                  <label for="correction"><b>Correction:</b></label>
                                                  <input type="text" id="correction" class="form-control" name="correction" required>
                                              </div>
                                            </div>
                                            <div class="col-md-4">
                                              <div class="form-group">
                                                <label for="amount"><b>Amount (BDT):</b></label>
                                                  <input type="number" id="amount" class="form-control" name="amount" required>
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
                            <h3 class="text-center">ALL CORRECTION FEE </h3>
                            <div class="sanctioned-view-area" style="background: #FFF;margin:15px">
                              <table class="table table-responsive">
                                <thead>
                                  <tr>
                                    <th>Sl</th>
                                    <th>Correction</th>
                                    <th>Amount</th>
                                    <th>Saved By</th>
                                    <th>Saved Time</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                    if(count($allCorrData) > 0){
                                      $i = 1;
                                      foreach ($allCorrData as $Item) {
                                  ?>
                                      <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $Item->Correction; ?></td>
                                        <td><?php echo $Item->amount; ?></td>
                                        <td><?php echo $Item->SaveBy; ?></td>
                                        <td><?php echo Date('d-m-Y',strtotime($Item->SaveTime)); ?></td>
                                        <td><a onclick="return confirm('Are you sure! You want to Edit?')" class="btn btn-sm btn-primary" href="{{URL::to('/operation/correction-fee/edit').'/'.$Item->Sl}}"><i class="fa fa-edit"></i></a> <a onclick="return confirm('Are you sure! You want to Delete?')" class="btn btn-sm btn-danger" href="{{URL::to('/operation/correction-fee/destroy').'/'.$Item->Sl}}"><i class="fa fa-trash"></i></a></td>
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
