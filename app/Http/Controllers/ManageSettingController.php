<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tbl_setup;
use App\Tbl_center_info;
use App\Tbl_service;
use App\Tbl_floor;
use App\FloorTokenQueue;
use App\FloorTokenHistory;
use App\Tbl_counter;
use App\Tbl_callCounter;
use App\Tbl_service_log;
use App\Tbl_appointmentlist;
use App\Tbl_appointmentserved;
use App\Tbl_del_operation;
use App\Tbl_visa_type;
use App\Tbl_visacheck;
use App\Tbl_correctionfee;
use App\Tbl_rejectcause;
use App\Tbl_portName;
use DateTime;
use Session;
use Auth;
use DB;

class ManageSettingController extends Controller
{
    public function actionSetupCreate(){
      $centerDataArr = Tbl_setup::all();
      foreach ($centerDataArr as $item)
      {
        if($item->item_name == 'ServerName')
        {
          $data['serverName'] = $item->item_value;
        }

        if($item->item_name == 'Comport')
        {
          $data['Comport'] = $item->item_value;
        }

        if($item->item_name == 'DBQT')
        {
          $data['dbqt'] = $item->item_value;
        }

        if($item->item_name == 'CenterName')
        {
          $data['centerName'] = $item->item_value;
        }

        if($item->item_name == 'BackupMIN')
        {
          $data['BackupMIN'] = $item->item_value;
        }

        if($item->item_name == 'UploadMIN')
        {
          $data['UploadMIN'] = $item->item_value;
        }

        if($item->item_name == 'Barcode')
        {
          $data['Barcode'] = $item->item_value;
        }

        if($item->item_name == 'payment_api')
        {
          $data['payment_api'] = $item->item_value;
        }

        if($item->item_name == 'debug')
        {
          $data['debug'] = $item->item_value;
        }
        if($item->item_name == 'audio_floor')
        {
          $data['audio_floor'] = $item->item_value;
        }
        if($item->item_name == 'UploadSize')
        {
          $data['UploadSize'] = $item->item_value;
        }



      }

      $data['centerAll'] = Tbl_center_info::all();

      return view('master_setting.action_setup.action_setup', $data);
    }

    public function actionSetupUpdate(Request $request){

        $setupData = Tbl_setup::all();
        foreach ($setupData as $value) {
          foreach ($request->all() as  $index=>$item) {
            if($value->item_name == $index){
              $data = Tbl_setup::where('sl',$value->sl)->where('item_name', $value->item_name)->update([
                "item_value"=>$item
              ]);
            }
          }
        }

        return redirect()->back()->with(['statusMsg'=>'Data Update Successfully']);
    }


  public function queueSetupCreate(){
    $data['allServices'] = Tbl_service::all();
    return view('master_setting.queue_setup.queue_setup', $data);
  }

  public function queueSetupStore(Request $request)
  {
    $if_exist = Tbl_service::where('svc_number',$request->svc_number)->count();
    if($if_exist){
        return redirect()->back()->with(['msg'=>'This Service Number Aleady Exist','status'=>'warning']);
    }
    else{
      $curDateTime = Date('Y-m-d H:i:s');
      $user_id = Auth::user()->user_id;
      $is_save = Tbl_service::create([
        'svc_number'=>$request->svc_number,
        'svc_name'=> $request->svc_name,
        'entrydate'=>$curDateTime,
        'entryby'=>$user_id,
        'qty'=>$request->token_qty,
        'defCon'=>$request->default_qty,
        'status'=>$request->active_status,
      ]);

      if($is_save){
        return redirect()->back()->with(['msg'=>'Data Inserted Successfully','status'=>'success']);
      }
      else{
        return redirect()->back()->with(['msg'=>'Data Couldn\'t Insert','status'=>'danger']);
      }
    }
  }

  public function queueSetupEdit($id){
    $ServiceData = Tbl_service::find($id);
    //return $ServiceData;
    if($ServiceData){
      return view('master_setting.queue_setup.queue_setup_edit',compact('ServiceData'));
    }
    else{
      return redirect()->back()->with(['msg'=>'Data Couldn\'t Found','status'=>'warning']);
    }
  }
  public function queueSetupUpdate(Request $request){
    $id = $request->update_id;
    $user_id = Auth::user()->user_id;
    $is_update = Tbl_service::find($id)->update([
      'svc_number'=>$request->svc_number,
      'svc_name'=> $request->svc_name,
      'entryby'=>$user_id,
      'qty'=>$request->token_qty,
      'defCon'=>$request->default_qty,
      'status'=>$request->active_status,
    ]);
    if($is_update){
      return redirect('/setting/queue-setup/create')->with(['msg'=>'Data Updated Successfully','status'=>'success']);
    }
    else{
      return redirect('/setting/queue-setup/create')->back()->with(['msg'=>'Data Couldn\'t Update','status'=>'danger']);
    }
  }
  public function queueSetupDestroy($id){
    $is_delete = Tbl_service::find($id)->delete();
    if($is_delete){
      return redirect('/setting/queue-setup/create')->with(['msg'=>'Data Updated Successfully','status'=>'success']);
    }
    else{
      return redirect('/setting/queue-setup/create')->back()->with(['msg'=>'Data Couldn\'t Update','status'=>'danger']);
    }
  }


///// ADMIN OPERATION /////


