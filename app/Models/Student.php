<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
         'uid',
        'name',
        'email',
        'phone',
        'gender',
        'institution_id',
    ];

    // Each student belongs to one institution (user with role=institution)
    public function institution()
    {
        return $this->belongsTo(User::class, 'institution_id');
    }

    // A student can have many event registrations
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}
