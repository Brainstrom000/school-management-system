@extends('layouts.star')

@section('title', 'My Profile')

@section('content')

<div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
    <h1 class="mb-0">Edit Profile</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 px-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('profile.edit') }}">Profile</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </nav>
</div>

@if (session('status') === 'profile-updated')
    <div class="alert alert-success">Your profile has been updated successfully.</div>
@endif

<div class="card mb-4">
    <div class="card-body">

        <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profileForm">
            @csrf
            @method('patch')

            <div class="row g-4">

                {{-- Profile Picture --}}
                <div class="col-lg-4">
                    <div class="border rounded-4 p-4 h-100 text-center">
                        <h5 class="mb-4 text-start">Profile Picture</h5>

                        <div class="position-relative d-inline-block mb-3">
                            <img id="profilePreview"
                                 src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=4f46e5&color=fff&size=160' }}"
                                 alt="{{ $user->name }}"
                                 class="rounded-circle border"
                                 style="width:150px;height:150px;object-fit:cover;">

                            <label for="profile_image"
                                   class="position-absolute d-flex align-items-center justify-content-center rounded-circle bg-primary text-white"
                                   style="width:36px;height:36px;bottom:6px;right:6px;cursor:pointer;box-shadow:0 2px 6px rgba(0,0,0,.25);">
                                <i class="mdi mdi-camera"></i>
                            </label>
                        </div>

                        <p class="text-muted small mb-3">JPG, PNG or GIF. Max size 2MB.</p>

                        <input type="file" id="profile_image" name="profile_image" accept="image/png, image/jpeg, image/gif" class="d-none">
                        <label for="profile_image" class="btn btn-outline-secondary btn-sm">Choose File</label>

                        @error('profile_image')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Personal Information --}}
                <div class="col-lg-8">
                    <div class="border rounded-4 p-4 h-100">
                        <h5 class="mb-4">Personal Information</h5>

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name</label>
                                <input id="name" name="name" type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $user->name) }}"
                                       required autocomplete="name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input id="email" name="email" type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $user->email) }}"
                                       required autocomplete="username">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                    <div class="mt-2">
                                        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                                            @csrf
                                        </form>
                                        <p class="small text-muted mb-1">
                                            Your email address is unverified.
                                            <button form="send-verification" class="btn btn-link btn-sm p-0 align-baseline">
                                                Click here to re-send the verification email.
                                            </button>
                                        </p>
                                        @if (session('status') === 'verification-link-sent')
                                            <p class="small text-success mb-0">A new verification link has been sent to your email address.</p>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone</label>
                                <input id="phone" name="phone" type="text"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone', $user->phone) }}"
                                       placeholder="e.g. 0300-1234567">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="address" class="form-label">Address</label>
                                <input id="address" name="address" type="text"
                                       class="form-control @error('address') is-invalid @enderror"
                                       value="{{ old('address', $user->address) }}"
                                       placeholder="City, Country">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input id="date_of_birth" name="date_of_birth" type="date"
                                       class="form-control @error('date_of_birth') is-invalid @enderror"
                                       value="{{ old('date_of_birth', optional($user->date_of_birth)->format('Y-m-d')) }}">
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="gender" class="form-label">Gender</label>
                                <select id="gender" name="gender" class="form-select @error('gender') is-invalid @enderror">
                                    <option value="">Select Gender</option>
                                    @foreach(['Male', 'Female', 'Other'] as $option)
                                        <option value="{{ $option }}" @selected(old('gender', $user->gender) === $option)>{{ $option }}</option>
                                    @endforeach
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>

        </form>

    </div>
</div>

{{-- Update Password --}}
<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title mb-0">Update Password</h3>
    </div>
    <div class="card-body">

        <p class="text-muted mb-4">Ensure your account is using a long, random password to stay secure.</p>

        @if (session('status') === 'password-updated')
            <div class="alert alert-success">Your password has been updated successfully.</div>
        @endif

        <form method="post" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            <div class="row g-3">
                <div class="col-md-4">
                    <label for="update_password_current_password" class="form-label">Current Password</label>
                    <input id="update_password_current_password" name="current_password" type="password"
                           class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                           autocomplete="current-password">
                    @error('current_password', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="update_password_password" class="form-label">New Password</label>
                    <input id="update_password_password" name="password" type="password"
                           class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                           autocomplete="new-password">
                    @error('password', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="update_password_password_confirmation" class="form-label">Confirm Password</label>
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                           class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                           autocomplete="new-password">
                    @error('password_confirmation', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-primary">Update Password</button>
            </div>
        </form>

    </div>
</div>

{{-- Delete Account --}}
<div class="card mb-4 border-danger">
    <div class="card-header bg-transparent">
        <h3 class="card-title mb-0 text-danger">Delete Account</h3>
    </div>
    <div class="card-body">

        <p class="text-muted mb-4">
            Once your account is deleted, all of its resources and data will be permanently deleted.
            Before deleting your account, please download any data or information that you wish to retain.
        </p>

        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
            Delete Account
        </button>

    </div>
</div>

{{-- Delete Account Confirmation Modal --}}
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <div class="modal-header">
                    <h5 class="modal-title">Are you sure you want to delete your account?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p class="text-muted">
                        Once your account is deleted, all of its resources and data will be permanently deleted.
                        Please enter your password to confirm you would like to permanently delete your account.
                    </p>

                    <label for="delete_password" class="form-label">Password</label>
                    <input id="delete_password" name="password" type="password"
                           class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                           placeholder="Password">
                    @error('password', 'userDeletion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if ($errors->userDeletion->isNotEmpty())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new bootstrap.Modal(document.getElementById('deleteAccountModal')).show();
        });
    </script>
@endif

@endsection

@push('scripts')
<script>
    // Live preview of the selected profile picture before uploading.
    document.getElementById('profile_image').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (ev) {
            document.getElementById('profilePreview').src = ev.target.result;
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush