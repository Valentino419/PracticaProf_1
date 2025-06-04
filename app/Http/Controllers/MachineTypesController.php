<?php

namespace App\Http\Controllers;
use App\Models\Machine;
use App\Models\MachineType;
//use App\Models\tipos;
use Illuminate\Http\Request;

class MachineTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $machinetypes= MachineType::all();
        return view('machinetypes.index',compact('machinetypes'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      return view('machinetypes.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $machineType=[
            'name'=>$request['name'],
            'description'=>$request['description']];
        MachineType::create($machineType);
        return redirect()->route('machinetypes.index')->with('success', 'Machine Type created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
         $machinetype = MachineType::find($id);
         $machines = $machinetype->machines()->get();      
              
         return view('machinetypes.show', compact('machines','machinetype'));//, compact(), compact(), compact('));
       
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        
        $machinetype = MachineType::find($id);
     return view('machinetypes.edit', compact('machinetype'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
       $request->validate([
      'name' => 'required|max:255',
      'description' => 'required|max:255',
      ]);

      $machinetypes = MachineType::find($id);
    $machinetypes->update($request->all());
    return redirect()->route('machinetypes.index')
      ->with('success', 'machine Type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
          $machinetype = MachineType::where('id', $id)->firstOrFail(); // Find the post by ID
        
        $machinetype->delete(); // Delete the post
        
      //
        return redirect()->route('machinetypes.index')->with('success', 'Machine Type deleted successfully!');
   
    }

    public function getMachines($id){
        $validated = validator(['id' => $id], [
        'id' => 'required|exists:machine_types,id',
    ])->validate();
        $MachineType = MachineType::find($id);
        $machines= $MachineType->machines()->get();
        if ($machines->isEmpty()) {
            return response()->json(['message' => 'No machine recorde'], 404);
        }
        return $machines;
    }
}
