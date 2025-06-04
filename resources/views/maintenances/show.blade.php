<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Details - {{ $maintenance->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>
        .card-header {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .btn-group .btn {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container mt-4 mb-5">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2">Maintenance Details: {{ $maintenance->id }}</h1>
            <div class="btn-group">
                <a href="{{ route('maintenances.index') }}" class="btn btn-outline-secondary" aria-label="Back to maintenances list">Back to Maintenances</a>
                <a href="{{ route('maintenances.edit', $maintenance->id) }}" class="btn btn-outline-primary" aria-label="Edit maintenance {{ $maintenance->id }}">Edit Maintenance</a>
                <form action="{{ route('maintenances.destroy', $maintenance->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this maintenance?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" aria-label="Delete maintenance {{ $maintenance->id }}">Delete Maintenance</button>
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

        <!-- Maintenance Details Card -->
        <div class="card shadow-sm">
            <div class="card-header">Maintenance Information</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5><strong>ID:</strong> {{ $maintenance->id }}</h5>
                        <h5><strong>Machine:</strong> {{ $maintenance->machine->name ?? 'N/A' }}</h5>
                        <h5><strong>Description:</strong> {{ $maintenance->description ?? 'N/A' }}</h5>
                    </div>
                    <div class="col-md-6">
                        <h5><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($maintenance->start_date)->format('d/m/Y') }}</h5>
                        <h5><strong>End Date:</strong> {{ $maintenance->end_date ? \Carbon\Carbon::parse($maintenance->end_date)->format('d/m/Y') : 'N/A' }}</h5>
                        <h5><strong>Kilometers:</strong> {{ number_format($maintenance->kilometers, 2) }}</h5>
                        <h5><strong>Status:</strong> {{ ucfirst($maintenance->status ?? 'N/A') }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>