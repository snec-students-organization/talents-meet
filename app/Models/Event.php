<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
    'name',
    'category',
    'type',
    'stream',
    'level',
    'stage_type',
    'allowed_streams',
    'max_participants',
    'max_institution_entries',
    'created_by',
];

    public function registrations()
{
    return $this->hasMany(\App\Models\Registration::class);
}
public function participants()
{
    return $this->hasMany(\App\Models\Participant::class);
}


}
