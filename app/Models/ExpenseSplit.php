<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseSplit extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'expense_id',
        'member_name',
        'share_amount',
        'is_settled'
    ];

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }
}
