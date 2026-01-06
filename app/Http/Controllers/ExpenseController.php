<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseSplit;
use App\Models\GroupMember;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    /* =======================
       CREATE EXPENSE FORM
    ======================= */
    public function create($groupId)
    {
        $group = Group::where('is_active', 'Y')->findOrFail($groupId);

        $members = GroupMember::where('group_id', $groupId)
            ->where('is_active', 'Y')
            ->get();

        return view('expenses.create', compact('group', 'members'));
    }

    /* =======================
       STORE NORMAL EXPENSE
    ======================= */
    public function store(Request $request)
    {
        $request->validate([
            'group_id'     => 'required|integer',
            'amount'       => 'required|numeric|min:0.01',
            'paid_by_name' => 'required|string|max:255'
        ]);

        DB::transaction(function () use ($request) {

            $members = GroupMember::where('group_id', $request->group_id)
                ->where('is_active', 'Y')
                ->pluck('member_name');

            if ($members->count() === 0) {
                throw new \Exception('No active members found');
            }

            $expense = Expense::create([
                'group_id'     => $request->group_id,
                'paid_by_name' => $request->paid_by_name,
                'amount'       => $request->amount,
                'description'  => $request->description,
                'is_active'    => 'Y'
            ]);

            $splitAmount = round($request->amount / $members->count(), 2);

            foreach ($members as $name) {
                ExpenseSplit::create([
                    'expense_id'   => $expense->id,
                    'member_name'  => $name,
                    'share_amount' => $splitAmount
                ]);
            }
        });

        return redirect()->route('groups.show', $request->group_id);
    }

    /* =======================
       UPDATE NORMAL EXPENSE
    ======================= */
    public function update(Request $request, $id)
    {
        $request->validate([
            'description'  => 'nullable|string|max:255',
            'amount'       => 'required|numeric|min:0.01',
            'paid_by_name' => 'required|string|max:255'
        ]);

        DB::transaction(function () use ($request, $id) {

            $expense = Expense::where('is_active', 'Y')->findOrFail($id);

            $members = GroupMember::where('group_id', $expense->group_id)
                ->where('is_active', 'Y')
                ->pluck('member_name');

            if ($members->count() === 0) {
                throw new \Exception('No active members found');
            }

            $expense->update([
                'description'  => $request->description,
                'amount'       => $request->amount,
                'paid_by_name' => $request->paid_by_name
            ]);

            ExpenseSplit::where('expense_id', $expense->id)->delete();

            $splitAmount = round($request->amount / $members->count(), 2);

            foreach ($members as $member) {
                ExpenseSplit::create([
                    'expense_id'   => $expense->id,
                    'member_name'  => $member,
                    'share_amount' => $splitAmount
                ]);
            }
        });

        return back()->with('success', 'Expense updated');
    }

    /* =======================
       EDIT QUICK SPLIT FORM
    ======================= */
    public function editQuickSplit($expenseId)
    {
        $expense = Expense::where('is_active', 'Y')->findOrFail($expenseId);

        $membersCount = GroupMember::where('group_id', $expense->group_id)
            ->where('is_active', 'Y')
            ->count();

        return view('expenses.edit_quick_split', compact('expense', 'membersCount'));
    }

    /* =======================
       UPDATE QUICK SPLIT
    ======================= */
    public function updateQuickSplit(Request $request, $expenseId)
    {
        $request->validate([
            'amount'      => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $expenseId) {

            $expense = Expense::where('is_active', 'Y')->findOrFail($expenseId);

            $members = GroupMember::where('group_id', $expense->group_id)
                ->where('is_active', 'Y')
                ->pluck('member_name');

            if ($members->count() === 0) {
                throw new \Exception('No active members found');
            }

            $expense->update([
                'amount'      => $request->amount,
                'description' => $request->description,
            ]);

            ExpenseSplit::where('expense_id', $expense->id)->delete();

            $splitAmount = round($request->amount / $members->count(), 2);

            foreach ($members as $member) {
                ExpenseSplit::create([
                    'expense_id'   => $expense->id,
                    'member_name'  => $member,
                    'share_amount' => $splitAmount,
                ]);
            }
        });

        return redirect()
            ->route('groups.show', Expense::find($expenseId)->group_id)
            ->with('success', 'Total split updated successfully');
    }

    /* =======================
       QUICK TOTAL SPLIT
    ======================= */
    public function quickSplit(Request $request, $groupId)
    {
        $request->validate([
            'amount'      => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255'
        ]);

        DB::transaction(function () use ($request, $groupId) {

            $members = GroupMember::where('group_id', $groupId)
                ->where('is_active', 'Y')
                ->pluck('member_name');

            if ($members->count() === 0) {
                throw new \Exception('No members found');
            }

            $expense = Expense::create([
                'group_id'     => $groupId,
                'paid_by_name' => 'Group',
                'amount'       => $request->amount,
                'description'  => $request->description ?? 'Quick Split',
                'is_active'    => 'Y'
            ]);

            $splitAmount = round($request->amount / $members->count(), 2);

            foreach ($members as $member) {
                ExpenseSplit::create([
                    'expense_id'   => $expense->id,
                    'member_name'  => $member,
                    'share_amount' => $splitAmount
                ]);
            }
        });

        return redirect()
            ->route('groups.show', $groupId)
            ->with('success', 'Amount split equally');
    }

    /* =======================
       SOFT DELETE EXPENSE
    ======================= */
    public function delete($id)
    {
        Expense::where('id', $id)->update(['is_active' => 'N']);

        return back()->with('success', 'Expense deleted');
    }
}
