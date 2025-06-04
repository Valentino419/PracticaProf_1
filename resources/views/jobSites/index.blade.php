<!DOCTYPE html>
<html>

<head>
    <title>Active Job sites </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1>Active Job sites</h1>
        <a href="{{ route('jobsites.create') }}" class="btn btn-outline-secondary">Create New Job Site</a>
        <a href="{{ route('machines.index') }}" class="btn btn-outline-secondary">View Machines</a>   
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Porvince</th>
                    <th>location</th>
                    <th>starting</th>
                    <th>Finalization</th>

                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jobsites as $jobsite)
                    @if (is_null($jobsite->end_date) || \Carbon\Carbon::parse($jobsite->end_date)->isFuture())
                        <tr>



                            <td>{{ $jobsite->id }}</td>
                            <td>{{ $jobsite->province->name }}</td>
                            <td>{{ $jobsite->location }}</td>
                            <td>{{ $jobsite->start_date }}</td>
                            <td>{{ $jobsite->end_date }}</td>
                            <td>
                                <a href="{{ route('jobsites.show', $jobsite->id) }}" class="btn btn-info btn-sm">View</a>
                                <a href="{{ route('jobsites.edit', $jobsite->id) }}"
                                    class="btn btn-warning btn-sm">Edit</a>
                              
                                <form action="{{ route('jobsites.destroy', $jobsite->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endif
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
