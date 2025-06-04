<!DOCTYPE html>
<html>
<head>
    <title>New Job sites </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>complet Machine Job</h1>
        <a href="{{ route('machines.show', $jobsitemachine->machine_id) }}" class="btn btn-info btn-sm">Back to Machine</a>
        <a href="{{ route('jobsites.show', $jobsitemachine->jobsite_id) }}" class="btn btn-info btn-sm">Back to Jobsite</a>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('jobsitemachines.update', $jobsitemachine->id)}}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="kilometers" class="form-label">Kilometers </label>
                <input type="text" name="kilometers" id="kilometers" class="form-control" value="{{ old('kilometers') }}">
            </div>
            <div class="mb-3">
                <label for="conclusion_reasons" class="form-label">Conclusion Reason</label>
                <input type="text" name="conclusion_reasons" id="conclusion_reasons" class="form-control" value="{{old('conclusion_reasons')}}">
            </div>
            <input type='hidden' name='status' id='status' value='completed'>
             <input type='hidden' name='machine_id' id='machine_id' value='{{$jobsitemachine->machine_id }}'>
              <input type='hidden' name='jobsite_id' id='jobsite_id' value='{{$jobsitemachine->jobsite_id }}'>
               <input type='hidden' name='start_date' id='start_date' value='{{$jobsitemachine->start_date }}'>
               <input type='hidden' name='end_date' id='end_date' value='{{$jobsitemachine->end_date }}'>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</body>
</html>