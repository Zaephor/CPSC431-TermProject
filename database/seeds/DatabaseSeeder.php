<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Department;
use App\Course;
use App\Session;
use App\Assignment;

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // Generates test data via
        // php artisan db:seed
        // or php artisan migrate --seed

        $count['student'] = 200; // Number of students to generate
        $count['professor'] = 10; // Number of prof's to generate
        $count['admin'] = 5; // Number of admins to generate
        $count['session'] = 15; // Total Number of course sessions to generate
        $count['sessionEnroll'] = 16; // Number of students to enroll in each session
        $count['assignment'] = 10; // Number of HW assignments to generate per class, per student with random scores

        $student = array();
        for ($i = 1; $i <= $count['student']; $i++) {
            $student[] = User::create([
                'name' => 'Test Student' . $i,
                'email' => 'student' . $i . '@test.com',
                'password' => Hash::make('test'),
                'role' => 'student'
            ]);
        }
        $professor = array();
        for ($i = 1; $i <= $count['professor']; $i++) {
            $professor[] = User::create([
                'name' => 'Test Professor' . $i,
                'email' => 'professor' . $i . '@test.com',
                'password' => Hash::make('test'),
                'role' => 'faculty'
            ]);
        }
        $admin = array();
        for ($i = 1; $i <= $count['admin']; $i++) {
            $admin[] = User::create([
                'name' => 'Test Admin'.$i,
                'email' => 'admin'.$i.'@test.com',
                'password' => Hash::make('test'),
                'role' => 'admin'
            ]);
        }
        $cpsc = Department::create(['title' => 'Computer Science', 'code' => 'CPSC']);
        $math = Department::create(['title' => 'Mathematics', 'code' => 'MATH']);
        $engl = Department::create(['title' => 'English', 'code' => 'ENGL']);
        $phys = Department::create(['title' => 'Physics', 'code' => 'PHYS']);
        $biol = Department::create(['title' => 'Biology', 'code' => 'BIOL']);
        $course[] = Course::create([
            'department_id' => $cpsc->id,
            'title' => 'INTRODUCTION TO PROGRAMMING',
            'description' => 'Beginners course into C++',
            'code' => '120',
            'unitval' => 3
        ]);
        $course[] = Course::create([
            'department_id' => $cpsc->id,
            'title' => 'PROGRAMMING CONCEPTS',
            'description' => 'Introduction to object oriented programming',
            'code' => '121',
            'unitval' => 3
        ]);
        $course[] = Course::create([
            'department_id' => $cpsc->id,
            'title' => 'DATA STRUCTURE CONCEPTS',
            'description' => 'Introduction to classes and search algorithms',
            'code' => '131',
            'unitval' => 3
        ]);
        $course[] = Course::create([
            'department_id' => $math->id,
            'title' => 'ANALYTIC GEOMETRY & CALCULUS',
            'description' => 'More Math',
            'code' => '150',
            'unitval' => 4
        ]);
        $course[] = Course::create([
            'department_id' => $math->id,
            'title' => 'MATHEMATICAL STRUCTURES',
            'description' => 'Still more math',
            'code' => '270',
            'unitval' => 4
        ]);
        $course[] = Course::create([
            'department_id' => $phys->id,
            'title' => 'FUNDAMENT PHYS: MECHANICS',
            'description' => 'Physics of motion',
            'code' => '225',
            'unitval' => 4
        ]);
        $session = array();
        for ($i = 0; $i < $count['session']; $i++) {
            $count['course'] = sizeof($course);
            $rand['start'] = mt_rand(1420070400, 1422576000);
            $rand['end'] = mt_rand(1430438400, 1433030400);
            $session[] = Session::create([
                'course_id' => $course[$i % $count['course']]->id,
                'professor_id' => $professor[$i % $count['professor']]->id,
                'begins_on' => date("Y-m-d", $rand['start']),
                'ends_on' => date("Y-m-d", $rand['end'])
            ]);
        }
        $assignment = array();
        for ($i = 0; $i < $count['session']; $i++) { // Per session
            for($k=0;$k< $count['sessionEnroll'];$k++) { // Enroll students
                $someId = (($i+$k) % $count['student']); // Pick a student
                // Enroll the student to this session
                $student[$someId]->sessions()->attach($session[$i % $count['session']]->id);

                $assignment[] = Assignment::create([
                    'session_id' => $session[$i % $count['session']]->id,
                    'student_id' => $student[$someId]->id,
                    'assignment_code' => 'Midterm',
                    'score' => null
                ]);

                $assignment[] = Assignment::create([
                    'session_id' => $session[$i % $count['session']]->id,
                    'student_id' => $student[$someId]->id,
                    'assignment_code' => 'Final',
                    'score' => null
                ]);

                // Add some homework that may or may not have been graded
                $scores = array(null, 50, 60, 70, 75, 80, 90, 95, 100);
                for ($j = 0; $j < $count['assignment']; $j++) {
                    $assignment[] = Assignment::create([
                        'session_id' => $session[$i % $count['session']]->id,
                        'student_id' => $student[$someId]->id,
                        'assignment_code' => 'HW' . ($j + 1),
                        'score' => $scores[mt_rand(0, sizeof($scores) - 1)]
                    ]);
                }
            }
        }
    }

}