  public function manageQueue(){
    $allFloor = Tbl_floor::all();
    $allService = Tbl_service::all();
    return view('admin_operation.manage_queue',compact(['allFloor','allService']));
  }
  public function deletePendingToken(Request $request){
       $curDate = Date('Y-m-d');
       $pendinToken = FloorTokenQueue::whereDate('token_date',$curDate)
           ->where('token_service_no',$request->service_id)
           ->where('floor_id',$request->floor_id)
           ->delete();
       if($pendinToken){
         return response()->json(['msg'=>'Data Deleted Successfully','status'=>'yes']);
       }
       else{
           $data_C = FloorTokenQueue::whereDate('token_date',$curDate)
               ->where('token_service_no',$request->service_id)
               ->where('floor_id',$request->floor_id)
               ->count();
           if($data_C > 0){
               return response()->json(['msg'=>'Data Couldn\'t Delete','status'=>'no']);
           }
           else{
               return response()->json(['msg'=>'Data Not Found','status'=>'notFound']);
           }
       }


  }

  public function deleteHistoryToken(Request $request){
       $curDate = Date('Y-m-d');
       $pendinToken = FloorTokenHistory::whereDate('token_date',$curDate)
           ->where('token_svc',$request->service_id)
           ->where('floor_id',$request->floor_id)
           ->delete();
       if($pendinToken){
         return response()->json(['msg'=>'Data Deleted Successfully','status'=>'yes']);
       }
       else{
           $data_C = FloorTokenHistory::whereDate('token_date',$curDate)
               ->where('token_svc',$request->service_id)
               ->where('floor_id',$request->floor_id)
               ->count();
           if($data_C > 0){
               return response()->json(['msg'=>'Data Couldn\'t Delete','status'=>'no']);
           }
           else{
               return response()->json(['msg'=>'Data Not Found','status'=>'notFound']);
           }
       }


  }

  public function SetIssueToken(Request $request){
       $curDate = Date('Y-m-d');
       $curDateTime = Date('Y-m-d H:i:s');
       $pendinToken = FloorTokenHistory::whereDate('token_date',$curDate)
           ->where('token_svc',$request->service_id)
           ->where('floor_id',1)
           ->delete();
      $history = new FloorTokenHistory;
      $history->token_svc = $request->service_id;
      $history->floor_id = 1;
      $history->token = $request->issue_token_id;
      $history->token_date = $curDateTime;
      $history->token_type = '1';
      $is_save = $history->save();

       if($is_save){
         return response()->json(['msg'=>'Data Set Successfully','status'=>'yes']);
       }
       else{
           return response()->json(['msg'=>'Data Couldn\'t Set','status'=>'no']);
       }

  }

  public function CreateTokenNumber(Request $request){
      $create_token_id = $request->create_token_id;
      $service_id = $request->service_id;

      for ($i = 1;$i <= $create_token_id; $i++){
          DB::select('CALL IssueTokenWaitF("'.$service_id.'","1")');
      }


      return response()->json(['msg'=>'Token Created Successfully','status'=>'yes']);

  }


    public function counterSetupCreate(){
      $data['allCounter'] = Tbl_counter::all();
      $data['allFloor'] = Tbl_floor::all();
      $data['allService'] = Tbl_service::all();
      return view('master_setting.counter_setup.counter_setup', $data);
    }

  public function counterSetupStore(Request $r)
  {
      $user_id = Auth::user()->user_id;
      $curDate = Date('Y-m-d H:i:s');
      $service_name = implode($r->service_name,',');

     $if_exist = Tbl_counter::where('counter_no',$r->counter_no)->where('floor_id',$r->floor_no)->count();

     if($if_exist < 1){
        $is_save = Tbl_counter::create([
            'hostname'=>$r->host_name,
            'counter_no'=>$r->counter_no,
            'ip'=>$r->counter_ip,
            'floor_id'=>$r->floor_no,
            'entrydate'=>$curDate,
            'entryby'=>$user_id,
            'permission'=>$user_id,
            'svc_name'=>$service_name,
        ]);
        

          if($is_save){
            return redirect()->back()->with(['msg'=>'Data Inserted Successfully','status'=>'success']);
          }
          else{
            return redirect()->back()->with(['msg'=>'Data Couldn\'t Insert','status'=>'danger']);
          }
     }
     else{
      return redirect()->back()->with(['msg'=>'This Counter No Already Exist','status'=>'danger']);
     }
   
  }

