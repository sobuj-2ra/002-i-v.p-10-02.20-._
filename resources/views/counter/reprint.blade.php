@extends('admin.master')
<!--Page Title-->
@section('page-title')
    Foreign Regular Passport
@endsection

<!--Page Header-->
@section('page-header')
    Reprint Regular Passport
@endsection

<!--Page Content Start Here-->
@section('page-content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="main_part">
                    <br>

                <!-- Code Here.... -->
                    <div class="row">
                        <div class="col-md-4 change_passport_body"
                             style="width: 30%;padding-left: 33px;border-top: none;">
                            <p class="form_title_center bg-info">
                                <i>-Reprint Regular Passport-</i>
                            </p>
                            {!! Form::open(['url' => 'regular-passport-reprint','id' => 'applicant_form']) !!}
                            <div class="form-group">
                                <input class="form-control" name="webfile" placeholder="Enter webfile" required="required" autocomplete="off">
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                            <button type="reset" class="btn btn-default">Reset</button>
                            {!! Form::close() !!}
                        </div>
                        <div class="col-md-6 col-md-offset-1">
                            @if (Session::has('message'))
                                <div class="row">
                                    <div class="col-md-6 col-md-offset-3 alert alert-warning alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×
                                        </button>
                                        <h4> {{ Session::get('message') }}</h4>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
<!--Page Content End Here-->
