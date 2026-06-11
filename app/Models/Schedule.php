<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'schudelis';
    protected $guarded = [];
    // Schudeli.php model
    public function smena() {
        return $this->belongsTo(SmenaType::class, 'smena_id', 'id');
    }
    public function lesson() {
        return $this->belongsTo(Lesson::class, 'lesson_id', 'id');
    }
   // App\Models\Schedule.php
public function employee()
{
    return $this->belongsTo(Employee::class);
}





}