  public function counterSetupEdit($id){
    $data['allFloor'] = Tbl_floor::all();
    $data['allService'] = Tbl_service::all();
    $data['CounterData'] = $CounterData = Tbl_counter::find($id);
    
    $data['oldSvc'] = explode(',',$CounterData->svc_name);

    if($CounterData){
      return view('master_setting.counter_setup.counter_setup_edit',$data);
    }
    else{
      return redirect()->with(['msg'=>'Data Couldn\'t Found','status'=>'warning']);
    }
  }

  public function counterSetupUpdate(Request $r){
      $user_id = Auth::user()->user_id;
      $service_name = implode($r->service_name,',');

      $is_update = Tbl_counter::find($r->update_id)->update([
          'hostname'=>$r->host_name,
            'counter_no'=>$r->counter_no,
            'ip'=>$r->counter_ip,
            'floor_id'=>$r->floor_no,
            'entryby'=>$user_id,
            'permission'=>$user_id,
            'svc_name'=>$service_name,
      ]);

      if($is_update){
        return redirect('/setting/counter-setup/create')->with(['msg'=>'Data Updated Successfully','status'=>'success']);
      }
      else{
        return redirect('/setting/counter-setup/create')->with(['msg'=>'Data Couldn\'t Update','status'=>'danger']);
      }


  }
  public function counterSetupDestroy($id){
    $is_delete = Tbl_counter::find($id)->delete();
    if($is_delete){
      return redirect('/setting/counter-setup/create')->with(['msg'=>'Data Updated Successfully','status'=>'success']);
    }
    else{
      return redirect('/setting/counter-setup/create')->with(['msg'=>'Data Couldn\'t Update','status'=>'danger']);
    }
  }


  public function queueStatus(){
    $allService = Tbl_service::all();
    $activeToken = Tbl_callCounter::all();
    return view('admin_operation.queue_status',compact('allService','activeToken'));
  }
  public function searchTokenInfo(Request $r){
    $token_info = Tbl_service_log::whereDate('tissuetime',Date('Y-m-d'))->where('tokenno',$r->token_no)->get();
    if(count($token_info) > 0){
      return response()->json(['token_info'=>$token_info,'status'=>1]);
    }
    else{
      return response()->json(['status'=>0]);
    }
  }

  public function changeStatus(){

    return view('admin_operation.change_status');
  }

  public function changeStatusEdit(Request $r){
    $webfileData = Tbl_appointmentlist::where('WebFile_no',$r->webfile)->first();
    if($webfileData){
        $status = $webfileData->Presence_Status;
        if($status){
          $statusArr = ['ACCEPTED','PENDING','REJECTED','RETURN','BACK REJECTED','EXPIRED'];
          if($status == 'ACCEPTED'){
            $resArr = ['PENDING','REJECTED','RETURN','BACK REJECTED'];
          }
          else if($status == 'PENDING'){
            $resArr = ['REJECTED'];
          }
          else if($status == 'BACK REJECTED'){
            $resArr = ['PENDING','RETURN'];
          }
          else if($status == 'REJECTED'){
            $resArr = ['PENDING','RETURN'];
          }
          else if($status == 'RETURN'){
            $resArr = ['REJECTED','PENDING'];
          }
          else if($status == 'EXPIRED'){
            $resArr =  ['PENDING','REJECTED','RETURN','BACK REJECTED'];
          }
          return view('admin_operation.change_status_edit',compact('statusArr','webfileData','resArr'));
        }
    }
    else{
      return redirect('operation/change-status')->with(['msg'=>'Webfile Not Found','status'=>'warning']);
    }
  }

