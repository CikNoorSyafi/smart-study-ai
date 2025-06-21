<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqQuestionGenerator
{
    private $apiKey;
    private $baseUrl;
    private $model;

    public function __construct()
    {
        $this->apiKey = env('GROQ_API_KEY');
        $this->baseUrl = 'https://api.groq.com/openai/v1/chat/completions';
        $this->model = env('GROQ_MODEL', 'llama3-8b-8192'); // Default model
    }

    /**
     * Generate questions from text content using Groq AI
     */
    public function generateQuestions($text, $count = 10, $difficulty = 'medium', $types = ['multiple_choice'])
    {
        if (empty($this->apiKey)) {
            throw new \Exception('Groq API key not configured. Please set GROQ_API_KEY in your .env file.');
        }

        try {
            // Prepare the prompt for Groq
            $prompt = $this->buildPrompt($text, $count, $difficulty, $types);
            
            // Make API call to Groq
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(60)->post($this->baseUrl, [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert educational content creator. Generate high-quality questions and answers based on the provided text. Always respond with valid JSON format.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 4000,
                'top_p' => 0.9
            ]);

            if (!$response->successful()) {
                throw new \Exception('Groq API request failed: ' . $response->body());
            }

            $responseData = $response->json();
            
            if (!isset($responseData['choices'][0]['message']['content'])) {
                throw new \Exception('Invalid response format from Groq API');
            }

            $content = $responseData['choices'][0]['message']['content'];
            
            // Parse the JSON response
            $questions = $this->parseGroqResponse($content);
            
            // Validate and format questions
            return $this->validateAndFormatQuestions($questions, $count);

        } catch (\Exception $e) {
            Log::error('Groq Question Generation Error: ' . $e->getMessage());
            
            // Fallback to basic questions if Groq fails
            return $this->generateFallbackQuestions($text, $count, $difficulty, $types);
        }
    }

    /**
     * Build prompt for Groq API
     */
    private function buildPrompt($text, $count, $difficulty, $types)
    {
        $typesString = implode(', ', $types);
        
        $prompt = "Based on the following text, generate exactly {$count} educational questions with the specified difficulty level '{$difficulty}' and types: {$typesString}.

TEXT:
{$text}

REQUIREMENTS:
1. Generate exactly {$count} questions
2. Each question must have exactly 1 correct answer
3. For multiple_choice: provide 4 options (1 correct + 3 wrong)
4. For true_false: provide 2 options (True/False with 1 correct)
5. For short_answer: provide 1 correct answer
6. For essay: provide 1 sample answer
7. Questions should be specific and clear, not generic
8. Extract key concepts from the text for questions

RESPONSE FORMAT (JSON):
{
  \"questions\": [
    {
      \"question\": \"What is [specific concept from text]?\",
      \"type\": \"multiple_choice\",
      \"answers\": [
        {\"text\": \"Correct answer from text\", \"is_correct\": true},
        {\"text\": \"Wrong option 1\", \"is_correct\": false},
        {\"text\": \"Wrong option 2\", \"is_correct\": false},
        {\"text\": \"Wrong option 3\", \"is_correct\": false}
      ]
    }
  ]
}

Generate the questions now:";

        return $prompt;
    }

    /**
     * Parse Groq API response
     */
    private function parseGroqResponse($content)
    {
        // Clean up the response (remove markdown formatting if present)
        $content = preg_replace('/```json\s*/', '', $content);
        $content = preg_replace('/```\s*$/', '', $content);
        $content = trim($content);

        // Try to decode JSON
        $decoded = json_decode($content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Try to extract JSON from the response
            if (preg_match('/\{.*\}/s', $content, $matches)) {
                $decoded = json_decode($matches[0], true);
            }
        }

        if (json_last_error() !== JSON_ERROR_NONE || !isset($decoded['questions'])) {
            throw new \Exception('Failed to parse Groq response as JSON: ' . json_last_error_msg());
        }

        return $decoded['questions'];
    }

    /**
     * Validate and format questions to match expected structure
     */
    private function validateAndFormatQuestions($questions, $requestedCount)
    {
        $validQuestions = [];
        
        foreach ($questions as $question) {
            // Validate required fields
            if (!isset($question['question']) || !isset($question['type']) || !isset($question['answers'])) {
                continue;
            }

            // Ensure answers is an array
            if (!is_array($question['answers'])) {
                continue;
            }

            // Validate that there's at least one correct answer
            $hasCorrectAnswer = false;
            foreach ($question['answers'] as $answer) {
                if (isset($answer['is_correct']) && $answer['is_correct']) {
                    $hasCorrectAnswer = true;
                    break;
                }
            }

            if (!$hasCorrectAnswer) {
                continue;
            }

            $validQuestions[] = [
                'question' => trim($question['question']),
                'type' => $question['type'],
                'answers' => $question['answers']
            ];

            // Stop if we have enough questions
            if (count($validQuestions) >= $requestedCount) {
                break;
            }
        }

        return $validQuestions;
    }

    /**
     * Generate fallback questions if Groq fails
     */
    private function generateFallbackQuestions($text, $count, $difficulty, $types)
    {
        $sentences = $this->extractSentences($text);
        $questions = [];

        for ($i = 0; $i < min($count, count($sentences)); $i++) {
            $sentence = $sentences[$i];
            $type = $types[array_rand($types)];

            $question = $this->createFallbackQuestion($sentence, $type, $difficulty);
            if ($question) {
                $questions[] = $question;
            }
        }

        return $questions;
    }

    /**
     * Create fallback question
     */
    private function createFallbackQuestion($sentence, $type, $difficulty)
    {
        switch ($type) {
            case 'multiple_choice':
                return [
                    'question' => "What information is provided in the text?",
                    'type' => 'multiple_choice',
                    'answers' => [
                        ['text' => trim($sentence), 'is_correct' => true],
                        ['text' => 'This information is not mentioned', 'is_correct' => false],
                        ['text' => 'The opposite of what is stated', 'is_correct' => false],
                        ['text' => 'A different interpretation', 'is_correct' => false]
                    ]
                ];

            case 'true_false':
                return [
                    'question' => "True or False: " . trim($sentence),
                    'type' => 'true_false',
                    'answers' => [
                        ['text' => 'True', 'is_correct' => true],
                        ['text' => 'False', 'is_correct' => false]
                    ]
                ];

            case 'short_answer':
                return [
                    'question' => "What does the text state?",
                    'type' => 'short_answer',
                    'answers' => [
                        ['text' => trim($sentence), 'is_correct' => true]
                    ]
                ];

            case 'essay':
                return [
                    'question' => "Discuss the following information: \"" . trim($sentence) . "\"",
                    'type' => 'essay',
                    'answers' => [
                        ['text' => 'This requires a detailed explanation based on the provided information.', 'is_correct' => true]
                    ]
                ];

            default:
                return null;
        }
    }

    /**
     * Extract sentences from text
     */
    private function extractSentences($text)
    {
        return preg_split('/(?<=[.!?])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * Test Groq API connection
     */
    public function testConnection()
    {
        if (empty($this->apiKey)) {
            return ['success' => false, 'message' => 'Groq API key not configured'];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(10)->post($this->baseUrl, [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => 'Hello, this is a test message.'
                    ]
                ],
                'max_tokens' => 10
            ]);

            if ($response->successful()) {
                return ['success' => true, 'message' => 'Groq API connection successful'];
            } else {
                return ['success' => false, 'message' => 'Groq API connection failed: ' . $response->body()];
            }

        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Connection error: ' . $e->getMessage()];
        }
    }
}
