<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

class Lesson extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id','subject', 'day', 'start_time', 'end_time'
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];
    public function student(){
        return $this->belongsTo(Student::class);
    }
}
