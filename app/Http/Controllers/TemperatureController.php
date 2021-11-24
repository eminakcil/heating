<?php

namespace App\Http\Controllers;

use App\Models\Temperature;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TemperatureController extends Controller
{

  public function getTemperature()
  {
    $temperature = Temperature::all()->last();
    return response($temperature?->value);
  }

  public function create(Request $request)
  {
    if ($request->input('value') && $request->input('millis')) {
      $temperature = Temperature::all()->last();
      if (empty($temperature) || $temperature->value != floatval($request->input('value'))) {
        $temperature = new Temperature();
        $temperature->value = floatval($request->input('value'));
        $temperature->millis = intval($request->input('millis'));
        $temperature->save();
        return response('ok');
      }
      return response('no');
    }
    return response('no');
  }

  public function getTemperatures()
  {
    $temperatures = DB::table('temperatures')
      ->select(DB::raw('id, value, created_at, date_format(created_at, \'%H:%i\') as created'))
      ->where('created_at', '>', Carbon::now()->subHours(4))
      ->groupByRaw('created')
      ->orderBy('created')
      ->get();

    return response($temperatures);
  }
}
