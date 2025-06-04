@forelse ($maintenances as $maintenance)
    <tr>
        <td>{{ $maintenance->id }}</td>
        <td>{{ $maintenance->description }}</td>
        <td>{{ $maintenance->status }}</td>
        <td>{{ $maintenance->start_date }}</td>
        <td>{{ $maintenance->end_date }}</td>
        <td>{{ $maintenance->kilometers }}</td>
        <td>
            <a href="{{ route('maintenances.show', $maintenance->id) }}" class="btn btn-info btn-sm">View</a>
            <a href="{{ route('maintenances.edit', $maintenance->id) }}" class="btn btn-warning btn-sm">Edit</a>
            <form action="{{ route('maintenances.destroy', $maintenance->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
            </form>
        </td>
    </tr>
@empty
    <tr><td colspan="6" class="text-center">No maintenances found.</td></tr>
@endforelse
<div class="mt-3">
    {{ $maintenances->links() }}
</div>
