<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Note;
use App\Models\User;
use Illuminate\Support\Str;

class QualityQuestionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a sample user and note for relationships
        $user = User::first();
        $note = Note::first();

        if (!$user || !$note) {
            $this->command->info('No users or notes found. Please seed users and notes first.');
            return;
        }

        // Malaysian Education Questions
        $questions = [
            // Mathematics Questions
            [
                'question_text' => 'Solve for x: 2x + 5 = 13',
                'question_type' => 'multiple_choice',
                'difficulty' => 'easy',
                'explanation' => 'Subtract 5 from both sides, then divide by 2',
                'answers' => [
                    ['text' => 'x = 4', 'correct' => true],
                    ['text' => 'x = 6', 'correct' => false],
                    ['text' => 'x = 8', 'correct' => false],
                    ['text' => 'x = 9', 'correct' => false],
                ]
            ],
            [
                'question_text' => 'What is the area of a circle with radius 7 cm? (Use π = 22/7)',
                'question_type' => 'multiple_choice',
                'difficulty' => 'medium',
                'explanation' => 'Area = πr² = (22/7) × 7² = 154 cm²',
                'answers' => [
                    ['text' => '154 cm²', 'correct' => true],
                    ['text' => '144 cm²', 'correct' => false],
                    ['text' => '164 cm²', 'correct' => false],
                    ['text' => '174 cm²', 'correct' => false],
                ]
            ],

            // Science Questions
            [
                'question_text' => 'What is the chemical formula for water?',
                'question_type' => 'multiple_choice',
                'difficulty' => 'easy',
                'explanation' => 'Water consists of 2 hydrogen atoms and 1 oxygen atom',
                'answers' => [
                    ['text' => 'H₂O', 'correct' => true],
                    ['text' => 'CO₂', 'correct' => false],
                    ['text' => 'NaCl', 'correct' => false],
                    ['text' => 'CH₄', 'correct' => false],
                ]
            ],
            [
                'question_text' => 'Which organ in the human body produces insulin?',
                'question_type' => 'multiple_choice',
                'difficulty' => 'medium',
                'explanation' => 'The pancreas produces insulin to regulate blood sugar levels',
                'answers' => [
                    ['text' => 'Pancreas', 'correct' => true],
                    ['text' => 'Liver', 'correct' => false],
                    ['text' => 'Kidney', 'correct' => false],
                    ['text' => 'Heart', 'correct' => false],
                ]
            ],

            // History Questions
            [
                'question_text' => 'When did Malaysia gain independence?',
                'question_type' => 'multiple_choice',
                'difficulty' => 'easy',
                'explanation' => 'Malaysia gained independence from British colonial rule on August 31, 1957',
                'answers' => [
                    ['text' => 'August 31, 1957', 'correct' => true],
                    ['text' => 'August 31, 1963', 'correct' => false],
                    ['text' => 'September 16, 1963', 'correct' => false],
                    ['text' => 'December 31, 1957', 'correct' => false],
                ]
            ],
            [
                'question_text' => 'Who was the first Prime Minister of Malaysia?',
                'question_type' => 'multiple_choice',
                'difficulty' => 'medium',
                'explanation' => 'Tunku Abdul Rahman was Malaysia\'s first Prime Minister from 1957 to 1970',
                'answers' => [
                    ['text' => 'Tunku Abdul Rahman', 'correct' => true],
                    ['text' => 'Tun Abdul Razak', 'correct' => false],
                    ['text' => 'Tun Hussein Onn', 'correct' => false],
                    ['text' => 'Tun Dr. Mahathir', 'correct' => false],
                ]
            ],

            // English Questions
            [
                'question_text' => 'Choose the correct sentence:',
                'question_type' => 'multiple_choice',
                'difficulty' => 'easy',
                'explanation' => 'Subject-verb agreement: "She" is singular, so use "goes"',
                'answers' => [
                    ['text' => 'She goes to school every day.', 'correct' => true],
                    ['text' => 'She go to school every day.', 'correct' => false],
                    ['text' => 'She going to school every day.', 'correct' => false],
                    ['text' => 'She gone to school every day.', 'correct' => false],
                ]
            ],

            // True/False Questions
            [
                'question_text' => 'The Earth revolves around the Sun.',
                'question_type' => 'true_false',
                'difficulty' => 'easy',
                'explanation' => 'The Earth orbits the Sun in approximately 365.25 days',
                'answers' => [
                    ['text' => 'True', 'correct' => true],
                    ['text' => 'False', 'correct' => false],
                ]
            ],
            [
                'question_text' => 'Kuala Lumpur is the capital city of Malaysia.',
                'question_type' => 'true_false',
                'difficulty' => 'easy',
                'explanation' => 'Kuala Lumpur is indeed the capital and largest city of Malaysia',
                'answers' => [
                    ['text' => 'True', 'correct' => true],
                    ['text' => 'False', 'correct' => false],
                ]
            ],

            // Short Answer Questions
            [
                'question_text' => 'Name three states in Malaysia that start with the letter "P".',
                'question_type' => 'short_answer',
                'difficulty' => 'medium',
                'explanation' => 'Examples include Penang, Perak, Pahang, Perlis, and Putrajaya',
                'answers' => [
                    ['text' => 'Penang, Perak, Pahang', 'correct' => true],
                    ['text' => 'Penang, Perak, Perlis', 'correct' => true],
                    ['text' => 'Pahang, Perlis, Putrajaya', 'correct' => true],
                ]
            ],

            // Essay Questions
            [
                'question_text' => 'Explain the importance of preserving Malaysia\'s cultural diversity. Discuss at least three benefits and provide examples.',
                'question_type' => 'essay',
                'difficulty' => 'hard',
                'explanation' => 'Students should discuss cultural tolerance, economic benefits, social harmony, and provide specific examples from Malaysian society',
                'answers' => [
                    ['text' => 'Sample answer covering cultural tolerance, economic benefits, and social harmony with Malaysian examples', 'correct' => true],
                ]
            ],
        ];

        foreach ($questions as $questionData) {
            // Create the question
            $question = Question::create([
                'question_id' => (string) Str::uuid(),
                'note_id' => $note->note_id,
                'user_id' => $user->user_id,
                'question_text' => $questionData['question_text'],
                'question_type' => $questionData['question_type'],
                'difficulty' => $questionData['difficulty'],
                'generated_by' => 'Manual',
                'explanation' => $questionData['explanation'],
            ]);

            // Create the answers
            foreach ($questionData['answers'] as $answerData) {
                Answer::create([
                    'answer_id' => (string) Str::uuid(),
                    'question_id' => $question->question_id,
                    'answer_text' => $answerData['text'],
                    'is_correct' => $answerData['correct'],
                    'explanation' => $answerData['correct'] ? 'This is the correct answer.' : 'This is not the correct answer.',
                ]);
            }
        }

        $this->command->info('Quality questions seeded successfully!');
    }
}
