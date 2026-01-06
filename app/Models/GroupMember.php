<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'group_id',
        'member_name',
        'is_active',
        'joined_at'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
