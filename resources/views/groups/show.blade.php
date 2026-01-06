@extends('layouts.app')

@section('content')

@if($errors->has('member_name'))
    <div class="text-danger small">
        {{ $errors->first('member_name') }}
    </div>
@endif


<!-- ================= QUICK TOTAL SPLIT ================= -->
<div class="card mb-3">
    <div class="card-body">
        <h6 class="mb-3">Quick Total Split</h6>

        <form method="POST" action="{{ route('groups.quickSplit', $group->id) }}">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <label>Total Amount</label>
                    <input type="number" step="0.01" name="amount" class="form-control" required>
                </div>
                <div class="col-md-5">
                    <label>Description</label>
                    <input type="text" name="description" class="form-control" placeholder="Dinner, Trip">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-success w-100">Split Equally</button>
                </div>
            </div>
            <small class="text-muted">
                Split among {{ $group->activeMembers->count() }} members
            </small>
        </form>
    </div>
</div>

<div class="row">

<!-- ================= MEMBERS ================= -->
<div class="col-md-4">
    <div class="card mb-3">
        <div class="card-body">
            <h6>Members</h6>

            <!-- ADD MEMBER -->
            <form method="POST" action="{{ route('groups.members.add', $group->id) }}" class="mb-3">
                @csrf
                <div class="input-group">
                    <input type="text" name="member_name" class="form-control" placeholder="Member name" required>
                    <button class="btn btn-primary btn-sm">Add</button>
                </div>
            </form>

            <!-- MEMBER LIST (EDIT + DELETE) -->
            <ul class="list-group list-group-flush">
            @forelse($group->activeMembers as $member)
                <li class="list-group-item d-flex justify-content-between align-items-center">

                    <!-- EDIT MEMBER -->
                    <form method="POST"
                          action="{{ route('groups.members.update', $member->id) }}"
                          class="d-flex gap-2 w-100">
                        @csrf
                        <input type="text"
                               name="member_name"
                               value="{{ $member->member_name }}"
                               class="form-control form-control-sm"
                               required>
                        <button class="btn btn-sm btn-success">✔</button>
                    </form>

                    <!-- DELETE MEMBER -->
                    <form method="POST"
                          action="{{ route('groups.members.delete', $member->id) }}"
                          onsubmit="return confirm('Remove member?')">
                        @csrf
                        <button class="btn btn-sm btn-danger ms-2">×</button>
                    </form>
                </li>
            @empty
                <li class="list-group-item text-muted">No members</li>
            @endforelse
            </ul>
        </div>
    </div>
</div>

<!-- ================= EXPENSES ================= -->
<div class="col-md-8">
    {{-- <div class="d-flex justify-content-between mb-2">
        <h6>Expenses</h6>
        <a href="{{ route('expenses.create', $group->id) }}" class="btn btn-sm btn-primary">
            + Add Expense
        </a>
    </div> --}}

    <table class="table table-bordered bg-white">
        <thead>
            <tr>
                <th>Description</th>
                <th>Paid By</th>
                <th>Amount</th>
                <th width="120">Action</th>
            </tr>
        </thead>
        <tbody>
        @forelse($group->expenses as $expense)
            <tr>
                <td>{{ $expense->description }}</td>
                <td>{{ $expense->paid_by_name }}</td>
                <td>₹ {{ number_format($expense->amount, 2) }}</td>
                <td>
                    <!-- EDIT -->
                   @if($expense->paid_by_name === 'Group')
    <a href="{{ route('expenses.quickSplit.edit', $expense->id) }}"
       class="btn btn-sm btn-warning">
        Edit
    </a>
@endif


                    <!-- DELETE -->
                    <form method="POST"
                          action="{{ route('expenses.delete', $expense->id) }}"
                          class="d-inline"
                          onsubmit="return confirm('Delete expense?')">
                        @csrf
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>

            <!-- EDIT EXPENSE MODAL -->
            <div class="modal fade" id="editExpense{{ $expense->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('expenses.update', $expense->id) }}">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5>Edit Expense</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <input type="text"
                                       name="description"
                                       value="{{ $expense->description }}"
                                       class="form-control mb-2">

                                <input type="number"
                                       step="0.01"
                                       name="amount"
                                       value="{{ $expense->amount }}"
                                       class="form-control mb-2"
                                       required>

                                <select name="paid_by_name" class="form-select">
                                    @foreach($group->activeMembers as $m)
                                        <option value="{{ $m->member_name }}"
                                            {{ $expense->paid_by_name == $m->member_name ? 'selected' : '' }}>
                                            {{ $m->member_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-success">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <tr>
                <td colspan="4" class="text-center text-muted">
                    No expenses added yet
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

</div>

<!-- ================= SUMMARY ================= -->
<div class="card mt-3">
    <div class="card-body">
        <h6>Balance Summary</h6>
        <ul class="list-group">
        @forelse($balances as $name => $amount)
            <li class="list-group-item d-flex justify-content-between">
                {{ $name }}
                <strong class="{{ $amount >= 0 ? 'text-success' : 'text-danger' }}">
                    ₹ {{ number_format(abs($amount), 2) }}
                </strong>
            </li>
        @empty
            <li class="list-group-item text-muted">No balance data</li>
        @endforelse
        </ul>
    </div>
</div>

@endsection
