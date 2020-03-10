@extends('admin.master')
<!--Page Title-->
@section('page-title')
    Receive Foreign Passport
@endsection

<!--Page Header-->
@section('page-header')
    Edit Receive Foreign Passport
@endsection

<!--Page Content Start Here-->
@section('page-content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="main_part">
                    <br>
                    {{--<div class="row">--}}
                        {{--@if (Session::has('message'))--}}
                            {{--<div class="col-md-6 col-md-offset-3 alert {{ Session::get('alert-class') }}">{{ Session::get('message') }}</div>--}}
                        {{--@endif--}}
                    {{--</div>--}}

                <!-- Code Here.... -->
                    <div class="row">
                        <div class="col-md-4 change_passport_body"
                                style="width: 30%;padding-left: 33px;border-top: none;">
                                <p class="form_title_center bg-info">
                                    <i>-Edit Receive Foreign Passport-</i>
                                </p>
                            <form action="{{URL::to('edit-receive-foreign-passport')}}" method="POST">
                                {{csrf_field()}}
                                <div class="form-group">
                                    <input class="form-control" name="webfile" placeholder="Enter Webfile"
                                        required="required" autocomplete="off">
                                </div>
                                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                <button type="reset" class="btn btn-default">Reset</button>
                            </form>
                        </div>
                        <div class="col-md-6 col-md-offset-1">
                            @if (Session::has('message'))
                                <div class="row">
                                    <div class=" alert {{ Session::get('status') }} alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×
                                        </button>
                                        <h4> {{ Session::get('message') }}</h4>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <br>
                    </div>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                </div>
            </div>
        </div>
    </section>


@endsection