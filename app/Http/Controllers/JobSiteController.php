<?php

namespace App\Http\Controllers;

use App\Models\Jobsite;
use App\Models\Jobsite_machine;
use App\Models\obra;
use App\Models\Province;
use Illuminate\Http\Request;

class JobSiteController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $jobsites = Jobsite::all();

    return view('jobSites.index', compact('jobsites'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $provinces = Province::pluck('name', 'id')->toArray();
    return view('jobSites.create', compact('provinces'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      'province_id' => 'required',

      'start_date' => 'required|date',

      'location' => 'required|max:255'
    ]);
    $jobsite = [
      'province_id' => $request['province_id'],
      'start_date' => $request['start_date'],
      'end_date' => $request['end_date'],
      'location' => $request['location']
    ];
    //DD($jobsite);
    Jobsite::create($jobsite);
    return redirect()->route('jobsites.index')->with('success', 'Job Site created successfully!');
    //
  }

  /**
   * Display the specified resource.
   */
  public function show($id)
  {

    
        $jobsite = Jobsite::with(['machines', 'province'])->findOrFail($id);
        $machines = $jobsite->machines()
            ->wherePivot('status', 'active')
            ->orderBy('jobsite_machines.start_date', 'desc')
            ->get();
        $jobSiteMachines = Jobsite_machine::where('jobsite_id', $jobsite->id)
            ->where('status', 'pending')
            ->with('machine')
            ->get();

        // Add maintenance warning status for each machine
        $machinesWithWarnings = $machines->map(function ($machine) {
            $machine->needsMaintenanceWarning = $machine->needsMaintenanceWarning();
            return $machine;
        });

    return view('jobSites.show', compact('jobsite', 'jobSiteMachines','machinesWithWarnings'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    $jobsite = Jobsite::find($id);
    $provinces = Province::pluck('name', 'id')->toArray();
    return view('jobSites.edit', compact('jobsite', 'provinces'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, $id)
  {
    $request->validate([
      'location' => 'required|max:255',
      'province_id' => 'required',
      'start_date' => 'required',
      'end_date' => 'required'
    ]);
    $jobsite = Jobsite::find($id);
    $jobsite->update($request->all());
    return redirect()->route('jobsites.index')
      ->with('success', 'job site updated successfully.');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id)
  {
    $Jobsite = Jobsite::where('id', $id)->firstOrFail(); // Find the post by ID

    $Jobsite->delete(); // Delete the post

    //
    return redirect()->route('jobsites.index')->with('success', 'Machine deleted successfully!');
  }
}
