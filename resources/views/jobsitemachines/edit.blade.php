<!DOCTYPE html>
<html>

<head>
    <title>Edit Assigment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1>Edit Assigment</h1>
        
       
            <a href="{{ route('machines.show', $jobsitemachine->machine_id) }}" class="btn btn-secondary mb-3">Back to
                Machine</a>
    
            <a href="{{ route('jobsites.show', $jobsitemachine->jobsite_id) }}" class="btn btn-secondary mb-3">Back to
                Job site</a>
       
        

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div> <!-- Fixed: Use alert-danger for errors -->
        @endif

        <form action="{{ route('jobsitemachines.update',$jobsitemachine->id) }}" method="POST">
            @csrf
            @method('PUT')
            <table class="table">
                <tr>
                    <td>
                        <div class="mb-3">
                            <label for="machine_id" class="form-label">Machine</label>
                            <select name="machine_id" id="machine_id"
                                class="form-control @error('machine_id') is-invalid @enderror">
                                <option value="">Select a Machine</option>
                                @foreach ($machines as $id => $name)
                                    <option value="{{ $id }}"
                                        {{ old('machine_id', $jobsitemachine->machine_id ?? '') == $id ? 'selected' : '' }}>
                                        >
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('machine_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </td>
                    <td>
                        <div class="mb-3">
                            <label for="jobsite_id" class="form-label">Jobsite</label>
                            <select name="jobsite_id" id="jobsite_id"
                                class="form-control @error('jobsite_id') is-invalid @enderror">
                                <option value="">Select a Jobsite</option>
                                @foreach ($jobsites as $id => $name)
                                    <option value="{{ $id }}"
                                        {{ old('jobsite_id', $jobsitemachine->jobsite_id ?? '') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jobsite_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </td>
                    <td>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status"
                                class="form-control @error('status') is-invalid @enderror">
                                <option value="">Select a Status</option>
                                <option value="active" {{ $jobsitemachine->status == 'active' ? 'selected' : '' }}>
                                    Active</option>
                                <option value="completed"
                                    {{ $jobsitemachine->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="pending" {{ $jobsitemachine->status == 'pending' ? 'selected' : '' }}>
                                    Pending</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Starting Date</label>
                            <input type="date" name="start_date" id="start_date"
                                class="form-control @error('start_date') is-invalid @enderror"
                                value="{{ $jobsitemachine->start_date }}">
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </td>
                    <td>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Finishing Date</label>
                            <input type="date" name="end_date" id="end_date"
                                class="form-control @error('end_date') is-invalid @enderror"
                                value="{{ $jobsitemachine->end_date }}">
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </td>
                    <td>
                        <div class="mb-3 km-reasons @if ($jobsitemachine->status != 'completed') d-none @endif">
                            <label for="kilometers" class="form-label">Kilometers</label>
                            <input type="number" name="kilometers" id="kilometers"
                                class="form-control @error('kilometers') is-invalid @enderror"
                                value={{ $jobsitemachine->kilometers ?? 0 }}>
                            @error('kilometers')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="mb-3 km-reasons @if ($jobsitemachine->status != 'completed') d-none @endif">
                            <label for="conclusion_reasons" class="form-label">Reasons for Conclusion</label>
                            <input type="text" name="conclusion_reasons" id="conclusion_reasons"
                                class="form-control @error('conclusion_reasons') is-invalid @enderror"
                                value={{ $jobsitemachine->conclusion_reasons ?? 'n/a' }}>
                            @error('conclusion_reasons')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </td>
                </tr>
            </table>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('status');
            const kmReasonsFields = document.querySelectorAll('.km-reasons');

            function toggleKmReasonsFields() {
                const isCompleted = statusSelect.value === 'completed';
                kmReasonsFields.forEach(field => {
                    field.classList.toggle('d-none', !isCompleted);
                });
            }

            // Run on page load to handle pre-selected status (e.g., after validation error)
            toggleKmReasonsFields();

            // Run on status change
            statusSelect.addEventListener('change', toggleKmReasonsFields);
        });
    </script>
</body>

</html>