  public function hit_changeStatusFunc_AppServeDel($web,$oldST,$presST,$del){
      if($del == 1){
        $user_id = Auth::user()->user_id;
        $curDateTime = Date('Y-m-d H:i:s');

        Tbl_appointmentlist::where('WebFile_no',$web)->update(['Presence_Status'=>$presST]);
        Tbl_appointmentserved::where('WebFile_no',$web)->delete();
        $delOp = new Tbl_del_operation;
        $delOp->webfile = $web;
        $delOp->old_data = $oldST;
        $delOp->action_by = $user_id;
        $delOp->action_time = $curDateTime;
        $delOp->remark = 'Status Changse';
        $delOp->service_type = 'Regular Passport';
        $is_save = $delOp->save();
        if($is_save){
          return redirect('operation/change-status')->with(['msg'=>'Data Updated Successfully','status'=>'success']);
        }
        else{
          return redirect('operation/change-status')->with(['msg'=>'Data Updated Successfully','status'=>'success']);
        }
      }
      else{
        $user_id = Auth::user()->user_id;
        $curDateTime = Date('Y-m-d H:i:s');

        Tbl_appointmentlist::where('WebFile_no',$web)->update(['Presence_Status'=>$presST]);
        // Tbl_appointmentserved::where('WebFile_no',$web)->delete();
        $delOp = new Tbl_del_operation;
        $delOp->webfile = $web;
        $delOp->old_data = $oldST;
        $delOp->action_by = $user_id;
        $delOp->action_time = $curDateTime;
        $delOp->remark = 'Status Changse';
        $delOp->service_type = 'Regular Passport';
        $is_save = $delOp->save();
        if($is_save){
          return redirect('operation/change-status')->with(['msg'=>'Data Updated Successfully','status'=>'success']);
        }
        else{
          return redirect('operation/change-status')->with(['msg'=>'Data Updated Successfully','status'=>'success']);
        }
      }
  }
  public function hit_webfile_ssl_api($web){
    $user_id = Auth::user()->user_id;
    $get_api = Tbl_setup::where('item_name', 'payment_api')->first();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$get_api->item_value.'/?webfile='.$web.'&user='.$user_id.'&save='.'R'.'');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $ssldata = curl_exec($ch);
    // $sslArr = explode(',',$ssldata);
    // $onlinePay = $sslArr[0];
  }

  public function changeStatusUpdate(Request $r)
  {
      $user_id = Auth::user()->user_id;
      $curDateTime = Date('Y-m-d H:i:s');
      $webfile =  $r->webfile;
      $status =  $r->old_status;
      $p_status =  $r->present_status;
      if($status == 'ACCEPTED')
      {
        if($p_status == 'PENDING'){
          return $this->hit_changeStatusFunc_AppServeDel($webfile,$status,$p_status,$del=1);
        }
        else if($p_status == 'BACK REJECTED'){
          return $this->hit_changeStatusFunc_AppServeDel($webfile,$status,$p_status,$del=1);
        }
        else if($p_status == 'RETURN'){

          $this->hit_webfile_ssl_api($webfile);
          return $this->hit_changeStatusFunc_AppServeDel($webfile,$status,$p_status,$del=1);
          
        }
        else if($p_status == 'REJECTED'){
          return $this->hit_changeStatusFunc_AppServeDel($webfile,$status,$p_status,$del=1);
        }
      }
      else if($status == 'PENDING')
      {
          if($p_status == 'REJECTED')
          {
            return $this->hit_changeStatusFunc_AppServeDel($webfile,$status,$p_status,$del=0);
          }
      }
      else if($status == 'BACK REJECTED')
      {

          if($p_status == 'PENDING'){
            return $this->hit_changeStatusFunc_AppServeDel($webfile,$status,$p_status,$del=0);
          }
          
          else if($p_status == 'RETURN'){
            $this->hit_webfile_ssl_api($webfile);
            return $this->hit_changeStatusFunc_AppServeDel($webfile,$status,$p_status,$del=0);
            
          }
        
      }
      else if($status == 'RETURN')
      {
            if($p_status == 'PENDING'){
              return $this->hit_changeStatusFunc_AppServeDel($webfile,$status,$p_status,$del=0);
            }
            else if($p_status == 'REJECTED'){
              return $this->hit_changeStatusFunc_AppServeDel($webfile,$status,$p_status,$del=0);
            }
            
      }
      else if($status == 'REJECTED')
      {
            if($p_status == 'PENDING'){
              return $this->hit_changeStatusFunc_AppServeDel($webfile,$status,$p_status,$del=0);
            }
            else if($p_status == 'RETURN'){
              $this->hit_webfile_ssl_api($webfile);
              return $this->hit_changeStatusFunc_AppServeDel($webfile,$status,$p_status,$del=0);
              
            }
            
      }
      else if($status == 'EXPIRED')
      {
          if($p_status == 'PENDING'){
            return $this->hit_changeStatusFunc_AppServeDel($webfile,$status,$p_status,$del=0);
          }
          else if($p_status == 'BACK REJECTED'){
            return $this->hit_changeStatusFunc_AppServeDel($webfile,$status,$p_status,$del=0);
          }
          else if($p_status == 'RETURN'){
            $this->hit_webfile_ssl_api($webfile);
            return $this->hit_changeStatusFunc_AppServeDel($webfile,$status,$p_status,$del=0);
          }
      }
  }

  public function SyncMaintenanceView(){
    return view('admin_operation.sync_maintence.sync_maintenance');
  }
  public function SyncMaintenanceGet(Request $r){
    $from = $r->date_from;
    $to = $r->date_to;
    $from_date = date('Y-m-d 00:00:00', strtotime($from));
    $to_date = date('Y-m-d 23:59:59', strtotime($to));
    $type = $r->sync_type;
    $status = $r->sync_status;


    if($type == 'Received'){
        if($status == 'Success'){
          $servedData = Tbl_appointmentserved::whereBetween('Service_Date',[$from_date,$to_date])
          ->orderBy('Service_Date','DESC')
          ->where('status',0)
          ->get();
        }
        else if($status == 'Pending'){
          $servedData = Tbl_appointmentserved::whereBetween('Service_Date',[$from_date,$to_date])
          ->orderBy('Service_Date','DESC')
          ->where('status',1)
          ->orWhere('status',3)
          ->get();
        }
        else if($status == 'Failed'){
          $servedData = Tbl_appointmentserved::whereBetween('Service_Date',[$from_date,$to_date])
          ->orderBy('Service_Date','DESC')
          ->where('status',5)
          ->get();
        }
    }
    else if($type == 'Ready@Center'){
        if($status == 'Success'){
          $servedData = Tbl_appointmentserved::whereBetween('ReadyCentertime',[$from_date,$to_date])
          ->orderBy('ReadyCentertime','DESC')
          ->where('status',0)
          ->get();
        }
        else if($status == 'Pending'){
          $servedData = Tbl_appointmentserved::whereBetween('ReadyCentertime',[$from_date,$to_date])
          ->orderBy('ReadyCentertime','DESC')
          ->where('status',2)
          ->orWhere('status',4)
          ->get();
        }
        else if($status == 'Failed'){
          $servedData = Tbl_appointmentserved::whereBetween('ReadyCentertime',[$from_date,$to_date])
          ->orderBy('ReadyCentertime','DESC')
          ->where('status',6)
          ->get();
        }
    }
    else if($type == 'Delivery'){
        if($status == 'Success'){
          $servedData = Tbl_appointmentserved::whereBetween('DelFinaltime',[$from_date,$to_date])
          ->orderBy('DelFinaltime','DESC')
          ->where('status',0)
          ->get();
        }
        else if($status == 'Pending'){
          $servedData = Tbl_appointmentserved::whereBetween('DelFinaltime',[$from_date,$to_date])
          ->orderBy('DelFinaltime','DESC')
          ->where('status',2)
          ->orWhere('status',4)
          ->get();
        }
        else if($status == 'Failed'){
          $servedData = Tbl_appointmentserved::whereBetween('DelFinaltime',[$from_date,$to_date])
          ->orderBy('DelFinaltime','DESC')
          ->where('status',6)
          ->get();
        }
    }
    return view('admin_operation.sync_maintence.sync_maintenance',compact('servedData','type','status','from','to'));
  }

  public function SyncMaintenanceGetUpdate(Request $r){

    $getData =  $r->h_id;
    $type =  $r->h_type;
    foreach($getData as $item){
      echo $item."<br>";
      if($type == 'Received'){
          Tbl_appointmentserved::where('app_sl',$item)->Update(['status'=>1]);
      }
      else{
          Tbl_appointmentserved::where('app_sl',$item)->Update(['status'=>2]);
      }
    }
    return redirect('operation/sync-maintenance')->with(['msg'=>'Data Update Successfully','status'=>'success']);

  }


  /// visa requirement //

  public function visaRequireCreate(){
    $data['visaType'] = Tbl_visa_type::all();
    $data['visaCheck'] = Tbl_visacheck::all();
    return view('admin_operation.visa_require.visa_require', $data);
  }

  public function visaRequireStore(Request $r)
  {

      $curDateTime = Date('Y-m-d H:i:s');
      $user_id = Auth::user()->user_id;
      $is_save = Tbl_visacheck::create([
        'Visa_type'=>$r->visa_type,
        'parameter'=>$r->check_point,
        'SaveTime'=>$curDateTime,
        'SavedBy'=>$user_id,
      ]);

      if($is_save){
        return redirect()->back()->with(['msg'=>'Data Inserted Successfully','status'=>'success']);
      }
      else{
        return redirect()->back()->with(['msg'=>'Data Couldn\'t Insert','status'=>'danger']);
      }
    
  }

  public function visaRequireEdit($id){
    $visaType = Tbl_visa_type::all();
    $visaCheck = Tbl_visacheck::where('Sl',$id)->first();
    //return $ServiceData;
    if($visaCheck){
      return view('admin_operation.visa_require.visa_require_edit',compact('visaCheck','visaType'));
    }
    else{
      return redirect()->back()->with(['msg'=>'Data Couldn\'t Found','status'=>'warning']);
    }
  }
  public function visaRequireUpdate(Request $r){
    $id = $r->update_id;
    $curDateTime = Date('Y-m-d H:i:s');
    $user_id = Auth::user()->user_id;
    $is_update = Tbl_visacheck::where('Sl',$id)->update([
      'Visa_type'=>$r->visa_type,
      'parameter'=>$r->check_point,
      'SaveTime'=>$curDateTime,
      'SavedBy'=>$user_id,
    ]);
    if($is_update){
      return redirect('/operation/visa-requirement')->with(['msg'=>'Data Updated Successfully','status'=>'success']);
    }
    else{
      return redirect('/operation/visa-requirement')->with(['msg'=>'Data Couldn\'t Update','status'=>'danger']);
    }
  }
  public function visaRequireDestroy($id){
    $is_delete = Tbl_visacheck::where('Sl',$id)->delete();
    if($is_delete){
      return redirect('/operation/visa-requirement')->with(['msg'=>'Data Deleted Successfully','status'=>'success']);
    }
    else{
      return redirect('/operation/visa-requirement')->with(['msg'=>'Data Couldn\'t Delete','status'=>'danger']);
    }
  }




  /// correction fee //

  public function corFeeCreate(){
    $data['allCorrData'] = Tbl_correctionfee::all();
    return view('admin_operation.cor_fee.cor_fee', $data);
  }

  public function corFeeStore(Request $r)
  {
      $correction = $r->correction;
      $amount = $r->amount;

      $curDateTime = Date('Y-m-d H:i:s');
      $user_id = Auth::user()->user_id;
      $is_save = Tbl_correctionfee::create([
        'Correction'=>$correction,
        'amount'=> $amount,
        'SaveBy'=>$user_id,
        'SaveTime'=>$curDateTime,
      ]);

      if($is_save){
        return redirect()->back()->with(['msg'=>'Data Inserted Successfully','status'=>'success']);
      }
      else{
        return redirect()->back()->with(['msg'=>'Data Couldn\'t Insert','status'=>'danger']);
      }
    
  }

  public function corFeeEdit($id){

    $corrData = Tbl_correctionfee::where('Sl',$id)->first();
    //return $ServiceData;
    if($corrData){
      return view('admin_operation.cor_fee.cor_fee_edit',compact('corrData'));
    }
    else{
      return redirect()->back()->with(['msg'=>'Data Couldn\'t Found','status'=>'warning']);
    }
  }
  public function corFeeUpdate(Request $r){
    $correction = $r->correction;
    $amount = $r->amount;
    $id = $r->update_id;
    $curDateTime = Date('Y-m-d H:i:s');
    $user_id = Auth::user()->user_id;
    $is_update = Tbl_correctionfee::where('Sl',$id)->update([
      'Correction'=>$correction,
      'amount'=> $amount,
      'SaveBy'=>$user_id,
      'SaveTime'=>$curDateTime,
    ]);
    if($is_update){
      return redirect('/operation/correction-fee')->with(['msg'=>'Data Updated Successfully','status'=>'success']);
    }
    else{
      return redirect('/operation/correction-fee')->with(['msg'=>'Data Couldn\'t Update','status'=>'danger']);
    }
  }
  public function corFeeDestroy($id){
    $is_delete = Tbl_correctionfee::where('Sl',$id)->delete();
    if($is_delete){
      return redirect('/operation/correction-fee')->with(['msg'=>'Data Deleted Successfully','status'=>'success']);
    }
    else{
      return redirect('/operation/correction-fee')->with(['msg'=>'Data Couldn\'t Delete','status'=>'danger']);
    }
  }




  /// PORT NAME //

  public function portNameCreate(){
    $data['portName'] = Tbl_portName::all();
    return view('admin_operation.port_name.port_name', $data);
  }

  public function portNameStore(Request $r)
  {
      $name = $r->port_name;
      $fee = $r->fee;
      $s_type = $r->service_type;

      $curDateTime = Date('Y-m-d H:i:s');
      $user_id = Auth::user()->user_id;
      $is_save = Tbl_portName::create([
        'port_name'=>$name,
        'fee'=> $fee,
        'service_type'=> $s_type,
        'save_time'=>$curDateTime,
      ]);

      if($is_save){
        return redirect()->back()->with(['msg'=>'Data Inserted Successfully','status'=>'success']);
      }
      else{
        return redirect()->back()->with(['msg'=>'Data Couldn\'t Insert','status'=>'danger']);
      }
    
  }

  public function portNameEdit($id){

    $portName = Tbl_portName::where('port_id',$id)->first();
    if($portName){
      return view('admin_operation.port_name.port_name_edit',compact('portName'));
    }
    else{
      return redirect()->back()->with(['msg'=>'Data Couldn\'t Found','status'=>'warning']);
    }
  }
  public function portNameUpdate(Request $r){
    $id = $r->update_id;
    $name = $r->fee;
    $fee = $r->fee;
    $s_type = $r->service_type;

    $curDateTime = Date('Y-m-d H:i:s');
    $user_id = Auth::user()->user_id;
    $is_update = Tbl_portName::where('port_id',$id)->update([
        'port_name'=>$name,
        'fee'=> $fee,
        'service_type'=> $s_type,
        'save_time'=>$curDateTime,
    ]);
    if($is_update){
      return redirect('/operation/port-name')->with(['msg'=>'Data Updated Successfully','status'=>'success']);
    }
    else{
      return redirect('/operation/port-name')->with(['msg'=>'Data Couldn\'t Update','status'=>'danger']);
    }
  }
  public function portNameDestroy($id){
    $is_delete = Tbl_portName::where('port_id',$id)->delete();
    if($is_delete){
      return redirect('/operation/port-name')->with(['msg'=>'Data Deleted Successfully','status'=>'success']);
    }
    else{
      return redirect('/operation/port-name')->with(['msg'=>'Data Couldn\'t Delete','status'=>'danger']);
    }
  }


  /// REJECTION REASON //

  public function rejectReasonCreate(){




    $data['rejectReason'] = Tbl_rejectcause::all();
    return view('admin_operation.reject_reason.reject_reason', $data);
  }

  public function rejectReasonStore(Request $r)
  {
      $reason = $r->rejection_reason;

      $curDateTime = Date('Y-m-d H:i:s');
      $user_id = Auth::user()->user_id;
      $is_save = Tbl_rejectcause::create([
        'reason'=>$reason,
        'SavedBy'=> $user_id,
        'SaveTime'=>$curDateTime,
      ]);

      if($is_save){
        return redirect()->back()->with(['msg'=>'Data Inserted Successfully','status'=>'success']);
      }
      else{
        return redirect()->back()->with(['msg'=>'Data Couldn\'t Insert','status'=>'danger']);
      }
    
  }

  public function rejectReasonEdit($id){

    $reason = Tbl_rejectcause::where('Sl',$id)->first();
    if($reason){
      return view('admin_operation.reject_reason.reject_reason_edit',compact('reason'));
    }
    else{
      return redirect()->back()->with(['msg'=>'Data Couldn\'t Found','status'=>'warning']);
    }
  }
  public function rejectReasonUpdate(Request $r){
    $id = $r->update_id;
    $reason = $r->rejection_reason;

    $curDateTime = Date('Y-m-d H:i:s');
    $user_id = Auth::user()->user_id;
    $is_update = Tbl_rejectcause::where('Sl',$id)->update([
        'reason'=>$reason,
        'SavedBy'=> $user_id,
        'SaveTime'=>$curDateTime,
    ]);
    if($is_update){
      return redirect('/operation/rejection-reason')->with(['msg'=>'Data Updated Successfully','status'=>'success']);
    }
    else{
      return redirect('/operation/rejection-reason')->with(['msg'=>'Data Couldn\'t Update','status'=>'danger']);
    }
  }
  public function rejectReasonDestroy($id){
    $is_delete = Tbl_rejectcause::where('Sl',$id)->delete();
    if($is_delete){
      return redirect('/operation/rejection-reason')->with(['msg'=>'Data Deleted Successfully','status'=>'success']);
    }
    else{
      return redirect('/operation/rejection-reason')->with(['msg'=>'Data Couldn\'t Delete','status'=>'danger']);
    }
  }


  public function regularServiceSearch(){
    $data['center_name'] = Tbl_center_info::select('center_name')->first();
    $receiveData = Tbl_appointmentserved::whereDate('Service_Date',Date('Y-m-d'))->get();
    $rcvData = collect($receiveData);
    $data['rcvUploaded'] = $rcvData->where('status',0)->count();
    $data['rcvPending'] = $rcvData->where('status',1)->count()+$rcvData->where('status',3)->count();
    $data['rcvFaild'] = $rcvData->where('status',5)->count();

    $ReadyCenterData = Tbl_appointmentserved::whereDate('ReadyCentertime',Date('Y-m-d'))->get();
    $redData = collect($ReadyCenterData);
    $data['redUploaded'] = $redData->where('status',0)->count();
    $data['redPending'] = $redData->where('status',2)->count()+$rcvData->where('status',4)->count();
    $data['redFaild'] = $redData->where('status',6)->count();

    $DelCenterData = Tbl_appointmentserved::whereDate('DelFinaltime',Date('Y-m-d'))->get();
    $delData = collect($DelCenterData);
    $data['delUploaded'] = $delData->where('status',0)->count();
    $data['delPending'] = $delData->where('status',2)->count()+$rcvData->where('status',4)->count();
    $data['delFaild'] = $delData->where('status',6)->count();
    $data['searchData'] = false;

    return view('admin_operation.regular_service_search',$data);
  }
  public function regularServiceSearchGet(Request $r){
    $data['s_data'] = $s_data = $r->service_search;
    $checkWeb = substr($s_data,0,3);
    if($checkWeb == 'BGD'){
        $appData = Tbl_appointmentserved::where('WebFile_no',$s_data)->orderBy('Service_Date','DESC')->first();
        if($appData){
          $data['getTable'] = 'Appointment Served';
        }
        else{
          $appData = Tbl_appointmentlist::where('WebFile_no',$s_data)->orderBy('Appointment_Date','DESC')->first();
          if($appData){
            $data['getTable'] = 'Appointment List';
          }
        }
        $data['serachBy'] = 'Webfile';
    }
    else{
      $appData = Tbl_appointmentserved::where('Passport',$s_data)->orderBy('Service_Date','DESC')->first();
      if($appData){
        $data['getTable'] = 'Appointment Served';
      }
      else{
        $appData = Tbl_appointmentlist::where('Passport',$s_data)->orderBy('Appointment_Date','DESC')->first();
        if($appData){
          $data['getTable'] = 'Appointment List';
        }
      }
      $data['serachBy'] = 'Passport';
    }

    $data['appData'] = $appData;
    if($appData){
      $data['dataStatus'] = true;
      $data['searchData'] = false;
    }
    else{
      $data['dataStatus'] = false;
      $data['searchData'] = true;
    }

    // Dashboad status //
    $data['center_name'] = Tbl_center_info::select('center_name')->first();
    $receiveData = Tbl_appointmentserved::whereDate('Service_Date',Date('Y-m-d'))->get();
    $rcvData = collect($receiveData);
    $data['rcvUploaded'] = $rcvData->where('status',0)->count();
    $data['rcvPending'] = $rcvData->where('status',1)->count()+$rcvData->where('status',3)->count();
    $data['rcvFaild'] = $rcvData->where('status',5)->count();

    $ReadyCenterData = Tbl_appointmentserved::whereDate('ReadyCentertime',Date('Y-m-d'))->get();
    $redData = collect($ReadyCenterData);
    $data['redUploaded'] = $redData->where('status',0)->count();
    $data['redPending'] = $redData->where('status',2)->count()+$rcvData->where('status',4)->count();
    $data['redFaild'] = $redData->where('status',6)->count();

    $DelCenterData = Tbl_appointmentserved::whereDate('DelFinaltime',Date('Y-m-d'))->get();
    $delData = collect($DelCenterData);
    $data['delUploaded'] = $delData->where('status',0)->count();
    $data['delPending'] = $delData->where('status',2)->count()+$rcvData->where('status',4)->count();
    $data['delFaild'] = $delData->where('status',6)->count();
    

    return view('admin_operation.regular_service_search',$data);


  }


  //// Holiday  Start/////

  public function holidayView(){

    $data['allHoliday'] = DB::table('tbl_holiday')->orderBy('hday_id','DESC')->get();
    return view('master_setting.holiday.holiday_view',$data);
  }

  public function holidayStore(Request $r){
    $allDay = $r->day;
    $curDateTime = Date('Y-m-d H:i:s');
    $from_date = Date('Y-m-d',strtotime($r->from_date));
    $to_date = Date('Y-m-d',strtotime($r->to_date));


    //$date_from = "2020-02-01";
    $date_from = strtotime($from_date);

    //$date_to="2020-12-30";
    $date_to = strtotime($to_date);
    foreach($allDay as $s_day){
        for ($i=$date_from; $i<=$date_to; $i+=86400) {
            $day = date("Y-m-d", $i);
            $unixTimestamp = strtotime($day);
      
            $dayOfWeek = date("l", $unixTimestamp);
      
            if ($dayOfWeek == $s_day){
                // echo $day ."is a". $dayOfWeek."<br>";
                $dbl_check = DB::table('tbl_holiday')->whereDate('date',$day)->get();
                if(count($dbl_check) < 1){
                  DB::table('tbl_holiday')->insert([
                      'date'=>$day,
                      'description'=>'Weekly Holiday',
                      'entry_date'=>$curDateTime
                  ]);
                }
            }
        }
    }

    return redirect('/setting/holiday')->with(['message'=>'Data Inserted Successfully','status'=>'alert-success']);
  }

  public function holidayDateStore(Request $r){
    // return $r->all();
    $curDateTime = Date('Y-m-d H:i:s');
    $description = $r->description;
    if($r->checkbox == 'yes'){
      
      $from_date = Date('Y-m-d',strtotime($r->from_date));
      $to_date = Date('Y-m-d',strtotime($r->to_date));
      $date_from = strtotime($from_date);
      $date_to = strtotime($to_date);
      for ($i=$date_from; $i<=$date_to; $i+=86400) {
        $day = date("Y-m-d", $i);
        $dbl_check = DB::table('tbl_holiday')->whereDate('date',$day)->get();
        if(count($dbl_check) < 1){
          DB::table('tbl_holiday')->insert([
                'date'=>$day,
                'description'=>$description,
                'entry_date'=>$curDateTime
            ]);
        }
      }
      $msg = true;
    }
    else{
      $from_date = Date('Y-m-d',strtotime($r->from_date));
      $dbl_check = DB::table('tbl_holiday')->whereDate('date',$from_date)->get();
      if(count($dbl_check) < 1){

        DB::table('tbl_holiday')->insert([
              'date'=>$from_date,
              'description'=>$description,
              'entry_date'=>$curDateTime
          ]);
      }
      else{
        return redirect('/setting/holiday')->with(['message'=>'Data Already Exist','status'=>'alert-warning']);
      }
    }


    return redirect('/setting/holiday')->with(['message'=>'Data Inserted Successfully','status'=>'alert-success']);
  }

  public function holidayDateDestroy($id){
    $is_delete = DB::table('tbl_holiday')->where('hday_id',$id)->delete();
    if($is_delete){
      return redirect('/setting/holiday')->with(['message'=>'Data Deleted Successfully','status'=>'alert-success']);
    }
    else{
      return redirect('/setting/holiday')->with(['message'=>'Data Couldn\'t Delete','status'=>'alert-danger']);
    }
  }




}