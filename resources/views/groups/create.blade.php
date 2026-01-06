@extends('layouts.app')

@section('content')
    <div class="card col-md-6 mx-auto">
        <div class="card-body">
            <h5 class="mb-3">Create Group</h5>
            @if($errors->has('member_name'))
    <div class="text-danger small">
        {{ $errors->first('member_name') }}
    </div>
@endif


            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('groups.store') }}">
                @csrf

                <!-- GROUP NAME -->
                <div class="mb-3">
                    <label class="form-label">Group Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <!-- MEMBERS -->
                <label class="form-label">Members</label>

                <div id="members-wrapper">
                    <div class="input-group mb-2">
                        <input type="text" name="members[]" class="form-control" placeholder="Member name" required>
                        <button type="button" class="btn btn-danger remove-member" disabled>×</button>
                    </div>
                </div>

                <button type="button" class="btn btn-sm btn-outline-primary mb-3" id="add-member">
                    + Add Member
                </button>

                <div class="d-flex gap-2">
                    <button class="btn btn-success">Create</button>
                    <a href="{{ route('groups.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('add-member').addEventListener('click', function () {
            const wrapper = document.getElementById('members-wrapper');

            const div = document.createElement('div');
            div.classList.add('input-group', 'mb-2');

            div.innerHTML = `
            <input type="text" name="members[]" class="form-control" placeholder="Member name" required>
            <button type="button" class="btn btn-danger remove-member">×</button>
        `;

            wrapper.appendChild(div);
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-member')) {
                e.target.parentElement.remove();
            }
        });
    </script>
@endsection