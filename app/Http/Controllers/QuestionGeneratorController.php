<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QuestionGenerator;
use App\Services\GroqQuestionGenerator;
use Illuminate\Support\Facades\Log;

class QuestionGeneratorController extends Controller
{
    private $localGenerator;
    private $groqGenerator;

    public function __construct(QuestionGenerator $localGenerator, GroqQuestionGenerator $groqGenerator)
    {
        $this->localGenerator = $localGenerator;
        $this->groqGenerator = $groqGenerator;
    }

    /**
     * Show generator selection page
     */
    public function index()
    {
        // Test both generators
        $localStatus = $this->testLocalGenerator();
        $groqStatus = $this->groqGenerator->testConnection();

        return view('question-generator.index', compact('localStatus', 'groqStatus'));
    }

    /**
     * Generate questions using selected generator
     */
    public function generate(Request $request)
    {
        $request->validate([
            'generator_type' => 'required|in:local,groq',
            'text' => 'required|string|min:50',
            'question_count' => 'required|integer|min:1|max:50',
            'difficulty' => 'required|in:easy,medium,hard',
            'question_types' => 'required|array',
            'question_types.*' => 'in:multiple_choice,true_false,short_answer,essay',
        ]);

        try {
            $generator = $this->getGenerator($request->generator_type);
            
            $questions = $generator->generateQuestions(
                $request->text,
                $request->question_count,
                $request->difficulty,
                $request->question_types
            );

            $stats = $this->calculateStats($questions);

            return response()->json([
                'success' => true,
                'generator_used' => $request->generator_type,
                'questions' => $questions,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Question Generation Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate questions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Compare generators side by side
     */
    public function compare(Request $request)
    {
        $request->validate([
            'text' => 'required|string|min:50',
            'question_count' => 'required|integer|min:1|max:20',
            'difficulty' => 'required|in:easy,medium,hard',
            'question_types' => 'required|array',
            'question_types.*' => 'in:multiple_choice,true_false,short_answer,essay',
        ]);

        try {
            $results = [];

            // Generate with local generator
            try {
                $localQuestions = $this->localGenerator->generateQuestions(
                    $request->text,
                    $request->question_count,
                    $request->difficulty,
                    $request->question_types
                );
                $results['local'] = [
                    'success' => true,
                    'questions' => $localQuestions,
                    'stats' => $this->calculateStats($localQuestions)
                ];
            } catch (\Exception $e) {
                $results['local'] = [
                    'success' => false,
                    'error' => $e->getMessage()
                ];
            }

            // Generate with Groq
            try {
                $groqQuestions = $this->groqGenerator->generateQuestions(
                    $request->text,
                    $request->question_count,
                    $request->difficulty,
                    $request->question_types
                );
                $results['groq'] = [
                    'success' => true,
                    'questions' => $groqQuestions,
                    'stats' => $this->calculateStats($groqQuestions)
                ];
            } catch (\Exception $e) {
                $results['groq'] = [
                    'success' => false,
                    'error' => $e->getMessage()
                ];
            }

            return response()->json([
                'success' => true,
                'results' => $results
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Comparison failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test generator performance
     */
    public function test(Request $request)
    {
        $request->validate([
            'generator_type' => 'required|in:local,groq,both'
        ]);

        $results = [];

        if ($request->generator_type === 'local' || $request->generator_type === 'both') {
            $results['local'] = $this->testLocalGenerator();
        }

        if ($request->generator_type === 'groq' || $request->generator_type === 'both') {
            $results['groq'] = $this->groqGenerator->testConnection();
        }

        return response()->json([
            'success' => true,
            'results' => $results
        ]);
    }

    /**
     * Get generator instance based on type
     */
    private function getGenerator($type)
    {
        switch ($type) {
            case 'groq':
                return $this->groqGenerator;
            case 'local':
            default:
                return $this->localGenerator;
        }
    }

    /**
     * Test local generator
     */
    private function testLocalGenerator()
    {
        try {
            $testText = "This is a test sentence for the local generator.";
            $questions = $this->localGenerator->generateQuestions($testText, 1, 'medium', ['multiple_choice']);
            
            if (!empty($questions)) {
                return ['success' => true, 'message' => 'Local generator working correctly'];
            } else {
                return ['success' => false, 'message' => 'Local generator returned no questions'];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Local generator error: ' . $e->getMessage()];
        }
    }

    /**
     * Calculate statistics for generated questions
     */
    private function calculateStats($questions)
    {
        if (empty($questions)) {
            return [
                'total_questions' => 0,
                'total_answers' => 0,
                'correct_answers' => 0,
                'question_types' => []
            ];
        }

        $totalQuestions = count($questions);
        $totalAnswers = 0;
        $correctAnswers = 0;
        $questionTypes = [];

        foreach ($questions as $question) {
            $answerCount = count($question['answers']);
            $totalAnswers += $answerCount;

            // Count correct answers
            foreach ($question['answers'] as $answer) {
                if ($answer['is_correct']) {
                    $correctAnswers++;
                }
            }

            // Count question types
            $type = $question['type'];
            $questionTypes[$type] = ($questionTypes[$type] ?? 0) + 1;
        }

        return [
            'total_questions' => $totalQuestions,
            'total_answers' => $totalAnswers,
            'correct_answers' => $correctAnswers,
            'question_types' => $questionTypes,
            'avg_answers_per_question' => round($totalAnswers / $totalQuestions, 2),
            'correct_answer_ratio' => $totalQuestions > 0 ? round($correctAnswers / $totalQuestions, 2) : 0
        ];
    }

    /**
     * Get available generators with their status
     */
    public function getAvailableGenerators()
    {
        return response()->json([
            'generators' => [
                'local' => [
                    'name' => 'Local Generator',
                    'description' => 'Built-in question generator with pattern-based analysis',
                    'features' => [
                        'Fast generation',
                        'No API costs',
                        'Offline capability',
                        'Pattern-based analysis'
                    ],
                    'status' => $this->testLocalGenerator()
                ],
                'groq' => [
                    'name' => 'Groq AI Generator',
                    'description' => 'AI-powered question generator using Groq\'s language models',
                    'features' => [
                        'Advanced AI analysis',
                        'Natural language understanding',
                        'Context-aware questions',
                        'High-quality output'
                    ],
                    'status' => $this->groqGenerator->testConnection()
                ]
            ]
        ]);
    }
}
