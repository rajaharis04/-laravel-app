@extends('layouts.app')

@section('title', 'Edit Student')

@section('content')

<div class="card shadow-sm mx-auto" style="max-width: 700px;">

    <div class="card-header bg-white">
        <h4 class="mb-0">Edit Student</h4>
    </div>

    <div class="card-body">

        <form
            action="{{ route('students.update', $student) }}"
            method="POST"
        >
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Student Name</label>

                <input
                    type="text"
                    name="name"
                    class="form-control"
                    value="{{ old('name', $student->name) }}"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Email Address</label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="{{ old('email', $student->email) }}"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Course</label>

                <input
                    type="text"
                    name="course"
                    class="form-control"
                    value="{{ old('course', $student->course) }}"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Phone Number</label>

                <input
                    type="text"
                    name="phone"
                    class="form-control"
                    value="{{ old('phone', $student->phone) }}"
                >
            </div>

            <button type="submit" class="btn btn-success">
                Update Student
            </button>

            <a href="{{ route('students.index') }}" class="btn btn-secondary">
                Cancel
            </a>

        </form>

    </div>
</div>

@endsection
