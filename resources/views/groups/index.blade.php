@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Your Groups</h4>
    <a href="{{ route('groups.create') }}" class="btn btn-primary">
        + Create Group
    </a>
</div>

<div class="row">
@forelse($groups as $group)
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex flex-column">

                <!-- EDIT GROUP NAME -->
                <form method="POST"
                      action="{{ route('groups.update', $group->id) }}"
                      class="mb-2">
                    @csrf
                    <input type="text"
                           name="name"
                           value="{{ $group->name }}"
                           class="form-control form-control-sm"
                           required>
                    <button class="btn btn-sm btn-success mt-2 w-100">
                        EDIT Name
                    </button>
                </form>

                <p class="text-muted mb-3">
                    {{ $group->activeMembers->count() }} members
                </p>

                <div class="mt-auto d-flex gap-2">
                    <a href="{{ route('groups.show', $group->id) }}"
                       class="btn btn-sm btn-outline-primary w-100">
                        View
                    </a>

                    <!-- DELETE GROUP -->
                    <form method="POST"
                          action="{{ route('groups.delete', $group->id) }}"
                          onsubmit="return confirm('Are you sure you want to delete this group?')">
                        @csrf
                        <button class="btn btn-sm btn-outline-danger">
                            Delete
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
@empty
    <div class="col-12">
        <div class="alert alert-info">
            No groups created yet. Click <strong>Create Group</strong> to start.
        </div>
    </div>
@endforelse
</div>
@endsection
