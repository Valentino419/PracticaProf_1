<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\Maintenance;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $machines = Machine::pluck('name', 'id')->toArray();
        $maintenances = Maintenance::orderBy('start_date', 'desc')->get();


        return view('maintenances.index', compact('machines', 'maintenances'));
    }

    public function filter(Request $request)
    {
        $machineId = $request->query('machine_id');
        $status = $request->query('status');
        $query = Maintenance::query();
        if ($machineId) {
            $query->where('machine_id', $machineId);
        }
        if ($status) {
            $query->where('status', $status);
        }
        $maintenances = $query->paginate(10);
        $html = view('maintenances.partials.table_body', compact('maintenances'))->render();
        return response($html);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {   
        $machines = Machine::pluck('name', 'id')->toArray();
    
         $preselected_machine_id = $request->query('machine_id');
       
        return view('maintenances.create', compact('machines','preselected_machine_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'machine_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'description' => 'required|max:255',
            'kilometers' => 'required',
            'status'=>'required'
        ]);

        $maintenance = [
            'machine_id' => $request['machine_id'],
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],
            'description' => $request['description'],
            'kilometers' => $request['kilometers'],
            'status' => $request['status']
        ];

        Maintenance::create($maintenance);
        return redirect()->route('machines.show',$request->machine_id)->with('success', 'maintenance added correctly'); 
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $maintenance = Maintenance::find($id);
        $machine = $maintenance->machine()->get();
        return view('maintenances.show', compact('maintenance', 'machine'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $machines = Machine::pluck('name', 'id')->toArray();
        $maintenance = Maintenance::find($id);
        return view('maintenances.edit', compact('machines', 'maintenance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'machine_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'description' => 'required|max:255',
            'kilometers' => 'required',
            'status'=>'required'
        ]);

       $maintenance = Maintenance::find($id);
    $maintenance->update($request->all());
    return redirect()->route('machines.show',$maintenance->machine_id)
      ->with('success', 'maintenance updated successfully.');
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $maintenance = Maintenance::where('id', $id)->firstOrFail(); // Find the post by ID

        $maintenance->delete(); // Delete the post

        //
        return redirect()->route('maintenances.index')->with('success', 'mantenimento deleted successfully!');
    }
}
