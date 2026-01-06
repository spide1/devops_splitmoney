<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'group_id',
        'paid_by_name',
        'amount',
        'description',
        'is_active'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function splits()
    {
        return $this->hasMany(ExpenseSplit::class);
    }
}
