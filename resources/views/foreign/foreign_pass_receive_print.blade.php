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

    <div id="printableArea" >
        <?php foreach ($dataArr as $data){
            foreach ($valid_data as  $item) {
                //echo $data['webfile'];
                if($data['webfile'] == $item){
                    for($i_in = 1; $i_in <= $slipCopy->slip_copy;$i_in++){
            ?>
                    <table border=0 width="100%" class="tclass" style="padding: 0">
                        <tr>
                            <td colspan=2 class=text-center><strong>Indian Visa Application Center</strong></td>
                        </tr>
                        <tr>
                            <td colspan=2 class=text-center>
                                <div class="centerdiv"><b>{{$data['center_name']}}</b></div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan=2 class=text-center>
                                <div class="centerdiv2">STATEMENT OF VISA FEE COLLECTION</div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan=2 class=text-center>
                                <div class="centerdiv2">Foreign Passport</div>
                            </td>
                        </tr>

                        <tr>
                            <td>@php echo $now = date('d-M-Y'); @endphp</td>
                            <td><span class=pull-right>@php echo date("h:i:s A"); @endphp</span></td>
                        </tr>
                        <tr>
                            <td><hr style="font-weight: bold;color: #000;margin: 0px !important;border: 1px solid #000;"> <span style="font-size:18px;font-weight:bold">{{$data['sticker_type']}}<span style="font-size: 16px;">{{$data['validStkr']}}</span></span></td>
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
                                <strong>User :</strong> {{$data['user_id']}}<br/>
                                <strong>Counter :</strong> {{$data['counter_id']}}<br/>
                                <strong>Name :</strong> {{$data['name']}}<br/>
                                <strong>Passport :</strong> {{$data['passportNo']}}<br/>
                                <strong>Web file No :</strong> {{$data['webfile']}}<br/>
                                <strong>Visa Type :</strong> {{$data['visa_type']}}<br/>
                                <strong>Contact :</strong> {{$data['contact']}}<br/>
                                <strong>Visa fee :</strong> {{$data['visa_fee']}}<br/>
                                <strong>Fax trans. charge :</strong> {{$data['fax_trans_charge']}}<br/>
                                <strong>icwf :</strong> {{$data['icwf']}}<br/>
                                <strong>Visa app. charge :</strong> {{$data['visa_app_charge']}}<br/>
                                <strong>Processing fee :</strong> {{$data['proc_fee']}}<br/>
                                <strong>Special fee :</strong> {{$data['sp_fee']}}<br/>
                                <strong>Corr fee :</strong> {{$data['corr_fee']}}<br/>
                                <strong>Total Amount (received in cash) : BDT</strong> {{$data['total_amount_print']}}<br/>
                                <strong>Payment :</strong> {{$data['paytype']}}<br/>
                                <strong>Center Phone :</strong> {{$center_phone}}<br/>
                                <strong>Fax :</strong> {{$center_fax}}<br/>
                                <strong>Info :</strong> {{$center_info}}<br/>
                                {{-- <strong>Receiving Date :</strong> {{$data['receive_date']}}<br/> --}}

                                <strong>Delivery on or after:  </strong>{{  date('d-M-Y', strtotime($data['tddDelDateValue']))}}
                                <span class="float-right">{{$del_time}}</span>
                                </br>
                                <div class="topborder"></div>
                            </td>
                        </tr>
                        <tr>
                            <td><hr style="font-weight: bold;color: #000;margin: 0px !important;border: 1px solid #000;"></td>
                            <td><hr style="font-weight: bold;color: #000;margin: 0px !important;border: 1px solid #000;"></td>
                        </tr>
                        <tr>
                            <td>{{$center_web}}</td>
                            <td><span class=pull-right>EasyQ</span></td>
                        </tr>
                    </table>
                    <br>
                    <?php
                        if($BarcodePrint == 'passport'){
                            $BarcodeData = $data['passportNo'];
                        }
                        else{
                            $BarcodeData = $data['webfile'];
                        }
                    ?>
                <script>
                    JsBarcode("#bar_id", "<?php echo $BarcodeData; ?>", {
                            height: 25,
                            width: 1.5,
                            margin: 10,
                            fontSize: 11,
                        }
                    );
                </script>
            <?php
                    }
                }
            }
         } ?>

    </div>

    <script>
        function printDiv(pa) {
            var printContents = document.getElementById(pa).innerHTML;
            document.body.innerHTML = printContents;
            window.print();
        }
    </script>

    <script>
        
        window.onload = printDiv('printableArea');
        $(window).on('afterprint', function () {
            {{--window.location.href = "{{ url("/portendorsement/$type/$form/$to/$id") }}";--}}
            window.close();
        });
    </script>

    <script type="text/javascript">
        $(window).load(function () {
            //This execute when entire finished loaded
            window.print();
        });

    </script>


@endsection
