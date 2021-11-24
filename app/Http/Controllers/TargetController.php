<?php

namespace App\Http\Controllers;

use App\Models\Target;
use Illuminate\Http\Request;

class TargetController extends Controller
{
  public function getTarget()
  {
    $target = Target::all()->last();
    return response($target?->value);
  }

  public function setTarget(Request $request)
  {
    $target = new Target();
    $target->value = floatval($request->input('value'));
    $target->save();
    return response('ok');
  }
}
