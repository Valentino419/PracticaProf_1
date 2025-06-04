<!-- resources/views/machines/index.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Machine Types</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Machine Types</h1>
        <a href="{{ route('machinetypes.create') }}" class="btn btn-primary mb-3">Create New Machine Type</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    
                 
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($machinetypes as $machinetype)
                    <tr>
                        <td>{{ $machinetype->id }}</td>
                        <td>{{ $machinetype->name }}</td>
                        <td>{{ $machinetype->description }}</td>
                        
                       
                     
                        <td>
                            <a href="{{ route('machinetypes.show', $machinetype->id) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('machinetypes.edit', $machinetype->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('machinetypes.destroy', $machinetype->id) }}" method="POST" style="display:inline;">
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