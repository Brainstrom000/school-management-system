<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Fee;
use App\Models\Mark;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with sample School Management data.
     */
    public function run(): void
    {
        // ---- Admin ----
        $admin = User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@school.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // ---- Classes (with monthly fee in PKR) ----
        $class6 = SchoolClass::create(['name' => 'Class 6', 'fee_amount' => 4000]);
        $class7 = SchoolClass::create(['name' => 'Class 7', 'fee_amount' => 4500]);
        $class8 = SchoolClass::create(['name' => 'Class 8', 'fee_amount' => 5000]);

        // ---- Subjects (assigned to classes) ----
        $mathC6 = Subject::create(['name' => 'Math', 'school_class_id' => $class6->id]);
        Subject::create(['name' => 'English', 'school_class_id' => $class6->id]);
        Subject::create(['name' => 'Science', 'school_class_id' => $class7->id]);
        Subject::create(['name' => 'Computer', 'school_class_id' => $class8->id]);

        // ---- Teachers ----
        $teacherUser = User::create([
            'name'     => 'Sara Ahmed',
            'email'    => 'teacher@school.com',
            'password' => Hash::make('password'),
            'role'     => 'teacher',
        ]);

        $teacher = Teacher::create([
            'user_id' => $teacherUser->id,
            'subject' => 'Math',
            'salary'  => 55000,
        ]);

        $teacher->subjects()->attach($mathC6->id);

        // ---- Students ----
        $studentsData = [
            ['name' => 'Ali Khan', 'class' => $class6->name],
            ['name' => 'Ayesha Malik', 'class' => $class7->name],
            ['name' => 'Bilal Hussain', 'class' => $class8->name],
        ];

        foreach ($studentsData as $index => $data) {
            $email = 'student' . ($index + 1) . '@school.com';

            $user = User::create([
                'name'     => $data['name'],
                'email'    => $email,
                'password' => Hash::make('password'),
                'role'     => 'student',
            ]);

            $student = Student::create([
                'user_id' => $user->id,
                'phone'   => '030012345' . $index,
                'address' => 'Lahore, Pakistan',
                'class'   => $data['class'],
            ]);

            $student->subjects()->attach($mathC6->id);

            // Sample attendance (last 3 days)
            for ($d = 0; $d < 3; $d++) {
                Attendance::create([
                    'student_id' => $student->id,
                    'date'       => now()->subDays($d)->toDateString(),
                    'status'     => $d === 1 ? 'Absent' : 'Present',
                ]);
            }

            // Sample marks
            Mark::create([
                'student_id'  => $student->id,
                'subject_id'  => $mathC6->id,
                'marks'       => 78,
                'total_marks' => 100,
                'grade'       => 'A',
            ]);

            // Sample fee (based on class fee)
            $classFee = SchoolClass::where('name', $data['class'])->value('fee_amount');

            Fee::create([
                'student_id' => $student->id,
                'title'      => 'Tuition Fee - ' . now()->format('F Y'),
                'amount'     => $classFee,
                'due_date'   => now()->addDays(10),
            ]);
        }

        $this->command->info('Sample data seeded:');
        $this->command->info('Admin   -> admin@school.com   / password');
        $this->command->info('Teacher -> teacher@school.com / password');
        $this->command->info('Student -> student1@school.com / password');
    }
}
