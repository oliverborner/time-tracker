<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonthClosure extends Model {
  use HasFactory;
  protected $fillable = ['year','month','summary','closed_by'];
  protected $casts = ['summary' => 'array'];

  public function user()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

}

