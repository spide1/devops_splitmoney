<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupMember;
use App\Models\ExpenseSplit;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    // List user groups
   public function index()
{
    $groups = Group::with('activeMembers')
        ->where('is_active', 'Y')
        ->orderBy('created_at', 'desc')
        ->get();

    return view('groups.index', compact('groups'));
}



    // Show create group form
    public function create()
    {
        return view('groups.create');
    }

    // Store new group
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'members' => 'required|array|min:1',
            'members.*' => 'required|string|max:255',
        ]);

        $group = Group::create([
            'name' => $request->name,
        ]);

        foreach ($request->members as $memberName) {
            $group->members()->create([
                'member_name' => $memberName,
            ]);
        }

        return redirect()->route('groups.index')
            ->with('success', 'Group created successfully');
    }

    public function updateMember(Request $request, $id)
{
    $request->validate([
        'member_name' => 'required|string|max:255'
    ]);

    $member = GroupMember::findOrFail($id);

    $exists = GroupMember::where('group_id', $member->group_id)
        ->where('member_name', $request->member_name)
        ->where('is_active', 'Y')
        ->where('id', '!=', $id)
        ->exists();

    if ($exists) {
        return back()
            ->withErrors(['member_name' => 'Member name already exists'])
            ->withInput();
    }

    $oldName = $member->member_name;

    DB::transaction(function () use ($member, $request, $oldName) {

        $member->update([
            'member_name' => $request->member_name
        ]);

        ExpenseSplit::where('member_name', $oldName)
            ->update(['member_name' => $request->member_name]);

        Expense::where('paid_by_name', $oldName)
            ->update(['paid_by_name' => $request->member_name]);
    });

    return back()->with('success', 'Member updated');
}


    // Show group details
    public function show($id)
    {

        $group = Group::with(['activeMembers', 'expenses'])
            ->where('is_active', 'Y')
            ->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | BALANCE CALCULATION (BY MEMBER NAME)
        |--------------------------------------------------------------------------
        */
        $balances = ExpenseSplit::select(
            'expense_splits.member_name',
            DB::raw('SUM(expense_splits.share_amount) as total_share')
        )
            ->join('expenses', 'expenses.id', '=', 'expense_splits.expense_id')
            ->where('expenses.group_id', $id)
            ->where('expenses.is_active', 'Y')
            ->groupBy('expense_splits.member_name')
            ->pluck('total_share', 'member_name');

        return view('groups.show', compact('group', 'balances'));
    }


  public function addMember(Request $request, $groupId)
{
    $request->validate([
        'member_name' => 'required|string|max:255'
    ]);

    $exists = GroupMember::where('group_id', $groupId)
        ->where('member_name', $request->member_name)
        ->where('is_active', 'Y')
        ->exists();

    if ($exists) {
        return back()
            ->withErrors(['member_name' => 'Member already exists in this group'])
            ->withInput();
    }

    GroupMember::create([
        'group_id'    => $groupId,
        'member_name' => $request->member_name,
        'is_active'   => 'Y'
    ]);

    return back()->with('success', 'Member added');
}


public function deleteMember($id)
{
    GroupMember::where('id', $id)
        ->update(['is_active' => 'N']);

    return back()->with('success', 'Member removed');
}

//Delete Group 
public function delete($id)
{
    Group::where('id', $id)
        ->update(['is_active' => 'N']);

    // Optional: also deactivate members & expenses
    GroupMember::where('group_id', $id)
        ->update(['is_active' => 'N']);

    Expense::where('group_id', $id)
        ->update(['is_active' => 'N']);

    return redirect()->route('groups.index')
        ->with('success', 'Group deleted successfully');
}


    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255'
    ]);

    Group::where('id', $id)
        ->where('is_active', 'Y')
        ->update([
            'name' => $request->name
        ]);

    return back()->with('success', 'Group name updated');
}



}


