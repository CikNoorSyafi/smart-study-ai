<?php

namespace App\Services;

use App\Models\Question;
use App\Models\Answer;
use App\Models\Note;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class SimpleQuestionGenerator
{
    /**
     * Generate intelligent questions from note content
     */
    public function generateFromNote(Note $note, $count = 5, $difficulty = 'medium')
    {
        $content = $note->content;
        $title = $note->title;
        
        // Create questions based on content analysis
        $questions = $this->createQuestionsFromContent($content, $title, $count, $difficulty);
        
        // Save questions to database
        $savedQuestions = [];
        foreach ($questions as $questionData) {
            $question = $this->saveQuestion($note, $questionData, $difficulty);
            if ($question) {
                $savedQuestions[] = $question;
            }
        }
        
        return $savedQuestions;
    }

    /**
     * Create questions from content
     */
    private function createQuestionsFromContent($content, $title, $count, $difficulty)
    {
        $questions = [];
        
        // 1. Create a question about the main topic (from title)
        if ($title && strlen($title) > 5) {
            // Extract key information from content for a comprehensive answer
            $keyInfo = $this->extractKeyInformation($content);
            $comprehensiveAnswer = $this->createComprehensiveAnswer($title, $keyInfo);

            $questions[] = [
                'question_text' => "What is {$title}?",
                'question_type' => 'essay',
                'explanation' => "This question tests understanding of the main topic: {$title}",
                'answers' => [
                    ['text' => $comprehensiveAnswer, 'is_correct' => true]
                ]
            ];
        }

        // 2. Extract key sentences and create questions
        $sentences = $this->extractKeySentences($content);
        foreach ($sentences as $sentence) {
            if (count($questions) >= $count) break;
            
            $question = $this->createQuestionFromSentence($sentence, $difficulty);
            if ($question) {
                $questions[] = $question;
            }
        }

        // 3. Create definition questions
        $definitions = $this->extractDefinitions($content);
        foreach ($definitions as $def) {
            if (count($questions) >= $count) break;
            
            $questions[] = [
                'question_text' => "What is {$def['term']}?",
                'question_type' => 'short_answer',
                'explanation' => "Definition question about {$def['term']}",
                'answers' => [
                    ['text' => $def['definition'], 'is_correct' => true]
                ]
            ];
        }

        // 4. Create comprehension questions
        if (count($questions) < $count) {
            $questions[] = [
                'question_text' => "Summarize the main points discussed in this content.",
                'question_type' => 'essay',
                'explanation' => "This question tests overall comprehension of the content.",
                'answers' => [
                    ['text' => "A comprehensive summary of the main points", 'is_correct' => true]
                ]
            ];
        }

        if (count($questions) < $count) {
            $questions[] = [
                'question_text' => "How can this information be applied in real life?",
                'question_type' => 'essay',
                'explanation' => "This question tests practical application of the knowledge.",
                'answers' => [
                    ['text' => "Practical applications and examples", 'is_correct' => true]
                ]
            ];
        }

        return array_slice($questions, 0, $count);
    }

    /**
     * Extract key sentences from content
     */
    private function extractKeySentences($content)
    {
        $sentences = preg_split('/[.!?]+/', $content);
        $keySentences = [];
        
        foreach ($sentences as $sentence) {
            $sentence = trim($sentence);
            if (strlen($sentence) > 20 && strlen($sentence) < 200) {
                // Look for sentences with important keywords
                if (preg_match('/\b(?:is|are|was|were|process|method|important|essential|because|during|when|where|how)\b/i', $sentence)) {
                    $keySentences[] = $sentence;
                }
            }
        }
        
        return array_slice($keySentences, 0, 5);
    }

    /**
     * Create a question from a sentence
     */
    private function createQuestionFromSentence($sentence, $difficulty)
    {
        // Try to identify what the sentence is about and create a question
        
        // Pattern: "X is Y" -> "What is X?"
        if (preg_match('/^([^,]+?)\s+(?:is|are)\s+(.+)$/i', $sentence, $matches)) {
            $subject = trim($matches[1]);
            $predicate = trim($matches[2]);

            if (strlen($subject) > 3 && strlen($subject) < 50) {
                // Create a more comprehensive answer
                $comprehensiveAnswer = $subject . " is " . $predicate . ". This is an important concept that helps in understanding the subject matter.";

                return [
                    'question_text' => "What is {$subject}?",
                    'question_type' => 'short_answer',
                    'explanation' => "This question tests understanding of a key definition from the content.",
                    'answers' => [
                        ['text' => $comprehensiveAnswer, 'is_correct' => true]
                    ]
                ];
            }
        }

        // Pattern: "During X, Y happens" -> "What happens during X?"
        if (preg_match('/^During\s+([^,]+),\s*(.+)$/i', $sentence, $matches)) {
            $process = trim($matches[1]);
            $action = trim($matches[2]);

            // Create a more detailed answer
            $detailedAnswer = "During " . $process . ", " . $action . ". This process is important for understanding how the system works and what changes occur during this phase.";

            return [
                'question_text' => "What happens during {$process}?",
                'question_type' => 'short_answer',
                'explanation' => "This question tests understanding of a specific process described in the content.",
                'answers' => [
                    ['text' => $detailedAnswer, 'is_correct' => true]
                ]
            ];
        }

        // Pattern: "X because Y" -> "Why does X happen?"
        if (preg_match('/^(.+?)\s+because\s+(.+)$/i', $sentence, $matches)) {
            $effect = trim($matches[1]);
            $cause = trim($matches[2]);

            // Create a comprehensive explanation
            $comprehensiveAnswer = $effect . " because " . $cause . ". This cause-and-effect relationship is fundamental to understanding the underlying principles and mechanisms involved.";

            return [
                'question_text' => "Why {$effect}?",
                'question_type' => 'short_answer',
                'explanation' => "This question tests understanding of cause-and-effect relationships in the content.",
                'answers' => [
                    ['text' => $comprehensiveAnswer, 'is_correct' => true]
                ]
            ];
        }

        // General question for important sentences
        return [
            'question_text' => "Explain the significance of: \"{$sentence}\"",
            'question_type' => 'essay',
            'explanation' => "This question tests understanding of an important concept from the content.",
            'answers' => [
                ['text' => "Explanation of the significance based on the content", 'is_correct' => true]
            ]
        ];
    }

    /**
     * Extract definitions from content
     */
    private function extractDefinitions($content)
    {
        $definitions = [];
        
        // Pattern: "Term is definition"
        preg_match_all('/([A-Z][a-z]+(?:\s+[a-z]+)*)\s+is\s+([^.!?]+)/i', $content, $matches, PREG_SET_ORDER);
        
        foreach ($matches as $match) {
            $term = trim($match[1]);
            $definition = trim($match[2]);
            
            if (strlen($term) > 2 && strlen($definition) > 10 && strlen($term) < 30) {
                $definitions[] = [
                    'term' => $term,
                    'definition' => $definition
                ];
            }
        }
        
        return array_slice($definitions, 0, 3);
    }

    /**
     * Extract key information from content
     */
    private function extractKeyInformation($content)
    {
        $keyInfo = [];

        // Extract definitions
        preg_match_all('/([A-Z][a-z]+(?:\s+[a-z]+)*)\s+is\s+([^.!?]+)/i', $content, $definitions);
        for ($i = 0; $i < count($definitions[1]); $i++) {
            $keyInfo['definitions'][] = [
                'term' => $definitions[1][$i],
                'definition' => $definitions[2][$i]
            ];
        }

        // Extract processes (sentences with "during", "when", "process")
        preg_match_all('/[^.!?]*(?:during|when|process)[^.!?]*[.!?]/i', $content, $processes);
        $keyInfo['processes'] = array_slice($processes[0], 0, 3);

        // Extract important facts (sentences with numbers, dates, or "because")
        preg_match_all('/[^.!?]*(?:\d+|because|essential|important)[^.!?]*[.!?]/i', $content, $facts);
        $keyInfo['facts'] = array_slice($facts[0], 0, 3);

        // Extract key concepts (capitalized terms)
        preg_match_all('/\b[A-Z][a-z]+(?:\s+[A-Z][a-z]+)*\b/', $content, $concepts);
        $keyInfo['concepts'] = array_unique(array_slice($concepts[0], 0, 5));

        return $keyInfo;
    }

    /**
     * Create comprehensive answer based on content
     */
    private function createComprehensiveAnswer($topic, $keyInfo)
    {
        $answer = "";

        // Start with main definition if available
        if (!empty($keyInfo['definitions'])) {
            foreach ($keyInfo['definitions'] as $def) {
                if (stripos($def['term'], $topic) !== false || stripos($topic, $def['term']) !== false) {
                    $answer .= $def['term'] . " is " . $def['definition'] . ". ";
                    break;
                }
            }
        }

        // Add process information
        if (!empty($keyInfo['processes'])) {
            $answer .= "The process involves: " . trim($keyInfo['processes'][0]) . " ";
        }

        // Add important facts
        if (!empty($keyInfo['facts'])) {
            $answer .= "Key facts include: " . trim($keyInfo['facts'][0]) . " ";
        }

        // Add key concepts
        if (!empty($keyInfo['concepts'])) {
            $concepts = array_slice($keyInfo['concepts'], 0, 3);
            $answer .= "Important concepts related to this topic include " . implode(", ", $concepts) . ".";
        }

        // Fallback if no specific information found
        if (empty($answer)) {
            $answer = "This topic covers important concepts and information that are essential for understanding the subject matter. It includes key definitions, processes, and facts that students need to learn.";
        }

        return trim($answer);
    }

    /**
     * Save question to database
     */
    private function saveQuestion($note, $questionData, $difficulty)
    {
        try {
            $question = Question::create([
                'question_id' => (string) Str::uuid(),
                'note_id' => $note->note_id,
                'user_id' => $note->user_id,
                'question_text' => $questionData['question_text'],
                'question_type' => $questionData['question_type'],
                'difficulty' => $difficulty,
                'generated_by' => 'AI',
                'explanation' => $questionData['explanation'] ?? null,
                'status' => 'pending',
            ]);

            // Save answers
            foreach ($questionData['answers'] as $answerData) {
                Answer::create([
                    'answer_id' => (string) Str::uuid(),
                    'question_id' => $question->question_id,
                    'answer_text' => $answerData['text'],
                    'is_correct' => $answerData['is_correct'],
                    'explanation' => $answerData['explanation'] ?? null,
                ]);
            }

            return $question;
        } catch (\Exception $e) {
            Log::error('Failed to save generated question: ' . $e->getMessage());
            return null;
        }
    }
}
