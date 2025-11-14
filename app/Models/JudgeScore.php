<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JudgeScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'judge_id',
        'event_id',
        'registration_id',
        'score',
        'grade',
    ];

    public function judge()
    {
        return $this->belongsTo(User::class, 'judge_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }
}
