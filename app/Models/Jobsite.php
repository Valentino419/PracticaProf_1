<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jobsite extends Model

{
    
     protected $fillable = ['location', 'province_id','start_date','end_date'];
    public function machines(){
        return $this->belongsToMany(Machine::class, 'jobsite_machines', 'jobsite_id', 'machine_id')
         ->withPivot('start_date', 'end_date', 'kilometers','status','id');
    } 
    public function province(){
        return  $this->belongsTo(Province::class);
    }
    
}
