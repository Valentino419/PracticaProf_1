<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Machine;
use App\Models\MachineType;
use App\Models\Maintenance;
use Illuminate\Http\Request;

class MachineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $machines = Machine::with(['jobsites' => function ($query) {
            $query->wherePivot('end_date', null);
        }])->first()->get();
        return view('machines.index', compact('machines'));
    }
    public function filter(Request $request)
    {
        $machineTypeId = $request->query('machine_type_id');

        $query = Machine::query();
        if ($machineTypeId) {
            $query->where('machine_type_id', $machineTypeId);
        }

        $maintenances = $query->paginate(10);
        $html = view('machines.partials.table_body', compact('machines'))->render();
        return response($html);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $machineTypes = MachineType::pluck('name', 'id')->toArray();

        return view('machines.create', compact('machineTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'machine_type_id' => 'required',
        ]);
        $machine = [
            'machine_type_id' => $request['machine_type_id'],
            'name' => $request['name']
        ];
        Machine::create($machine);
        return redirect()->route('machines.index')->with('success', 'Machine created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $machine = Machine::find($id);
        $jobsite = $machine->jobsites()
            ->with('province')
            ->wherePivot('status', 'active')
            ->first();
        $jobsites = $machine->jobsites()->orderBy('jobsite_machines.start_date', 'desc')->get();
        $machineType = $machine->machineType;
        
        $maintenances = $machine->maintenances()->orderBy('start_date', 'desc')->get();
          $needsMaintenanceWarning = $machine->needsMaintenanceWarning();

        return view('machines.show', compact('machine', 'jobsite', 'machineType', 'jobsites', 'maintenances','needsMaintenanceWarning')); //, compact(), compact(), compact('));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $machineTypes = MachineType::pluck('name', 'id')->toArray();
        $machine = Machine::find($id);
        return view('machines.edit', compact('machine'), compact('machineTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'machine_type_id' => 'required',


        ]);
        $machine = Machine::find($id);
        $machine->update($request->all());
        return redirect()->route('machines.index')
            ->with('success', 'machine updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $machine = Machine::where('id', $id)->firstOrFail();
        $machine->delete();

        return redirect()->route('machines.index')->with('success', 'Machine deleted successfully!');
    }


    public function currentProvince($id)
    {
        $validated = validator(['id' => $id], [
            'id' => 'required|exists:machines,id',
        ])->validate();

        // Find the machine
        $machine = Machine::findOrFail($id);

        // Get the active jobsite with its province
        $jobsite = $machine->jobsites()
            ->with('province')
            ->wherePivot('end_date', null)
            ->first();

        // Check if jobsite and province exist
        if (!$jobsite || !$jobsite->province) {
            return response()->json(['message' => 'not asigned'], 404);
        }

        return $jobsite->province;
    }
    public function currentJobsite($id)
    {
        $validated = validator(['id' => $id], [
            'id' => 'required|exists:machines,id',
        ])->validate();

        // Find the machine
        $machine = Machine::findOrFail($id);

        // Get the active jobsite with its province
        $jobsite = $machine->jobsites()
            ->with('province')
            ->wherePivot('end_date', null)
            ->first();

        // Check if jobsite and province exist
        if (!$jobsite) {
            return response()->json(['message' => 'No active jobsite found'], 404);
        }

        return $jobsite;
    }
    public function getMaintenances($id)
    {
        $validated = validator(['id' => $id], [
            'id' => 'required|exists:machines,id',
        ])->validate();

        $machine = Machine::findOrFail($id);
        $maintenaces = $machine->maintenance()->get();
        if (!$maintenaces) {
            return response()->json(['message' => 'No maintencance recorde'], 404);
        }
        return $maintenaces;
    }
    
}
