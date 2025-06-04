<!DOCTYPE html>
<html>
<head>
    <title>Create Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>New Machine Type</h1>
        <a href="{{ route('machinetypes.index') }}" class="btn btn-secondary mb-3">Back</a>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('machinetypes.update',$machinetype->id) }}" method="POST">
            @csrf
             @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control"  value="{{ $machinetype->name }}">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">description</label>
                <input type="text" name="description" id="description" class="form-control"  value="{{ $machinetype->description }}">
            </div>
            
             
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</body>
</html>