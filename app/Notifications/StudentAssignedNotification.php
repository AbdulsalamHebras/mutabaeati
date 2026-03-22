<?php
namespace App\Notifications;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class StudentAssignedNotification extends Notification
{
    protected $student;

    public function __construct($student, $type = 'assigned')
    {
        $this->student = $student;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['database']; // نخزن في DB
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->type === 'assigned'
                ? 'تم إضافة الطالب ' . $this->student->name . ' لك، الرجاء التأكد من جدوله'
                : 'تم سحب الطالب ' . $this->student->name . ' منك',

            'student_id' => $this->student->id,
            'url' => route('muhdir.distribution'), // تقدر تغيرها
        ];
    }
}
