@extends('layouts.app')

@section('title', 'All Students')

@section('content')

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">All Students</h4>

        <a href="{{ route('students.create') }}" class="btn btn-primary">
            + Add Student
        </a>
    </div>

    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">

                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Course</th>
                        <th>Phone</th>
                        <th width="180">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($students as $student)
                        <tr>
                            <td>{{ $student->id }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->course }}</td>
                            <td>{{ $student->phone ?? '-' }}</td>

                            <td>
                                <a
                                    href="{{ route('students.edit', $student) }}"
                                    class="btn btn-sm btn-warning"
                                >
                                    Edit
                                </a>

                                <form
                                    action="{{ route('students.destroy', $student) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this student?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-sm btn-danger">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                No students found. Please add a student.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{ $students->links('pagination::bootstrap-5') }}

    </div>
</div>

@endsection
