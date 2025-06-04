<?php

namespace App\Http\Controllers;

use App\Models\Jobsite_machine;
use App\Models\Jobsite;
use App\Models\Machine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Compound;
use Carbon\Carbon;

class JobSiteMachineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $machines = Machine::pluck('name', 'id')->toArray();
        $jobsites = Jobsite::where(function ($query) {
            $query->whereNull('end_date')
                  ->orWhere('end_date', '>=', Carbon::today());
        })->pluck('location', 'id')->toArray();
        $preselected_jobsite_id = $request->query('jobsite_id');
        $preselected_machine_id = $request->query('machine_id');
        $back_url = $request->query('back', route('jobsites.index'));
        return view('jobsitemachines.create', compact(
            'jobsites',
            'machines',
            'preselected_jobsite_id',
            'preselected_machine_id'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Define base validation rules
        $rules = [
            'jobsite_id' => 'required|exists:jobsites,id',
            'machine_id' => 'required|exists:machines,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:pending,active,completed',
            'description'=>'nullable|string|max:255'
        ];

        // Add conditional validation for completed status
        if ($request->input('status') === 'completed') {
            $rules['kilometers'] = 'required|numeric|min:0';
            $rules['conclusion_reasons'] = 'required|string|max:255';
        }

        $validated = $request->validate($rules);

        // Fetch jobsite and machine
        $jobsite = Jobsite::findOrFail($validated['jobsite_id']);
        $machine = Machine::findOrFail($validated['machine_id']);

        // Check status condition (example: only allow if no 'active' assignment exists)
        if($validated['status']!='pending'){
        $existingAssignment = DB::table('jobsite_machines')
            ->where('machine_id', $validated['machine_id'])
            ->where('status', 'active')
            ->exists();

        if ($existingAssignment) {
            return back()->withErrors(['machine_id' => 'Machine is already assigned to an active jobsite.']);

            // return redirect()->route('jobsitemachines.create')->with('error', 'Machine is already assigned to an active jobsite.');
        }}

        // Check for date conflicts (optional, if dates matter)
        $dateConflict = DB::table('jobsite_machines')
            ->where('machine_id', $validated['machine_id'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date'] ?? $validated['start_date']])
                    ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date'] ?? $validated['start_date']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_date', '<=', $validated['start_date'])
                            ->where('end_date', '>=', $validated['end_date'] ?? $validated['start_date']);
                    });
            })
            ->exists();

        if ($dateConflict) {
            return back()->withErrors(['start_date' => 'Machine is already assigned during the specified dates.']);

            // return redirect()->route('jobsitemachines.create')->with('error', 'Machine is already assigned during the specified dates.');
            // return response()->json(['error' => 'Machine is already assigned during the specified dates.'], 422);
        }

        // Attach the machine to the jobsite with pivot data
        $jobsitemachine = [
            'machine_id' => $machine->id,
            'jobsite_id' => $jobsite->id,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => $validated['status'],
            'kilometers' => $validated['kilometers'] ?? null,
            'conclusion_reasons' => $validated['conclusion_reasons'] ?? null,
        ];
        Jobsite_machine::create($jobsitemachine);
        return redirect()->route('machines.show', $request->machine_id)->with('success', 'Machine assigned successfully.');

        // return redirect()->route('jobsitemachines.create')->with('success', 'Machine assigned to jobsite successfully.');
        // return response()->json(['message' => 'Machine assigned to jobsite successfully.']);

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $aux =  Jobsite_machine::findOrFail($id);
        dd($aux);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        $jobsitemachine = Jobsite_machine::findOrFail($id);
        $machines = Machine::pluck('name', 'id')->toArray();
         $jobsites = Jobsite::where(function ($query) {
            $query->whereNull('end_date')
                  ->orWhere('end_date', '>=', Carbon::today());
        })->pluck('location', 'id')->toArray();
        
        return view('jobsitemachines.edit', compact(
            'jobsites',
            'machines',
            'jobsitemachine'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'jobsite_id' => 'required|exists:jobsites,id',
            'machine_id' => 'required|exists:machines,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:pending,active,completed',
            'description'=>'nullable|string|max:255'
        ];

        // Add conditional validation for completed status
        if ($request->input('status') === 'completed') {
            $rules['kilometers'] = 'required|numeric|min:0';
            $rules['conclusion_reasons'] = 'required|string|max:255';
        }

        $validated = $request->validate($rules);

        // Check if the jobsite_machine record exists
        $jobsiteMachine = Jobsite_machine::findOrFail($id);

        if ($jobsiteMachine->status === 'completed' && in_array($validated['status'], ['pending', 'active'])) {
            return back()->withErrors(['status' => 'Cannot change status from completed to ' . $validated['status'] . '.']);
        }

        // Check for active assignment conflict
        $existingAssignment = Jobsite_machine::where('machine_id', $validated['machine_id'])
            ->where('status', 'active')
            ->where('id', '!=', $id) // Exclude the current record
            ->exists();

        if ($existingAssignment) {
            return back()->withErrors(['machine_id' => 'Machine is already assigned to an active jobsite.']);
        }

        // Check for date conflicts
        $dateConflict = Jobsite_machine::where('machine_id', $validated['machine_id'])
            ->where('id', '!=', $id)//->where('status', 'completed') // Exclude the current record and completed jobs
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date'] ?? $validated['start_date']])
                    ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date'] ?? $validated['start_date']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_date', '<=', $validated['start_date'])
                            ->where('end_date', '>=', $validated['end_date'] ?? $validated['start_date']);
                    });
            })
            ->exists();

        if ($dateConflict) {
            return back()->withErrors(['start_date' => 'Machine is already assigned during the specified dates.']);
        }

        // Update the record with validated data
        $jobsiteMachine->update([
            'jobsite_id' => $validated['jobsite_id'],
            'machine_id' => $validated['machine_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => $validated['status'],
            'kilometers' => $validated['kilometers'] ?? null,
            'conclusion_reasons' => $validated['conclusion_reasons'] ?? null,
        ]);

        return redirect()->route('machines.show', $jobsiteMachine->machine_id)->with('success', 'Assignment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jobsitemachine = Jobsite_machine::where('id', $id)->firstOrFail(); // Find the post by ID

        $jobsitemachine->delete();
        return back()->with('success', 'Assignment deleted successfully.');
        //
    }
    /**
     * show for concluding a job machine
     */
    public function conclution($id)
    {

        $jobsitemachine = Jobsite_machine::findOrFail($id);

        return view('jobsitemachines.conclusion', compact('jobsitemachine'));
    }

    public function activate($id)
    {
        // Find the record or fail
        $jobsiteMachine = Jobsite_machine::findOrFail($id);

        // Validate current status
        if ($jobsiteMachine->status !== 'pending') {
            return back()->withErrors(['status' => 'Only pending assignments can be activated.']);
        }
        $existingAssignment = Jobsite_machine::where('machine_id', $jobsiteMachine['machine_id'])
            ->where('status', 'active')
            ->where('id', '!=', $id) // Exclude the current record
            ->exists();

        if ($existingAssignment) {
            return back()->withErrors(['machine_id' => 'Machine is already assigned to an active jobsite.']);
        }
        // Update the status
        $jobsiteMachine->update([
            'status' => 'active',
        ]);

        return back()->with('success', 'Assignment activated successfully.');
    }
}
