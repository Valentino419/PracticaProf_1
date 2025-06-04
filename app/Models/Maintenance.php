<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;
     protected $fillable = ['machine_id', 'description', 'start_date', 'end_date', 'kilometers', 'status'];
    public function machine(){
        return $this->belongsTo(Machine::class);
    }

   
}
