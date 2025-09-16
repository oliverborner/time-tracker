<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTimeEntryRequest extends FormRequest {
  public function authorize() { return true; } 
  public function rules() {
    return [
      'project_id' => ['required','exists:projects,id'],
      'day' => ['required','date'],
      'time_input' => ['required','string'],
      'note' => ['nullable','string'],
    ];
  }
}
