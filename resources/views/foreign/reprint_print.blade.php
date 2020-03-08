@extends('admin.master')
<!--Page Title-->
@section('page-title')
    
@endsection
<!--Page Header-->
@section('page-header')
   
@endsection

<!--Page Content Start Here-->
@section('page-content')

    <style>
        /*Print Slip*/

        @media print {
            @page {
                size: landscape;
            }

            .noprint {
                display: none;
            }

            /*#printableArea {*/
            /*display: block !important;*/
            /*font-size: 11px !important;*/
            /*}*/
            #p {
                padding: 0px 0px 0px 0px !important;
            }

        }

    </style>

    <div id="printableArea" style="display: none;">
        <?php for ($i = 1; $i <= $service->slip_copy; $i++){  ?>
        <table border=0 width="100%" class="tclass" style="padding: 0">
            <tr>
                <td colspan=2 class=text-center><strong>Indian Visa Application Center</strong></td>
            </tr>
            <tr>
                <td colspan=2 class=text-center>
                    <div class="centerdiv">{{$appServed->center}}</div>
                </td>
            </tr>
            <tr>
                <td colspan=2 class=text-center>
                    <div class="centerdiv2">Foreign Passport Application</div>
                </td>
            </tr>

            <tr>
                <td>@php echo $now = date('d-M-Y'); @endphp</td>
                <td><span class=pull-right>@php echo date("h:i:s A"); @endphp</span></td>
            </tr>
            <tr>
                <td><hr style="font-weight: bold;color: #000;margin: 0px !important;border: 1px solid #000;"> <span style="font-size:18px;font-weight:bold">{{$appServed->Sticker_type}}<span style="font-size: 16px;">{{$appServed->RoundSticker}}</span></span></td>
                <td><hr style="font-weight: bold;color: #000;margin: 0px !important;border: 1px solid #000;"><span style="height:20px;display: inline-block"></span></td>
            </tr>
            <tr>
                <td colspan=2 class="text-center">
                    <div class="centerdiv2">
                        <center><svg id="bar_id"></svg></center>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan=2 style='margin: 105px 0'>
                    <div class="topborder"></div>
                    <strong>User :</strong> {{ $appServed->service_by}} <br/>
                    <strong>Counter :</strong> {{ $appServed->counter}} <br/>
                    <strong>Name:</strong> {{ $appServed->Applicant_name}} <br/>
                    <strong>Passport:</strong> {{ $appServed->Passport}} <br/>
                    <strong>Webfile No:</strong> {{ $appServed->WebFile_no}} <br/>
                    <strong>Processing fee:</strong> {{ $appServed->proc_fee}} <br/>
                    <strong>Visa Type:</strong> {{ $appServed->Visa_type}} <br/>
                    <strong>Phone :</strong> {{ $appServed->Contact}} <br/>
                    <strong>Nationality:</strong> {{ $fpServed->nationality}} <br/>
                    <strong>date of Checking:</strong> {{ $fpServed->date_of_checking}} <br/>
                    <strong>Remarks:</strong> {{ $fpServed->remarks}} <br/>
                    <strong>Visa fee:</strong> {{ $fpServed->visa_fee}} <br/>
                    <strong>Fax trans. charge:</strong> {{ $fpServed->fax_trans_charge}} <br/>
                    <strong>icwf:</strong> {{ $fpServed->icwf}} <br/>
                    <strong>Visa app. charge:</strong> {{ $fpServed->visa_app_charge}} <br/>
                    <strong>Special fee:</strong> {{ $appServed->sp_fee}}<br />
                    <strong>Corr fee:</strong> {{ $appServed->corrFee}} <br/>
                    <strong>Total:</strong> {{ $fpServed->total_amount+$appServed->proc_fee+$appServed->sp_fee+$appServed->corrFee}} <br />
                    <strong>Payment:</strong> {{ $appServed->Pmethod}} <br/>
                    <strong>Gratis/Non-Gratis Sticker:</strong> {{ $fpServed->strk_no}} <br/>
                    <strong>Center Phone:</strong> {{$center->center_phone}} <br/>
                    <strong>Fax:</strong> {{$center->center_fax}} <br/>
                    <strong>Info:</strong> {{$center->center_info}} <br/>
                    <strong>Delivery on or after:  </strong>{{  date('d-m-Y', strtotime($appServed->appx_Del_Date))}}
                    <span class="float-right">{{$center->del_time}}</span>
                    </br>
                    <div class="topborder"></div>
                </td>
            </tr>
            <tr>
                <td><hr style="font-weight: bold;color: #000;margin: 0px !important;border: 1px solid #000;"></td>
                <td><hr style="font-weight: bold;color: #000;margin: 0px !important;border: 1px solid #000;"></td>
            </tr>
            <tr>
                <td>{{$center->center_web}}</td>
                <td><span class=pull-right>EasyQ</span></td>
            </tr>
        </table>
        <?php
            if($BarcodePrint == 'passport'){
                $BarcodeData = $appServed->Passport;
            }
            else{
                $BarcodeData = $appServed->WebFile_no;
            }
        ?>
        <br>
        <script>
            JsBarcode("#bar_id", "<?php echo $BarcodeData; ?>", {
                    height: 25,
                    width: 1.5,
                    margin: 10,
                    fontSize: 11,
                }
            );
        </script>

        <?php } ?>
    </div>

    <script>
        function printDiv(pa) {
            var printContents = document.getElementById(pa).innerHTML;
            document.body.innerHTML = printContents;
            window.print();
        }
    </script>

    <script>
        <?php if (isset($id)){ ?>
            window.onload = printDiv('printableArea');
        $(window).on('afterprint', function () {
            window.location.href = "{{ url('/reprint-foreign-passport') }}";
            // window.close();
        });
        <?php } ?>
    </script>

    <script type="text/javascript">
        $(window).load(function () {
            //This execute when entire finished loaded
            window.print();
        });

    </script>


@endsection



