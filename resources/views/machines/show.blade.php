<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Machine Details - {{ $machine->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
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

        .loading-spinner {
            display: none;
            text-align: center;
            padding: 10px;
        }
    </style>
</head>

<body>
    <div class="container mt-4 mb-5">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2">Machine Details: {{ $machine->name }}</h1>
            <div class="btn-group">
                <a href="{{ route('machines.index') }}" class="btn btn-outline-secondary"
                    aria-label="Back to machines list">Back to Machines</a>
                <a href="{{ route('machines.edit', $machine->id) }}" class="btn btn-outline-primary"
                    aria-label="Edit machine {{ $machine->name }}">Edit Machine</a>
                <form action="{{ route('machines.destroy', $machine->id) }}" method="POST" style="display:inline;"
                    onsubmit="return confirm('Are you sure you want to delete {{ $machine->name }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                        aria-label="Delete machine {{ $machine->name }}">Delete Machine</button>
                </form>
            </div>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card-body">
            @if ($needsMaintenanceWarning)
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Maintenance Warning:</strong> This machine has traveled
                    {{ number_format($machine->kilometersSinceLastMaintenance(), 2) }} km since its last maintenance,
                    exceeding the {{ number_format(\App\Models\Machine::KILOMETER_THRESHOLD, 2) }} km threshold.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                   
                </div>
            @endif
            <!-- Machine Details Card -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header">Machine Information</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5><strong>Name:</strong> {{ $machine->name ?? 'N/A' }}</h5>
                            <h5><strong>Type:</strong> {{ $machineType->name ?? 'N/A' }}</h5>
                            <h5><strong>Kilometers:</strong> {{ number_format($machine->kilometers() ?? 0, 2) }}</h5>
                         <h5> <a href="{{ route('maintenances.create',['machine_id' => $machine->id]) }}" class="btn btn-primary mb-3">New Maintenance</a><h5>

                        </div>
                        <div class="col-md-6">

                            <h5><strong>Current Job Site:</strong> {{ $jobsite->location ?? 'N/A' }}</h5>
                            <h5><strong>Province:</strong> {{ $jobsite->province->name ?? 'N/A' }}</h5>
                            <h5>
                                <strong>From:</strong>
                                {{ isset($jobsite->pivot) && !is_null($jobsite->pivot->start_date) ? \Carbon\Carbon::parse($jobsite->pivot->start_date)->format('d/m/Y') : 'N/A' }}
                                <strong>to:</strong>
                                {{ isset($jobsite->pivot) && !is_null($jobsite->pivot->end_date) ? \Carbon\Carbon::parse($jobsite->pivot->end_date)->format('d/m/Y') : 'N/A' }}
                            </h5>
                            @if (isset($jobsite->pivot))
                                <h5><a href="{{ route('jobsitemachines.conclude', $jobsite->pivot->id) }}"
                                        class="btn btn-info btn-sm">conclude Job</a> </h5>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
            <!-- pending jobs -->
            <div class="card mb-4 shadow-sm">
                <a href="{{ route('jobsitemachines.create', ['machine_id' => $machine->id, 'back' => url()->current()]) }}"
                    class="btn btn-warning btn-sm">Assign machin to a Jobsite</a>


                <div class="card-header">Pending jobs</div>
                <div class="card-body p-0">
                    @if ($jobsites->isEmpty())
                        <div class="alert alert-info m-3">No job site history available.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped history-table mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Location</th>
                                        <th scope="col">Province</th>
                                        <th scope="col">Start Date</th>
                                        <th scope="col">End Date</th>
                                        <th scope="col">Actions</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jobsites as $job)
                                        @if ($job->pivot->status === 'pending')
                                            <tr>
                                                <td>{{ $job->location ?? 'N/A' }}</td>
                                                <td>{{ $job->province->name ?? 'N/A' }}</td>
                                                <td>{{ $job->pivot->start_date ? \Carbon\Carbon::parse($job->pivot->start_date)->format('d/m/Y') : 'N/A' }}
                                                </td>
                                                <td>{{ $job->pivot->end_date ? \Carbon\Carbon::parse($job->pivot->end_date)->format('d/m/Y') : 'N/A' }}
                                                </td>

                                                <td>
                                                    <a href="{{ route('jobsitemachines.edit', $job->pivot->id) }}"
                                                        class="btn btn-warning btn-sm">Edit</a>

                                                    <form
                                                        action="{{ route('jobsitemachines.activate', $job->pivot->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-info btn-sm"
                                                            onclick="return confirm('Are you sure you want to activate this assignment?')">
                                                            Activate
                                                        </button>
                                                    </form>
                                                    <form
                                                        action="{{ route('jobsitemachines.destroy', $job->pivot->id) }}"
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
            <!-- completed jons Card -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header">Completed jobs</div>
                <div class="card-body p-0">
                    @if ($jobsites->isEmpty())
                        <div class="alert alert-info m-3">No job site history available.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped history-table mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Location</th>
                                        <th scope="col">Province</th>
                                        <th scope="col">Start Date</th>
                                        <th scope="col">End Date</th>

                                        <th scope="col">Kilometers</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jobsites as $job)
                                        @if ($job->pivot->status == 'completed')
                                            <tr>
                                                <td>{{ $job->location ?? 'N/A' }}</td>
                                                <td>{{ $job->province->name ?? 'N/A' }}</td>
                                                <td>{{ $job->pivot->start_date ? \Carbon\Carbon::parse($job->pivot->start_date)->format('d/m/Y') : 'N/A' }}
                                                </td>
                                                <td>{{ $job->pivot->end_date ? \Carbon\Carbon::parse($job->pivot->end_date)->format('d/m/Y') : 'N/A' }}
                                                </td>

                                                <td>{{ number_format($job->pivot->kilometers ?? 0, 2) }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    @endif
                </div>
            </div>

            <!-- Maintenance History Card -->
            <div class="card shadow-sm">
                <a href="{{ route('maintenances.create', ['machine_id' => $machine->id, 'back' => url()->current()]) }}"
                    class="btn btn-primary mb-3">New Maintenance</a>

            </div>
            <div class="card shadow-sm">
                <div class="card-header">Maintenance History
                </div>


                <div class="card-body">
                    <!-- Filter Controls -->
                    <div class="mb-3">
                        <label for="maintenance_filter" class="form-label">Filter by Status</label>
                        <select name="maintenance_filter" id="maintenance_filter" class="form-select">
                            <option value="">All Maintenances</option>
                            <option value="completed">Completed</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>

                    <!-- Loading Spinner -->
                    <div class="loading-spinner">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <!-- Maintenance Table -->
                    <div class="table-responsive">
                        <table class="table table-striped" id="maintenances-table">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">status</th>
                                    <th scope="col">Starting</th>
                                    <th scope="col">Finalization</th>
                                    <th scope="col">Kilometers</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="maintenances-body">
                                @forelse ($maintenances as $maintenance)
                                    <tr>
                                        <td>{{ $maintenance->id }}</td>
                                        <td>{{ $maintenance->description }}</td>
                                        <td>{{ $maintenance->status }}
                                        <td>{{ \Carbon\Carbon::parse($maintenance->start_date)->format('d/m/Y') }}
                                        </td>
                                        <td>{{ $maintenance->end_date ? \Carbon\Carbon::parse($maintenance->end_date)->format('d/m/Y') : 'N/A' }}
                                        </td>
                                        <td>{{ number_format($maintenance->kilometers, 2) }}</td>
                                        <td>
                                            <a href="{{ route('maintenances.show', $maintenance->id) }}"
                                                class="btn btn-info btn-sm"
                                                aria-label="View maintenance {{ $maintenance->id }}">View</a>
                                            <a href="{{ route('maintenances.edit', $maintenance->id) }}"
                                                class="btn btn-warning btn-sm"
                                                aria-label="Edit maintenance {{ $maintenance->id }}">Edit</a>
                                            <form action="{{ route('maintenances.destroy', $maintenance->id) }}"
                                                method="POST" style="display:inline;"
                                                onsubmit="return confirm('Are you sure you want to delete this maintenance?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    aria-label="Delete maintenance {{ $maintenance->id }}">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No maintenances found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
        </script>
        <script>
            $(document).ready(function() {
                let debounceTimer;

                $('#maintenance_filter').change(function() {
                    const filter = $(this).val();
                    clearTimeout(debounceTimer);

                    // Show loading spinner
                    $('.loading-spinner').show();
                    $('#maintenances-body').html(
                        '<tr><td colspan="6" class="text-center">Loading...</td></tr>');

                    debounceTimer = setTimeout(() => {
                        $.ajax({
                            url: '{{ route('maintenances.filter') }}',
                            method: 'GET',
                            data: {
                                machine_id: {{ $machine->id }},
                                status: filter
                            },
                            success: function(response) {
                                $('#maintenances-body').html(response);
                                $('.loading-spinner').hide();
                            },
                            error: function(xhr, status, error) {
                                console.log('Error:', xhr.status, status, error);
                                console.log('Response:', xhr.responseText);
                                console.log('Request URL:',
                                    '{{ route('maintenances.filter') }}');
                                console.log('Filter sent:', filter);
                                let errorMessage =
                                    'Error fetching maintenances. Please try again.';
                                if (xhr.status === 404) errorMessage =
                                    'Filter endpoint not found. Contact support.';
                                else if (xhr.status === 500) errorMessage =
                                    'Server error occurred. Please try again later.';
                                $('#maintenances-body').html(
                                    `<tr><td colspan="6" class="text-center text-danger">${errorMessage}</td></tr>`
                                );
                                $('.loading-spinner').hide();
                            }
                        });
                    }, 300); // Debounce delay
                });
            });
        </script>
</body>

</html>
