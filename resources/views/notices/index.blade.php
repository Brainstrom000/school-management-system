@extends('layouts.star')

@section('title', 'Notices')

@section('content')

<div class="page-header-row">
    <h1>Notices</h1>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(auth()->user()->role === 'admin')
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title mb-0">Post a New Notice</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('notices.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required maxlength="255">
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Message</label>
                    <textarea name="message" rows="3" class="form-control @error('message') is-invalid @enderror" required maxlength="2000">{{ old('message') }}</textarea>
                    @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Audience</label>
                    <select name="audience" class="form-select @error('audience') is-invalid @enderror">
                        <option value="all" {{ old('audience') === 'all' ? 'selected' : '' }}>Everyone</option>
                        <option value="students" {{ old('audience') === 'students' ? 'selected' : '' }}>Students only</option>
                        <option value="teachers" {{ old('audience') === 'teachers' ? 'selected' : '' }}>Teachers only</option>
                    </select>
                    @error('audience') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="mdi mdi-bullhorn-outline"></i> Post Notice
                </button>
            </form>
        </div>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0">All Notices</h3>
    </div>
    <div class="card-body">
        @forelse($notices as $notice)
            <div class="notice-item @if(!$loop->last) border-bottom @endif">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="badge notice-audience-badge">{{ ucfirst($notice->audience) }}</span>
                        <h5 class="mt-2 mb-1">{{ $notice->title }}</h5>
                        <p class="mb-1 text-muted">{{ $notice->message }}</p>
                        <p class="small text-muted mb-0">
                            By {{ $notice->postedBy->name ?? 'Admin' }} &middot; {{ $notice->created_at->diffForHumans() }}
                        </p>
                    </div>
                    @if(auth()->user()->role === 'admin')
                        <form method="POST" action="{{ route('notices.destroy', $notice) }}" onsubmit="return confirm('Delete this notice?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="mdi mdi-trash-can-outline"></i></button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-muted mb-0">No notices yet.</p>
        @endforelse
    </div>
</div>

<div class="mt-3">
    {{ $notices->links() }}
</div>

@endsection
