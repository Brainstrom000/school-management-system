<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

/**
 * Generates ~50,000 students (with logins, attendance, and fee records)
 * for performance testing at scale.
 *
 * IMPORTANT — this is a LOCAL/TESTING seeder only. Do not run it against
 * production. `faker` is a dev-only package, and this will add a huge
 * amount of throwaway data to your database.
 *
 * Run with:
 *   php artisan db:seed --class=StudentSeeder
 *
 * On a normal dev machine this takes a few minutes (mostly the
 * attendance rows — 50,000 students x 14 days = ~700,000 rows).
 */
class StudentSeeder extends Seeder
{
    protected const TOTAL_STUDENTS = 50000;
    protected const CHUNK_SIZE = 2000;
    protected const ATTENDANCE_DAYS = 14;
    protected const FEE_MONTHS = 3;

    public function run(): void
    {
        if (DB::table('users')->where('email', 'student0@test.com')->exists()) {
            $this->command->warn('StudentSeeder already looks like it has been run (student0@test.com exists). Skipping to avoid duplicate emails.');
            $this->command->warn('If you want to re-seed, delete those test students first (e.g. via a fresh migrate).');
            return;
        }

        $faker = Faker::create();

        // ---- Make sure we have classes to assign students to ----
        if (SchoolClass::count() === 0) {
            SchoolClass::insert([
                ['name' => 'Class 6', 'fee_amount' => 4000, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Class 7', 'fee_amount' => 4500, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Class 8', 'fee_amount' => 5000, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Class 9', 'fee_amount' => 5500, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Class 10', 'fee_amount' => 6000, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        $classes = SchoolClass::all(['name', 'fee_amount']);

        // Hash "password" once — every seeded student can log in with it.
        $hashedPassword = Hash::make('password');

        $this->command->info('Seeding ' . number_format(self::TOTAL_STUDENTS) . ' students...');
        $bar = $this->command->getOutput()->createProgressBar(self::TOTAL_STUDENTS / self::CHUNK_SIZE);

        // We insert users first, note the starting id, then insert
        // students referencing those ids directly — avoids calling
        // Eloquent::create() 50,000 times (which would be far too slow).
        $startUserId = (int) (DB::table('users')->max('id') ?? 0) + 1;

        for ($offset = 0; $offset < self::TOTAL_STUDENTS; $offset += self::CHUNK_SIZE) {
            $userRows = [];
            $studentRows = [];
            $now = now();

            for ($i = 0; $i < self::CHUNK_SIZE; $i++) {
                $n = $offset + $i;
                if ($n >= self::TOTAL_STUDENTS) {
                    break;
                }

                $userId = $startUserId + $n;
                $name = $faker->firstName() . ' ' . $faker->lastName();
                $class = $classes->random();

                $userRows[] = [
                    'name'              => $name,
                    'email'             => 'student' . $n . '@test.com',
                    'password'          => $hashedPassword,
                    'role'              => 'student',
                    'email_verified_at' => $now,
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ];

                $studentRows[] = [
                    'user_id'    => $userId,
                    'phone'      => '03' . $faker->numerify('#########'),
                    'address'    => $faker->city() . ', Pakistan',
                    'class'      => $class->name,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            DB::table('users')->insert($userRows);
            DB::table('students')->insert($studentRows);

            $bar->advance();
        }

        $bar->finish();
        $this->command->newLine();

        // ---- Attendance (last 14 days) ----
        $this->command->info('Seeding attendance (' . self::ATTENDANCE_DAYS . ' days per student)...');
        $this->seedAttendance($startUserId);

        // ---- Fees (last 3 months) ----
        $this->command->info('Seeding fees (' . self::FEE_MONTHS . ' months per student)...');
        $this->seedFees($startUserId);

        $this->command->info('Done. ' . number_format(self::TOTAL_STUDENTS) . ' students seeded.');
        $this->command->info('Sample login: student0@test.com / password');
    }

    protected function seedAttendance(int $startUserId): void
    {
        // student_id isn't the same as user_id, so grab the id range we
        // just inserted (students inserted in the same order as users).
        $studentIds = DB::table('students')
            ->where('user_id', '>=', $startUserId)
            ->orderBy('id')
            ->pluck('id');

        $bar = $this->command->getOutput()->createProgressBar($studentIds->count());
        $rows = [];

        foreach ($studentIds as $studentId) {
            for ($d = 0; $d < self::ATTENDANCE_DAYS; $d++) {
                $roll = mt_rand(1, 100);
                $status = $roll <= 85 ? 'Present' : ($roll <= 95 ? 'Absent' : 'Leave');

                $rows[] = [
                    'student_id' => $studentId,
                    'date'       => now()->subDays($d)->toDateString(),
                    'status'     => $status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (count($rows) >= self::CHUNK_SIZE) {
                DB::table('attendances')->insert($rows);
                $rows = [];
            }

            $bar->advance();
        }

        if (!empty($rows)) {
            DB::table('attendances')->insert($rows);
        }

        $bar->finish();
        $this->command->newLine();
    }

    protected function seedFees(int $startUserId): void
    {
        $students = DB::table('students')
            ->where('user_id', '>=', $startUserId)
            ->orderBy('id')
            ->get(['id', 'class']);

        $classFees = SchoolClass::pluck('fee_amount', 'name');

        $bar = $this->command->getOutput()->createProgressBar($students->count());
        $rows = [];

        foreach ($students as $student) {
            $amount = $classFees[$student->class] ?? 5000;

            for ($m = 0; $m < self::FEE_MONTHS; $m++) {
                $month = now()->subMonths($m);
                $isPaid = mt_rand(1, 100) <= 70; // 70% paid, 30% unpaid

                $rows[] = [
                    'student_id' => $student->id,
                    'title'      => 'Tuition Fee - ' . $month->format('F Y'),
                    'amount'     => $amount,
                    'due_date'   => $month->copy()->addDays(10)->toDateString(),
                    'status'     => $isPaid ? 'paid' : 'unpaid',
                    'paid_at'    => $isPaid ? $month->copy()->addDays(mt_rand(1, 9)) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (count($rows) >= self::CHUNK_SIZE) {
                DB::table('fees')->insert($rows);
                $rows = [];
            }

            $bar->advance();
        }

        if (!empty($rows)) {
            DB::table('fees')->insert($rows);
        }

        $bar->finish();
        $this->command->newLine();
    }
}
