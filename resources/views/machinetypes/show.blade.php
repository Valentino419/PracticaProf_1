<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Machine Details - {{ $machinetype->name }}</title>
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
            <h1 class="h2">Machine Type Details: {{ $machinetype->name }}</h1>
            <div class="btn-group">
                <a href="{{ route('machinetypes.index') }}" class="btn btn-outline-secondary"
                    aria-label="Back to machine types list">Back to machine types</a>
                <a href="{{ route('machinetypes.edit', $machinetype->id) }}" class="btn btn-outline-primary"
                    aria-label="Edit machine type {{ $machinetype->name }}">Edit Machine</a>
                <form action="{{ route('machinetypes.destroy', $machinetype->id) }}" method="POST"
                    style="display:inline;"
                    onsubmit="return confirm('Are you sure you want to delete {{ $machinetype->name }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                        aria-label="Delete machine type type {{ $machinetype->name }}">Delete Machine</button>
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

        <!-- Machine Details Card -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header">Machine Type Information</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5><strong>Name:</strong> {{ $machinetype->name ?? 'N/A' }}</h5>
                        <h5><strong>description:</strong> {{ $machinetype->description ?? 'N/A' }}</h5>
                    </div>
                </div>
            </div>
        </div>


        <!-- Maintenance History Card -->
        <div class="card shadow-sm">
            <div class="card-header">list of machines </div>
            <div class="card-body">


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
                                <th scope="col">name</th>
                                <th scope="col">current jobsite</th>
                                <th scope="col">fecha finalizacion</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="maintenances-body">
                            @forelse ($machines as $machine)
                                <tr>
                                    <td>{{ $machine->id }}</td>
                                    <td>{{ $machine->name }}</td>

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
                                        <a href="{{ route('machines.show', $machine->id) }}"
                                            class="btn btn-info btn-sm"
                                            aria-label="View machine {{ $machine->id }}">View</a>
                                        <a href="{{ route('machines.edit', $machine->id) }}"
                                            class="btn btn-warning btn-sm"
                                            aria-label="Edit machine {{ $machine->id }}">Edit</a>
                                        <form action="{{ route('machines.destroy', $machine->id) }}" method="POST"
                                            style="display:inline;"
                                            onsubmit="return confirm('Are you sure you want to delete this maintenance?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                aria-label="Delete maintenance {{ $machine->id }}">Delete</button>
                                        </form>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No machine found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <a href="{{ route('machines.create') }}" class="btn btn-primary mb-3">Create New Machine</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>

</body>

</html>
