<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relay extends Model
{
  use HasFactory;

  protected $fillable = [
    'value',
  ];

  public static function toggleOn()
  {
    $lastRecord = Relay::all()->last();
    if (empty($lastRecord) || !$lastRecord->value) {
      Relay::create([
        'value' => true
      ]);
    }
  }

  public static function toggleOff()
  {
    $lastRecord = Relay::all()->last();
    if (empty($lastRecord) || $lastRecord->value) {
      Relay::create([
        'value' => false
      ]);
    }
  }
}
