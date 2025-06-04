 @forelse ($machines as $machine)
     <tr>
         <td>{{ $machine->id }}</td>
         <td>{{ $machine->name }}</td>
         <!-- <td>{{ $machine->machineType->name }}</td>-->
         <td>
             @if ($machine->jobsites->isNotEmpty())
                 @foreach ($machine->jobsites as $jobsite)
                     @if ($jobsite->pivot->end_date == null)
                         {{ $jobsite->province->name ?? 'N/A' }}
                     @endif
                 @endforeach
             @else
                 Not Assigned
             @endif
         </td>
         <td>
             @if ($machine->jobsites->isNotEmpty())
                 @foreach ($machine->jobsites as $jobsite)
                     @if ($jobsite->pivot->end_date == null)
                         {{ $jobsite->location }}
                     @endif
                 @endforeach
             @else
                 None
             @endif
         </td>

         <td>
            finalizar/modificar asignacion
             <a href="{{ route('machines.show', $machine->id) }}" class="btn btn-info btn-sm">View</a>
             <a href="{{ route('machines.edit', $machine->id) }}" class="btn btn-warning btn-sm">Edit</a>
                 class="btn btn-warning btn-sm">Assign to Jobsite</a>

             <form action="{{ route('machines.destroy', $machine->id) }}" method="POST" style="display:inline;">
                 @csrf
                 @method('DELETE')
                 <button type="submit" class="btn btn-danger btn-sm"
                     onclick="return confirm('Are you sure?')">Delete</button>
             </form>
         </td>
     </tr>
 @empty
     <tr>
         <td colspan="6">No machines found.</td>
     </tr>
 @endforelse
 <div class="mt-3">
     {{ $machines->links() }}
 </div>
