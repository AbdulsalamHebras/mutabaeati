<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'muhdir_id', 'title', 'file_path', 'month', 'year'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function muhdir()
    {
        return $this->belongsTo(User::class, 'muhdir_id');
    }
}
