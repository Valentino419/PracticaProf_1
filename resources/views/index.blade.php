
    
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index </title>
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
            <h1 class="h2">Inicio</h1>
            <div class="btn-group">
                <a href="{{ route('jobsites.index') }}" class="btn btn-outline-secondary">Active Job sites</a>
     <a href="{{ route('machines.index') }}" class="btn btn-outline-secondary"
                    aria-label="Back to machines list">Machines</a>          
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

      
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
