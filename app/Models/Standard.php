<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Standard extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'classgroup_id',
    ];

    public function classGroup()
    {
        return $this->belongsTo(ClassGroup::class, 'classgroup_id');
    }
}
