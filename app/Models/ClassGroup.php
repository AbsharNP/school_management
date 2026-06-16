<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the students that belong to this class group.
     */
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Get the users that belong to this class group.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
