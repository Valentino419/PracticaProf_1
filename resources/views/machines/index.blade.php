<!-- resources/views/machines/index.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Machines</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Machines</h1>
        <a href="{{ route('machines.create') }}" class="btn btn-outline-secondary">Create New Machine</a>
        <a href="{{ route('jobsites.index') }}" class="btn btn-outline-secondary">View Active Job Sites</a>
        
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Machine Name</th>
                    <th>Machine Type</th>
                    <th>Current Province</th>
                    <th>Jobsite</th>
                 
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($machines as $machine)
                    <tr>
                        <td>{{ $machine->id }}</td>
                        <td>{{ $machine->name }}</td>
                        <td>{{ $machine->machineType->name }}</td>
                        <td>
                            @if ($machine->jobsites->isNotEmpty())
                                @foreach ($machine->jobsites as $jobsite)
                                    @if($jobsite->pivot->end_date==null)
                                    {{ $jobsite->province->name ?? 'N/A' }}@endif
                                  
                                @endforeach
                            @else
                                Not Assigned
                            @endif
                        </td>
                        <td>
                            @if ($machine->jobsites->isNotEmpty())
                                @foreach ($machine->jobsites as $jobsite)
                                  @if($jobsite->pivot->end_date==null)
                                    {{ $jobsite->location }}
                                    @endif
                                @endforeach
                            @else
                                None
                            @endif
                        </td>
                     
                        <td>
                            <a href="{{ route('machines.show', $machine->id) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('machines.edit', $machine->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            
                            <form action="{{ route('machines.destroy', $machine->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No machines found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>