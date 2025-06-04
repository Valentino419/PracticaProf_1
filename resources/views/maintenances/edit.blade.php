<!DOCTYPE html>
<html>
<head>
    <title>Create maintenance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Create Machine</h1>
        <a href="{{ route('maintenances.index') }}" class="btn btn-secondary mb-3">Back</a>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('maintenances.update', $maintenance->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <input type="text" name="description" id="description" class="form-control" value="{{ $maintenance->description }}">
            </div>
            <div class="mb-3">
                <label for="start_date" class="form-label">Description</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $maintenance->start_date }}">
            </div>
            <div class="mb-3">
                <label for="end_date" class="form-label">finishing date</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $maintenance->end_date }}">
            </div>
            <div class="mb-3">
                <label for="kilometers" class="form-label">Kilometraje</label>
                <input type="integer" name="kilometers" id="kilometers" class="form-control" value="{{ $maintenance->kilometers }}">
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Satus</label>
                 <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                    <option value="">Select a status</option>
                    <option value="Completed"{{$maintenance->status== "Completed" ? 'selected' : '' }}>Completed</option>
                    <option value="Pending"{{$maintenance->status== "Pending" ? 'selected' : '' }}>Pending</option>
                </select>  
             </div>
              <div class="mb-3">
                <label for="machine_id" class="form-label">machine</label>
                <select name="machine_id" id="machine_type_id" class="form-control @error('machine_id') is-invalid @enderror">
                    <option value="">Select a machine</option>
                    @foreach ($machines as $id => $name)
                        <option value="{{ $id }}"{{$maintenance->machine_id== $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
                @error('machine_type_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</body>
</html>