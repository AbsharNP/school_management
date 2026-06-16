<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'roll_number',
        'class_group_id',
        'guardian_name',
        'guardian_phone',
        'address',
    ];

    /**
     * Get the class group that this student belongs to.
     */
    public function classGroup()
    {
        return $this->belongsTo(ClassGroup::class);
    }
}
