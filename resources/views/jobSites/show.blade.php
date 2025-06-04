<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job site Details - {{ $jobsite->location }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-header {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .btn-group .btn {
            margin-right: 5px;
        }

        .history-table th,
        .history-table td {
            vertical-align: middle;
        }

        .alert {
            margin-top: 1rem;
        }
    </style>
</head>

<body>
    <div class="container mt-4 mb-5">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2">Job site Details: {{ $jobsite->name }}</h1>
            <div class="btn-group">
                <a href="{{ route('jobsites.index') }}" class="btn btn-outline-secondary">Back to active Job site</a>
                <a href="{{ route('jobsites.edit', $jobsite->id) }}" class="btn btn-outline-primary">Edit Jobsite</a>
                <form action="{{ route('jobsites.destroy', $jobsite->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete {{ $jobsite->name }}?')">Delete Job
                        Site</button>
                </form>
            </div>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Machine Details Card -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header">
                Job site Information
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5><strong>Location:</strong> {{ $jobsite->location ?? 'N/A' }}</h5>
                        <h5><strong>Province:</strong> {{ $jobsite->province->name ?? 'N/A' }}</h5>
                    </div>
                    <div class="col-md-6">
                        <h5><strong>Starting date:</strong> {{ $jobsite->start_date ?? 'N/A' }}</h5>
                        <h5><strong>finshing date:</strong> {{ $jobsite->end_date ?? 'N/A' }}</h5>

                    </div>
                </div>
            </div>
        </div>

        <!-- active assigments -->

        <div class="card mb-4 shadow-sm">
            <div class="card-header">
                Actives machines on site

            </div>
            <div class="card-body p-0">
                @if ($jobSiteMachines->isEmpty())
                    <div class="alert alert-info m-3">No pending jobs available.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped history-table mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Machine</th>
                                    <th scope="col">description</th>
                                    <th scope="col">maintenace</td>
                                    <th scope="col">Start Date</th>
                                    <th scope="col">End Date</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jobSiteMachines as $job)
                                    @if ($job->status == 'active')
                                        <tr>
                                            <td scope="row">{{ $job->machine->name ?? 'N/A' }}</td>
                                            <td> {{ $job->description }}
                                            <td>
                                                @if ($machine->needsMaintenanceWarning)
                                                    <span class="badge bg-warning">Needs Maintenance
                                                        ({{ number_format($machine->kilometersSinceLastMaintenance(), 2) }}
                                                        km)</span>
                                                @else
                                                    <span class="badge bg-success">OK</span>
                                                @endif
                                            </td>
                                            <td>{{ $job->start_date ? \Carbon\Carbon::parse($job->start_date)->format('d/m/Y') : 'N/A' }}
                                            </td>
                                            <td>{{ $job->end_date ? \Carbon\Carbon::parse($job->end_date)->format('d/m/Y') : 'N/A' }}
                                            </td>
                                            <td>
                                                <a href="{{ route('machines.show', $job->machine->id) }}" class="btn btn-info btn-sm">View</a>
                                                <a href="{{ route('jobsitemachines.edit', $job->id) }}"
                                                    class="btn btn-warning btn-sm">Edit</a>
                                                <a href="{{ route('jobsitemachines.conclude', $job->id) }}"
                                                    class="btn btn-info btn-sm">conclude Job</a>
                                                <form action="{{ route('jobsitemachines.destroy', $job->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Are you sure?')">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
        <!-- Pending Jobs -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header">
                Pending Jobs
                <a href="{{ route('jobsitemachines.create', ['jobsite_id' => $jobsite->id, 'back' => url()->current()]) }}"
                    class="btn btn-warning btn-sm float-end">Assign Machine to Jobsite</a>
            </div>
            <div class="card-body p-0">
                @if ($jobSiteMachines->isEmpty())
                    <div class="alert alert-info m-3">No pending jobs available.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped history-table mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Machine</th>
                                    <th scope="col">description</th>
                                    <th scope="col">Start Date</th>
                                    <th scope="col">End Date</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jobSiteMachines as $job)
                                    @if ($job->status == 'pending')
                                        <tr>
                                            <td scope="row">{{ $job->machine->name ?? 'N/A' }}</td>
                                            <td> {{ $job->description }}
                                            <td>{{ $job->start_date ? \Carbon\Carbon::parse($job->start_date)->format('d/m/Y') : 'N/A' }}
                                            </td>
                                            <td>{{ $job->end_date ? \Carbon\Carbon::parse($job->end_date)->format('d/m/Y') : 'N/A' }}
                                            </td>
                                            <td>
                                                <a href="{{ route('machines.show', $job->machine->id) }}" class="btn btn-info btn-sm">View</a>
                                                <a href="{{ route('jobsitemachines.edit', $job->id) }}"
                                                    class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('jobsitemachines.activate', $job->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-info btn-sm"
                                                        onclick="return confirm('Are you sure you want to activate this assignment?')">
                                                        Activate
                                                    </button>
                                                </form>
                                                <form action="{{ route('jobsitemachines.destroy', $job->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Are you sure?')">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
