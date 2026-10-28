@extends('layouts.app')

@section('title', 'Add Student')

@section('content')

<div class="card shadow-sm mx-auto" style="max-width: 700px;">

    <div class="card-header bg-white">
        <h4 class="mb-0">Add New Student</h4>
    </div>

    <div class="card-body">

        <form action="{{ route('students.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Student Name</label>

                <input
                    type="text"
                    name="name"
                    class="form-control"
                    value="{{ old('name') }}"
                    placeholder="Enter student name"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Email Address</label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="{{ old('email') }}"
                    placeholder="Enter email"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Course</label>

                <input
                    type="text"
                    name="course"
                    class="form-control"
                    value="{{ old('course') }}"
                    placeholder="Enter course"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Phone Number</label>

                <input
                    type="text"
                    name="phone"
                    class="form-control"
                    value="{{ old('phone') }}"
                    placeholder="Enter phone number"
                >
            </div>

            <button type="submit" class="btn btn-primary">
                Save Student
            </button>

            <a href="{{ route('students.index') }}" class="btn btn-secondary">
                Cancel
            </a>

        </form>

    </div>
</div>

@endsection
