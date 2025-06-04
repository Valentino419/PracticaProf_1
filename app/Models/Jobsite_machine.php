<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jobsite_machine extends Model
{
      protected $fillable = ['machine_id','jobsite_id','start_date','end_date','kilometers','conclusion_reasons','status','description'];
   
    public function machine(){
        return  $this->belongsTo(Machine::class);
    }
    public function jobsite(){
        return $this->belongsTo(Jobsite::class);
    }
    
}
