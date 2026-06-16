<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'subject',
        'class_group_id',
        'email',
        'user_id',
    ];

    /**
     * Get the class group that this teacher belongs to.
     */
    public function classGroup()
    {
        return $this->belongsTo(ClassGroup::class);
    }

    /**
     * Get the user account associated with this teacher.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
