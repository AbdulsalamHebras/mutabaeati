<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\User;
use App\Models\University;
use App\Models\Batch;
use App\Models\Specialization;
use App\Models\Student;
use App\Models\Month;
use App\Models\Subscription;
use App\Models\ExamDistribution;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admins
        $admin = Admin::updateOrCreate(
            ['email' => 'admin@admin.com'],
            ['name' => 'الأدمن الرئيسي', 'password' => Hash::make('password')]
        );

        // 2. Create Supervisors (Users)
        $supervisors = [];
        for ($i = 1; $i <= 3; $i++) {
            $supervisors[] = User::updateOrCreate(
                ['email' => "supervisor{$i}@test.com"],
                [
                    'name' => "المشرف {$i}",
                    'password' => Hash::make('password'),
                    'role' => $i % 2 == 0 ? 'muraqib' : 'muhdir'
                ]
            );
        }

        // 3. Create Infrastructure
        $uni = University::firstOrCreate(['name' => 'جامعة الملك سعود']);
        $uni2 = University::firstOrCreate(['name' => 'جامعة جدة']);

        $batch = Batch::firstOrCreate(['name' => 'دفعة 2024', 'university_id' => $uni->id]);
        $batch2 = Batch::firstOrCreate(['name' => 'دفعة 2025', 'university_id' => $uni2->id]);

        $spec = Specialization::firstOrCreate(['name' => 'علوم الحاسب']);
        $spec2 = Specialization::firstOrCreate(['name' => 'إدارة الأعمال']);

        // 4. Create Students
        $studentsCount = 10;
        $studentNames = [
            'أحمد محمد علي', 'سارة خالد عمر', 'فهد عبدالله حسن', 
            'ريم منصور صقر', 'ياسر إبراهيم فوزي', 'نورة جابر سعيد',
            'خالد وليد تركي', 'مها سلمان بدر', 'تركي عبدالعزيز راشد', 'ليلى حمدان فهد'
        ];

        $allStudents = [];
        foreach ($studentNames as $index => $name) {
            $allStudents[] = Student::create([
                'name' => $name,
                'phone' => '050' . rand(1000000, 9999999),
                'national_id' => '10' . rand(10000000, 99999999),
                'email' => "student{$index}@example.com",
                'specialization_id' => $index % 2 == 0 ? $spec->id : $spec2->id,
                'university_id' => $index % 2 == 0 ? $uni->id : $uni2->id,
                'batch_id' => $index % 2 == 0 ? $batch->id : $batch2->id,
                'muhdir_id' => $supervisors[array_rand($supervisors)]->id,
                'admin_id' => $admin->id,
                'status' => 'نشط',
                'duration' => 'عام',
                'platform_password' => 'pass123',
                'notes' => 'طالب مجتهد'
            ]);
        }

        // 5. Create Months (if not seeded)
        if (Month::count() == 0) {
            $this->call(MonthSeeder::class);
        }
        $months = Month::all();

        // 6. Create Subscriptions
        foreach ($allStudents as $student) {
            // Pick 3 random months to have paid for this student
            $paidMonths = $months->random(3);
            foreach ($paidMonths as $month) {
                Subscription::create([
                    'student_id' => $student->id,
                    'month_id' => $month->id,
                    'amount' => 500,
                    'is_paid' => true,
                    'notes' => 'تم دفع الرسوم'
                ]);
            }
        }

        // 7. Create Exam Distributions
        $periods = ['من 4 الى 5', 'من 5 الى 6', 'من 6 الى 7', 'من 7 الى 8'];
        foreach ($allStudents as $index => $student) {
            ExamDistribution::create([
                'student_id' => $student->id,
                'supervisor_id' => $supervisors[array_rand($supervisors)]->id,
                'period' => $periods[array_rand($periods)],
                'room_number' => 'قاعة ' . rand(101, 105),
                'seat_number' => 'S-' . ($index + 1)
            ]);
        }
    }
}
