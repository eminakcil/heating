<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\Temperature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatusController extends Controller
{
  public function getStatus()
  {
    $status = Status::all()->last();
    $value = 'off';
    if ($status) {
      $value = $status->value;
    }
    return response($value);
  }

  public function setStatus(Request $request)
  {
    $status = Status::all()->last();
    if (empty($status) || ($request->input('value') == 'on') != $status->value) {
      $status = new Status();
      $status->value = $request->input('value') == 'on';
      $status->save();
      return response('ok');
    }
    return response('no');
  }

  public function getStatuses()
  {
    $values = DB::table('statuses')
      ->orderByDesc('created_at')
      ->get();
    return response($values);
  }
}
