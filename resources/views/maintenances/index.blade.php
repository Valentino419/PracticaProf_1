<!DOCTYPE html>
<html>
<head>
    <title>Maintenances</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Maintenances</h1>
        <a href="{{ route('maintenances.create') }}" class="btn btn-primary mb-3">Create New Maintenance</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <select name="machine_id" id="machine_id" class="form-control @error('machine_id') is-invalid @enderror">
            <option value="">Select a Machine</option>
            @foreach ($machines as $machine => $name)
                <option value="{{ $machine }}">{{ $name }}</option>
            @endforeach
        </select>

        <table class="table mt-3" id="maintenances-table">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Description</th>
                    <th>status</th>
                    <th>Starting</th>
                    <th>Finalization</th>
                    <th>Kilometers</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="maintenances-body">
                @forelse ($maintenances as $maintenance)
                    <tr>
                        <td>{{ $maintenance->id }}</td>
                        <td>{{ $maintenance->description }}</td>
                        <td>{{ $maintenance->status }}</td>
                        <td>{{ $maintenance->start_date }}</td>
                        <td>{{ $maintenance->end_date }}</td>
                        <td>{{ $maintenance->kilometers }}</td>
                        <td>
                            <a href="{{ route('maintenances.show', $maintenance->id) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('maintenances.edit', $maintenance->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('maintenances.destroy', $maintenance->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No maintenances found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            $('#machine_id').change(function() {
                var machineId = $(this).val();

                $.ajax({
    
                    url: '{{ route("maintenances.filter") }}', // Define this route in web.php
                    method: 'GET',
                    data: { machine_id: machineId },
                    success: function(response) {
                        $('#maintenances-body').html(response); // Update table body
                    },
                   error: function(xhr, status, error) {
    console.log('Error:', xhr.status, status, error);
    console.log('Response:', xhr.responseText);
    console.log('Request URL:', '{{ route("maintenances.filter") }}');
    console.log('machine_id sent:', machineId);
    alert('Error fetching maintenances: ' + xhr.status + ' ' + error);
}
                    
                });
            });
        });
    </script>
</body>
</html>