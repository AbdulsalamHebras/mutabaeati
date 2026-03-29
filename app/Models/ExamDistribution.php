<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamDistribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'supervisor_id', 'period', 'subject', 'date', 'exam_day'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }
}
