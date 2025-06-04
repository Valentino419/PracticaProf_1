<!-- resources/views/posts/create.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Create Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Create Machine</h1>
        <a href="{{ route('machines.index') }}" class="btn btn-secondary mb-3">Back</a>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('machines.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Idientifier</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
            </div>
            
              <div class="mb-3">
                <label for="machine_type_id" class="form-label">Category</label>
                <select name="machine_type_id" id="machine_type_id" class="form-control @error('machine_type_id') is-invalid @enderror">
                    <option value="">Select a category</option>
                    @foreach ($machineTypes as $id => $name)
                        <option value="{{ $id }}" {{ old('machine_type_id') == $id ? 'selected' : '' }}>
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