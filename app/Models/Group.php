<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['name', 'is_active'];

    public function members()
    {
        return $this->hasMany(GroupMember::class);
    }

    public function activeMembers()
    {
        return $this->hasMany(GroupMember::class)
                    ->where('is_active', 'Y');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class)
                    ->where('is_active', 'Y');
    }
}

