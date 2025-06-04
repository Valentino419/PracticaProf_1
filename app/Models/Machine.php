<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{

    protected $table = 'machines';
    protected $fillable = ['name', 'machine_type_id'];
    public const KILOMETER_THRESHOLD = 100;
   
    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }
    public function MachineType()
    {
        return $this->belongsTo(MachineType::class);
    }
    public function jobsites()
    {
        return $this->belongsToMany(Jobsite::class, 'jobsite_machines', 'machine_id', 'jobsite_id')
            ->withPivot('start_date', 'end_date', 'kilometers', 'status', 'id');
    }
    public function kilometersSinceLastMaintenance()
    {
        // Get the last completed maintenance
        $lastMaintenance = $this->maintenances()
            ->where('status', 'completed')
            ->orderBy('end_date', 'desc')
            ->first();

        // If no maintenance exists, sum all completed job kilometers
        if (!$lastMaintenance) {
            return $this->jobsites()
                ->wherePivot('status', 'completed')
                ->sum('jobsite_machines.kilometers');
        }

        // Sum kilometers from completed jobs after the last maintenance
        $total= $this->jobsites()
            ->wherePivot('status', 'completed')         
            ->sum('jobsite_machines.kilometers');
        $total=$total - $lastMaintenance->kilometers;
            return  $total;
    }
     public function needsMaintenanceWarning()
    {
        return $this->kilometersSinceLastMaintenance() >= self::KILOMETER_THRESHOLD;
    }
    public function kilometers()
    {
         return $this->jobsites()
                ->wherePivot('status', 'completed')
                ->sum('jobsite_machines.kilometers');
    }
}
