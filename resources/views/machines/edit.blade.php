<!-- resources/views/posts/edit.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Create Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Machine</h1>
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
        
        <form action="{{ route('machines.update', $machine->id) }}" method="post">
        @csrf
        @method('PUT')
        <div class="form-group">
          <label for="name">name</label>
          <input type="text" class="form-control" id="name" name="name"
            value="{{ $machine->name }}">   
         
              <div class="mb-3">
                <label for="machine_type_id" class="form-label">Type</label>
                <select name="machine_type_id" id="machine_type_id" class="form-control @error('machine_type_id') is-invalid @enderror">
                    <option value="">Select a Type</option>
                    @foreach ($machineTypes as $id => $name)
                        <option value="{{ $id }}" {{$machine->machine_type_id == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
                @error('machine_type_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        <button type="submit" class="btn mt-3 btn-primary">Update machine</button>
     
    </div>
</body>
</html>
