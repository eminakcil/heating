<?php

namespace App\Http\Controllers;

use App\Models\Relay;
use App\Models\Status;
use App\Models\Target;
use App\Models\Temperature;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RelayController extends Controller
{
  public function getRelayStatus()
  {
    $value = false;
    // status 
    $status = Status::all()->last();
    if (empty($status) || $status->value) {
      // temperature
      $temperature = Temperature::all()->last();
      $targetTemperature = Target::all()->last();
      if (isset($targetTemperature) && $temperature->value < $targetTemperature->value) {
        Relay::toggleOn();
        $value = true;
      } else {
        Relay::toggleOff();
        $value = false;
      }
    } else {
      Relay::toggleOff();
      $value = false;
    }

    return response($value ? '1' : '0');
  }

  public function getRelayStatuses(){
    $values = DB::table('relays')
      ->select(DB::raw('id, value, created_at, date_format(created_at, \'%H:%i\') as created'))
      ->where('created_at', '>', Carbon::now()->subHours(4))
      ->orderBy('created_at')
      ->get();
    return response($values);
  }
}
