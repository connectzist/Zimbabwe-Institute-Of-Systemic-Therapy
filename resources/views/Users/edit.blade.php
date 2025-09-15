@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>Edit User</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="role">Role</label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="" disabled selected>Select Role</option>
                        <option value="SuperAdmin" {{ old('role', $user->role) == 'SuperAdmin' ? 'selected' : '' }}>Super Admin</option>
                        <option value="CertificateAdmin" {{ old('role', $user->role) == 'CertificateAdmin' ? 'selected' : '' }}>Certificate Admin</option>
                        <option value="DiplomaAdmin" {{ old('role', $user->role) == 'DiplomaAdmin' ? 'selected' : '' }}>Diploma Admin</option>
                        <option value="AdvancedDiplomaAdmin" {{ old('role', $user->role) == 'AdvancedDiplomaAdmin' ? 'selected' : '' }}>Advanced Diploma Admin</option>
                    </select>
                    @error('role')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password">Password (leave blank to keep unchanged)</label>
                    <input type="password" name="password" id="password" class="form-control">
                    @error('password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary mt-2">Update User</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary mt-2">Cancel</a>
            </form>
        </div>
    </div>
</div>
