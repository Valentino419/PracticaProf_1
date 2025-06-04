<!DOCTYPE html>
<html>
<head>
    <title>New Job sites </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>edite Job Site</h1>
        <a href="{{ route('jobsites.index') }}" class="btn btn-secondary mb-3">Back</a>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('jobsites.update', $jobsite->id)}}" method="POST">
            @csrf
             @method('PUT')
            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" name="location" id="location" class="form-control" value="{{ $jobsite->location }}">
            </div>
            <div class="mb-3">
                <label for="start_date" class="form-label">Start date</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{$jobsite->start_date}}">
            </div>
            <div class="mb-3">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{$jobsite->end_date}}">
            </div>
            
              <div class="mb-3">
                <label for="province_id" class="form-label">Province</label>
                <select name="province_id" id="province_id" class="form-control @error('province_id') is-invalid @enderror">
                    <option value="">Select a province</option>
                    @foreach ($provinces as $province => $name)
                        <option value="{{ $province }}" {{$jobsite->province_id== $province ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
                @error('province_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</body>
</html>