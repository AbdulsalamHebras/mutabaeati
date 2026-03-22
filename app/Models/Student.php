<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'muhdir_id', 'phone', 'national_id', 'email',
        'specialization_id', 'duration', 'section', 'university_id',
        'batch_id', 'academic_id', 'status', 'platform_password', 'notes',
        'admin_id'
    ];

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function months()
    {
        return $this->belongsToMany(Month::class, 'subscriptions')
            ->withPivot(['amount', 'is_paid', 'notes'])
            ->withTimestamps();
    }

    public function muhdir()
    {
        return $this->belongsTo(User::class, 'muhdir_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
    public function examDistribution()
    {
        return $this->hasOne(\App\Models\ExamDistribution::class, 'student_id');
    }
}
