@extends('layouts.app')

@section('content')
<div class="card col-md-6 mx-auto">
    <div class="card-body">
        <h5 class="mb-3">Edit Total Split</h5>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST"
              action="{{ route('groups.quickSplit.update', $expense->id) }}">
            @csrf

            <!-- Description -->
            <div class="mb-3">
                <label>Description</label>
                <input type="text"
                       name="description"
                       class="form-control"
                       value="{{ old('description', $expense->description) }}">
            </div>

            <!-- Total Amount -->
            <div class="mb-3">
                <label>Total Amount</label>
                <input type="number"
                       step="0.01"
                       name="amount"
                       class="form-control"
                       value="{{ old('amount', $expense->amount) }}"
                       required>
            </div>

            <div class="alert alert-info small">
                This amount will be re-split equally among
                <strong>{{ $membersCount }}</strong> members.
            </div>

            <div class="d-flex gap-2">
                <button class="btn btn-success w-100">
                    Update & Re-Split
                </button>
                <a href="{{ route('groups.show', $expense->group_id) }}"
                   class="btn btn-secondary w-100">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
