<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'month_id', 'amount', 'is_paid', 'notes'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function month()
    {
        return $this->belongsTo(Month::class);
    }
}
