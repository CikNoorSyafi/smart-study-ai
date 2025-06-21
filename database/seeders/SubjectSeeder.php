<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing subjects safely
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Subject::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $subjects = [
            // FORM 4 SUBJECTS
            // Core Subjects (Compulsory)
            [
                'subject_id' => (string) Str::uuid(),
                'name' => 'Bahasa Melayu (Form 4)',
                'description' => 'Bahasa kebangsaan Malaysia yang merangkumi kesusasteraan, tatabahasa, dan kemahiran komunikasi.',
                'form_level' => 'Form 4',
                'category' => 'Core',
                'subject_code' => 'BM',
            ],
            [
                'subject_id' => (string) Str::uuid(),
                'name' => 'English (Form 4)',
                'description' => 'English language skills including reading, writing, speaking, and listening comprehension.',
                'form_level' => 'Form 4',
                'category' => 'Core',
                'subject_code' => 'BI',
            ],
            [
                'subject_id' => (string) Str::uuid(),
                'name' => 'Mathematics (Form 4)',
                'description' => 'Advanced mathematics including algebra, geometry, trigonometry, and statistics.',
                'form_level' => 'Form 4',
                'category' => 'Core',
                'subject_code' => 'MAT',
            ],
            [
                'subject_id' => (string) Str::uuid(),
                'name' => 'History (Form 4)',
                'description' => 'Malaysian and world history, focusing on significant events and their impact.',
                'form_level' => 'Form 4',
                'category' => 'Core',
                'subject_code' => 'SEJ',
            ],
            [
                'subject_id' => (string) Str::uuid(),
                'name' => 'Moral Education (Form 4)',
                'description' => 'Moral values, ethics, and character development for non-Muslim students.',
                'form_level' => 'Form 4',
                'category' => 'Core',
                'subject_code' => 'PM',
            ],
            [
                'subject_id' => (string) Str::uuid(),
                'name' => 'Islamic Studies (Form 4)',
                'description' => 'Islamic teachings, values, and practices for Muslim students.',
                'form_level' => 'Form 4',
                'category' => 'Core',
                'subject_code' => 'PI',
            ],

            // Science Stream
            [
                'subject_id' => (string) Str::uuid(),
                'name' => 'Physics (Form 4)',
                'description' => 'Study of matter, energy, motion, and forces in the physical world.',
                'form_level' => 'Form 4',
                'category' => 'Science',
                'subject_code' => 'FIZ',
            ],
            [
                'subject_id' => (string) Str::uuid(),
                'name' => 'Chemistry (Form 4)',
                'description' => 'Study of matter, its properties, composition, and chemical reactions.',
                'form_level' => 'Form 4',
                'category' => 'Science',
                'subject_code' => 'KIM',
            ],
            [
                'subject_id' => (string) Str::uuid(),
                'name' => 'Biology (Form 4)',
                'description' => 'Study of living organisms, their structure, function, and interactions.',
                'form_level' => 'Form 4',
                'category' => 'Science',
                'subject_code' => 'BIO',
            ],
            [
                'subject_id' => (string) Str::uuid(),
                'name' => 'Additional Mathematics (Form 4)',
                'description' => 'Advanced mathematical concepts including calculus, complex numbers, and advanced algebra.',
                'form_level' => 'Form 4',
                'category' => 'Science',
                'subject_code' => 'MAT-T',
            ],

            // Arts Stream
            [
                'subject_id' => (string) Str::uuid(),
                'name' => 'Geography (Form 4)',
                'description' => 'Study of Earth\'s physical features, climate, and human-environment interactions.',
                'form_level' => 'Form 4',
                'category' => 'Arts',
                'subject_code' => 'GEO',
            ],
            [
                'subject_id' => (string) Str::uuid(),
                'name' => 'Economics (Form 4)',
                'description' => 'Study of production, distribution, and consumption of goods and services.',
                'form_level' => 'Form 4',
                'category' => 'Arts',
                'subject_code' => 'EKO',
            ],
            [
                'subject_id' => (string) Str::uuid(),
                'name' => 'Accounting (Form 4)',
                'description' => 'Principles of financial recording, reporting, and business management.',
                'form_level' => 'Form 4',
                'category' => 'Arts',
                'subject_code' => 'PEK',
            ],
            [
                'subject_id' => (string) Str::uuid(),
                'name' => 'Business Studies (Form 4)',
                'description' => 'Fundamentals of business operations, management, and entrepreneurship.',
                'form_level' => 'Form 4',
                'category' => 'Arts',
                'subject_code' => 'PN',
            ],

            // Technical/Vocational
            [
                'subject_id' => (string) Str::uuid(),
                'name' => 'Information and Communication Technology (Form 4)',
                'description' => 'Computer skills, programming, and digital literacy.',
                'form_level' => 'Form 4',
                'category' => 'Technical',
                'subject_code' => 'ICT',
            ],

            // FORM 5 SUBJECTS
            // Core Subjects (Compulsory)
            [
                'subject_id' => (string) Str::uuid(),
                'name' => 'Bahasa Melayu (Form 5)',
                'description' => 'Lanjutan kemahiran bahasa Melayu dengan penekanan kepada kesusasteraan dan penulisan kreatif.',
                'form_level' => 'Form 5',
                'category' => 'Core',
                'subject_code' => 'BM',
            ],
            [
                'subject_id' => (string) Str::uuid(),
                'name' => 'English (Form 5)',
                'description' => 'Advanced English language skills with focus on literature and critical analysis.',
                'form_level' => 'Form 5',
                'category' => 'Core',
                'subject_code' => 'BI',
            ],
            [
                'subject_id' => (string) Str::uuid(),
                'name' => 'Mathematics (Form 5)',
                'description' => 'Advanced mathematical concepts preparing for SPM examination.',
                'form_level' => 'Form 5',
                'category' => 'Core',
                'subject_code' => 'MAT',
            ],
            [
                'subject_id' => (string) Str::uuid(),
                'name' => 'History (Form 5)',
                'description' => 'Comprehensive study of Malaysian independence and post-independence development.',
                'form_level' => 'Form 5',
                'category' => 'Core',
                'subject_code' => 'SEJ',
            ],
            [
                'subject_id' => (string) Str::uuid(),
                'name' => 'Moral Education (Form 5)',
                'description' => 'Advanced moral reasoning and ethical decision-making for non-Muslim students.',
                'form_level' => 'Form 5',
                'category' => 'Core',
                'subject_code' => 'PM',
            ],
            [
                'subject_id' => (string) Str::uuid(),
                'name' => 'Islamic Studies (Form 5)',
                'description' => 'Advanced Islamic knowledge and contemporary Islamic issues for Muslim students.',
                'form_level' => 'Form 5',
                'category' => 'Core',
                'subject_code' => 'PI',
            ],

            // Science Stream
            [
                'subject_id' => (string) Str::uuid(),
                'name' => 'Physics (Form 5)',
                'description' => 'Advanced physics concepts including waves, electricity, and modern physics.',
                'form_level' => 'Form 5',
                'category' => 'Science',
                'subject_code' => 'FIZ',
            ],
            [
                'subject_id' => (string) Str::uuid(),
                'name' => 'Chemistry (Form 5)',
                'description' => 'Advanced chemistry including organic chemistry and chemical analysis.',
                'form_level' => 'Form 5',
                'category' => 'Science',
                'subject_code' => 'KIM',
            ],
            [
                'subject_id' => (string) Str::uuid(),
                'name' => 'Biology (Form 5)',
                'description' => 'Advanced biological concepts including genetics, ecology, and biotechnology.',
                'form_level' => 'Form 5',
                'category' => 'Science',
                'subject_code' => 'BIO',
            ],
            [
                'subject_id' => (string) Str::uuid(),
                'name' => 'Additional Mathematics (Form 5)',
                'description' => 'Advanced calculus, differential equations, and mathematical modeling.',
                'form_level' => 'Form 5',
                'category' => 'Science',
                'subject_code' => 'MAT-T',
            ],

            // Arts Stream
            [
                'subject_id' => (string) Str::uuid(),
                'name' => 'Geography (Form 5)',
                'description' => 'Advanced geographical concepts and regional studies.',
                'form_level' => 'Form 5',
                'category' => 'Arts',
                'subject_code' => 'GEO',
            ],
            [
                'subject_id' => (string) Str::uuid(),
                'name' => 'Economics (Form 5)',
                'description' => 'Macroeconomics, international trade, and economic development.',
                'form_level' => 'Form 5',
                'category' => 'Arts',
                'subject_code' => 'EKO',
            ],
            [
                'subject_id' => (string) Str::uuid(),
                'name' => 'Accounting (Form 5)',
                'description' => 'Advanced accounting principles and financial statement analysis.',
                'form_level' => 'Form 5',
                'category' => 'Arts',
                'subject_code' => 'PEK',
            ],
            [
                'subject_id' => (string) Str::uuid(),
                'name' => 'Business Studies (Form 5)',
                'description' => 'Advanced business concepts and case study analysis.',
                'form_level' => 'Form 5',
                'category' => 'Arts',
                'subject_code' => 'PN',
            ],

            // Technical/Vocational
            [
                'subject_id' => (string) Str::uuid(),
                'name' => 'Information and Communication Technology (Form 5)',
                'description' => 'Advanced ICT skills including database management and web development.',
                'form_level' => 'Form 5',
                'category' => 'Technical',
                'subject_code' => 'ICT',
            ],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }
    }
}
