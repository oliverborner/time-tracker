<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TimeEntry extends Model {
  use HasFactory;
  protected $fillable = ['project_id','user_id','day','hours','time_input','note','locked'];
  protected $casts = ['day' => 'date', 'hours' => 'float', 'locked' => 'boolean'];

  public function project() { return $this->belongsTo(Project::class); }
  public function user() { return $this->belongsTo(User::class); }

}
