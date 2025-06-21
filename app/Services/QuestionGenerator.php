<?php

namespace App\Services;

use Exception;

class QuestionGenerator
{
    private $questionTemplates;
    private $difficultySettings;
    private $stopWords;
    private $questionPatterns;
    private $conceptKeywords;

    public function __construct()
    {
        $this->initializeTemplates();
        $this->initializeDifficultySettings();
        $this->initializeStopWords();
        $this->initializeQuestionPatterns();
        $this->initializeConceptKeywords();
    }

    /**
     * Generate questions from text content with simplified 1:1 format (1 question = 1 answer)
     */
    public function generateQuestions($text, $count = 10, $difficulty = 'medium', $types = ['multiple_choice'])
    {
        $sentences = $this->extractSentences($text);
        $questions = [];
        $usedSentences = [];

        for ($i = 0; $i < $count && $i < count($sentences); $i++) {
            // Get a sentence that hasn't been used
            $availableSentences = array_diff($sentences, $usedSentences);
            if (empty($availableSentences)) break;

            $sentence = $availableSentences[array_rand($availableSentences)];
            $usedSentences[] = $sentence;

            // Pick question type
            $questionType = $types[array_rand($types)];

            // Create simple 1:1 question
            $question = $this->createSimple1to1Question($sentence, $questionType, $difficulty);

            if ($question) {
                $questions[] = $question;
            }
        }

        return $questions;
    }

    /**
     * Create simple 1:1 question (1 question = 1 answer only)
     */
    private function createSimple1to1Question($sentence, $type, $difficulty)
    {
        switch ($type) {
            case 'multiple_choice':
                return $this->createSimple1to1MultipleChoice($sentence, $difficulty);
            case 'true_false':
                return $this->createSimple1to1TrueFalse($sentence, $difficulty);
            case 'short_answer':
                return $this->createSimple1to1ShortAnswer($sentence, $difficulty);
            case 'essay':
                return $this->createSimple1to1Essay($sentence, $difficulty);
            default:
                return $this->createSimple1to1ShortAnswer($sentence, $difficulty);
        }
    }

    /**
     * Create multiple choice with 4 options (1 correct + 3 wrong)
     */
    private function createSimple1to1MultipleChoice($sentence, $difficulty)
    {
        $correctAnswer = trim($sentence);

        // Create specific question based on sentence content
        $question = $this->createSpecificQuestion($sentence, $difficulty);

        // Generate 3 wrong answers
        $wrongAnswers = [
            "This information is not mentioned in the text",
            "The opposite of what is stated in the passage",
            "A different interpretation of the content"
        ];

        // Combine all answers
        $allAnswers = [
            ['text' => $correctAnswer, 'is_correct' => true],
            ['text' => $wrongAnswers[0], 'is_correct' => false],
            ['text' => $wrongAnswers[1], 'is_correct' => false],
            ['text' => $wrongAnswers[2], 'is_correct' => false]
        ];

        // Shuffle the answers
        shuffle($allAnswers);

        return [
            'question' => $question,
            'type' => 'multiple_choice',
            'answers' => $allAnswers
        ];
    }

    /**
     * Create true/false with 2 options (1 correct)
     */
    private function createSimple1to1TrueFalse($sentence, $difficulty)
    {
        $isTrue = rand(0, 1) === 1;

        if ($isTrue) {
            $statement = trim($sentence);
            $correctAnswer = 'True';
        } else {
            $statement = $this->modifySentenceToMakeFalse(trim($sentence));
            $correctAnswer = 'False';
        }

        return [
            'question' => "True or False: " . $statement,
            'type' => 'true_false',
            'answers' => [
                ['text' => 'True', 'is_correct' => $correctAnswer === 'True'],
                ['text' => 'False', 'is_correct' => $correctAnswer === 'False']
            ]
        ];
    }

    /**
     * Create specific question based on sentence content
     */
    private function createSpecificQuestion($sentence, $difficulty)
    {
        $sentence = trim($sentence);

        // Try to identify key information in the sentence
        $keyWords = $this->extractKeyWordsFromSentence($sentence);

        // Pattern-based question generation
        if (preg_match('/(.+?)\s+is\s+(.+?)\.?$/i', $sentence, $matches)) {
            $subject = trim($matches[1]);
            return "What is {$subject}?";
        }

        if (preg_match('/(.+?)\s+are\s+(.+?)\.?$/i', $sentence, $matches)) {
            $subject = trim($matches[1]);
            return "What are {$subject}?";
        }

        if (preg_match('/(.+?)\s+can\s+(.+?)\.?$/i', $sentence, $matches)) {
            $subject = trim($matches[1]);
            return "What can {$subject} do?";
        }

        if (preg_match('/(.+?)\s+will\s+(.+?)\.?$/i', $sentence, $matches)) {
            $subject = trim($matches[1]);
            return "What will {$subject} do?";
        }

        if (preg_match('/(.+?)\s+has\s+(.+?)\.?$/i', $sentence, $matches)) {
            $subject = trim($matches[1]);
            return "What does {$subject} have?";
        }

        if (preg_match('/(.+?)\s+uses?\s+(.+?)\.?$/i', $sentence, $matches)) {
            $subject = trim($matches[1]);
            return "What does {$subject} use?";
        }

        if (preg_match('/(.+?)\s+contains?\s+(.+?)\.?$/i', $sentence, $matches)) {
            $subject = trim($matches[1]);
            return "What does {$subject} contain?";
        }

        // If no pattern matches, create question based on key words
        if (!empty($keyWords)) {
            $mainKeyword = $keyWords[0];

            $templates = [
                'easy' => "What does the text say about {$mainKeyword}?",
                'medium' => "According to the passage, what is mentioned about {$mainKeyword}?",
                'hard' => "Based on the information provided, what can be concluded about {$mainKeyword}?"
            ];

            return $templates[$difficulty];
        }

        // Fallback to generic but improved questions
        $fallbackTemplates = [
            'easy' => "What information is provided in this statement?",
            'medium' => "Which of the following statements is correct?",
            'hard' => "What is the main point being conveyed?"
        ];

        return $fallbackTemplates[$difficulty];
    }

    /**
     * Extract key words from sentence for question generation
     */
    private function extractKeyWordsFromSentence($sentence)
    {
        // First try to extract compound terms (like "machine learning", "artificial intelligence")
        $compoundTerms = $this->extractCompoundTerms($sentence);
        if (!empty($compoundTerms)) {
            return $compoundTerms;
        }

        // Remove common words and extract meaningful terms
        $words = str_word_count(strtolower($sentence), 1);
        $stopWords = ['the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'is', 'are', 'was', 'were', 'be', 'been', 'being', 'have', 'has', 'had', 'do', 'does', 'did', 'will', 'would', 'could', 'should', 'may', 'might', 'can', 'this', 'that', 'these', 'those', 'from', 'they', 'them', 'their', 'there', 'then', 'than', 'when', 'where', 'how', 'what', 'who', 'why', 'which'];

        $keyWords = array_filter($words, function($word) use ($stopWords) {
            return strlen($word) > 4 && !in_array($word, $stopWords);
        });

        return array_values($keyWords);
    }

    /**
     * Extract compound terms (multi-word concepts) from sentence
     */
    private function extractCompoundTerms($sentence)
    {
        $compoundTerms = [];

        // Common compound terms in educational content
        $patterns = [
            '/machine learning/i',
            '/artificial intelligence/i',
            '/deep learning/i',
            '/neural networks?/i',
            '/data analysis/i',
            '/computer science/i',
            '/natural language/i',
            '/scientific method/i',
            '/climate change/i',
            '/renewable energy/i',
            '/solar system/i',
            '/human body/i',
            '/cell division/i',
            '/chemical reaction/i',
            '/periodic table/i',
            '/world war/i',
            '/industrial revolution/i',
            '/economic system/i',
            '/social media/i',
            '/global warming/i'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $sentence, $matches)) {
                $compoundTerms[] = $matches[0];
            }
        }

        return $compoundTerms;
    }

    /**
     * Create simple short answer with 1 answer
     */
    private function createSimple1to1ShortAnswer($sentence, $difficulty)
    {
        $sentence = trim($sentence);
        $keyWords = $this->extractKeyWordsFromSentence($sentence);

        // Create specific question based on content
        if (!empty($keyWords)) {
            $mainKeyword = $keyWords[0];

            $templates = [
                'easy' => "What does the text say about {$mainKeyword}?",
                'medium' => "Explain what is mentioned about {$mainKeyword}.",
                'hard' => "Analyze the information provided about {$mainKeyword}."
            ];

            $question = $templates[$difficulty];
        } else {
            // Fallback templates
            $templates = [
                'easy' => "What information is provided in this statement?",
                'medium' => "Explain what the passage states.",
                'hard' => "Analyze the key information mentioned."
            ];

            $question = $templates[$difficulty];
        }

        return [
            'question' => $question,
            'type' => 'short_answer',
            'answers' => [
                ['text' => $sentence, 'is_correct' => true]
            ]
        ];
    }

    /**
     * Create simple essay with 1 answer
     */
    private function createSimple1to1Essay($sentence, $difficulty)
    {
        $templates = [
            'easy' => "Discuss the following information: ",
            'medium' => "Explain and elaborate on this statement: ",
            'hard' => "Critically analyze the following: "
        ];

        return [
            'question' => $templates[$difficulty] . "\"" . trim($sentence) . "\"",
            'type' => 'essay',
            'answers' => [
                ['text' => 'Provide a detailed explanation based on the given information.', 'is_correct' => true]
            ]
        ];
    }

    /**
     * Generate a question from a text chunk
     */
    private function generateQuestionFromChunk($chunk, $type, $difficulty)
    {
        switch ($type) {
            case 'multiple_choice':
                return $this->generateMultipleChoice($chunk, $difficulty);
            case 'true_false':
                return $this->generateTrueFalse($chunk, $difficulty);
            case 'short_answer':
                return $this->generateShortAnswer($chunk, $difficulty);
            case 'essay':
                return $this->generateEssay($chunk, $difficulty);
            default:
                return $this->generateMultipleChoice($chunk, $difficulty);
        }
    }

    /**
     * Generate multiple choice question
     */
    private function generateMultipleChoice($chunk, $difficulty)
    {
        $sentences = $this->extractSentences($chunk);
        if (empty($sentences)) {
            return null;
        }

        $keyFacts = $this->extractKeyFacts($sentences);
        if (empty($keyFacts)) {
            return null;
        }

        $fact = $keyFacts[array_rand($keyFacts)];
        $question = $this->createQuestionFromFact($fact, $difficulty);
        
        if (!$question) {
            return null;
        }

        $correctAnswer = $this->extractAnswerFromFact($fact);
        $wrongAnswers = $this->generateWrongAnswers($correctAnswer, $chunk, 3);

        $answers = array_merge(
            [['text' => $correctAnswer, 'is_correct' => true]],
            array_map(function($answer) {
                return ['text' => $answer, 'is_correct' => false];
            }, $wrongAnswers)
        );

        shuffle($answers);

        return [
            'question' => $question,
            'type' => 'multiple_choice',
            'answers' => $answers
        ];
    }

    /**
     * Generate true/false question
     */
    private function generateTrueFalse($chunk, $difficulty)
    {
        $sentences = $this->extractSentences($chunk);
        if (empty($sentences)) {
            return null;
        }

        $sentence = $sentences[array_rand($sentences)];
        
        // Randomly decide if this should be true or false
        $isTrue = rand(0, 1) === 1;
        
        if ($isTrue) {
            $question = "True or False: " . $sentence;
            $correctAnswer = "True";
        } else {
            // Modify the sentence to make it false
            $modifiedSentence = $this->modifySentenceToMakeFalse($sentence);
            $question = "True or False: " . $modifiedSentence;
            $correctAnswer = "False";
        }

        return [
            'question' => $question,
            'type' => 'true_false',
            'answers' => [
                ['text' => 'True', 'is_correct' => $correctAnswer === 'True'],
                ['text' => 'False', 'is_correct' => $correctAnswer === 'False']
            ]
        ];
    }

    /**
     * Generate short answer question
     */
    private function generateShortAnswer($chunk, $difficulty)
    {
        $keyTerms = $this->extractKeyTerms($chunk);
        if (empty($keyTerms)) {
            return null;
        }

        $term = $keyTerms[array_rand($keyTerms)];
        $templates = $this->questionTemplates['short_answer'][$difficulty];
        $template = $templates[array_rand($templates)];
        
        $question = str_replace('{term}', $term, $template);
        
        return [
            'question' => $question,
            'type' => 'short_answer',
            'answers' => [
                ['text' => $this->generateShortAnswerResponse($term, $chunk), 'is_correct' => true]
            ]
        ];
    }

    /**
     * Generate essay question
     */
    private function generateEssay($chunk, $difficulty)
    {
        $mainTopics = $this->extractMainTopics($chunk);
        if (empty($mainTopics)) {
            return null;
        }

        $topic = $mainTopics[array_rand($mainTopics)];
        $templates = $this->questionTemplates['essay'][$difficulty];
        $template = $templates[array_rand($templates)];
        
        $question = str_replace('{topic}', $topic, $template);
        
        return [
            'question' => $question,
            'type' => 'essay',
            'answers' => [
                ['text' => 'This is an essay question that requires a detailed written response.', 'is_correct' => true]
            ]
        ];
    }

    /**
     * Initialize question templates
     */
    private function initializeTemplates()
    {
        $this->questionTemplates = [
            'multiple_choice' => [
                'easy' => [
                    'What is {term}?',
                    'Which of the following describes {term}?',
                    'What does {term} mean?'
                ],
                'medium' => [
                    'How does {term} relate to {concept}?',
                    'What is the significance of {term}?',
                    'Which statement best explains {term}?'
                ],
                'hard' => [
                    'Analyze the relationship between {term} and {concept}.',
                    'What are the implications of {term}?',
                    'How would you evaluate {term} in the context of {concept}?'
                ]
            ],
            'short_answer' => [
                'easy' => [
                    'Define {term}.',
                    'What is {term}?',
                    'Explain {term} in your own words.'
                ],
                'medium' => [
                    'Describe the importance of {term}.',
                    'How does {term} work?',
                    'What are the key features of {term}?'
                ],
                'hard' => [
                    'Analyze the impact of {term}.',
                    'Compare and contrast {term} with similar concepts.',
                    'Evaluate the effectiveness of {term}.'
                ]
            ],
            'essay' => [
                'easy' => [
                    'Discuss {topic}.',
                    'Write about {topic}.',
                    'Explain the concept of {topic}.'
                ],
                'medium' => [
                    'Analyze {topic} and its implications.',
                    'Compare different aspects of {topic}.',
                    'Discuss the advantages and disadvantages of {topic}.'
                ],
                'hard' => [
                    'Critically evaluate {topic}.',
                    'Synthesize information about {topic} from multiple perspectives.',
                    'Develop an argument about {topic} using evidence.'
                ]
            ]
        ];
    }

    /**
     * Initialize difficulty settings
     */
    private function initializeDifficultySettings()
    {
        $this->difficultySettings = [
            'easy' => ['complexity' => 1, 'vocabulary_level' => 'basic'],
            'medium' => ['complexity' => 2, 'vocabulary_level' => 'intermediate'],
            'hard' => ['complexity' => 3, 'vocabulary_level' => 'advanced']
        ];
    }

    /**
     * Split text into processable chunks
     */
    private function splitTextIntoChunks($text, $targetQuestions)
    {
        $sentences = preg_split('/(?<=[.!?])\s+/', $text);
        $chunkSize = max(3, intval(count($sentences) / $targetQuestions));
        
        $chunks = array_chunk($sentences, $chunkSize);
        return array_map(function($chunk) {
            return implode(' ', $chunk);
        }, $chunks);
    }

    /**
     * Extract sentences from text
     */
    private function extractSentences($text)
    {
        return preg_split('/(?<=[.!?])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * Extract key facts from sentences
     */
    private function extractKeyFacts($sentences)
    {
        $facts = [];
        foreach ($sentences as $sentence) {
            if (strlen($sentence) > 20 && strlen($sentence) < 200) {
                $facts[] = trim($sentence);
            }
        }
        return $facts;
    }

    /**
     * Create question from fact
     */
    private function createQuestionFromFact($fact, $difficulty)
    {
        // Simple question generation based on sentence structure
        if (preg_match('/(.+)\s+is\s+(.+)\.?$/i', $fact, $matches)) {
            return "What is " . trim($matches[1]) . "?";
        }
        
        if (preg_match('/(.+)\s+are\s+(.+)\.?$/i', $fact, $matches)) {
            return "What are " . trim($matches[1]) . "?";
        }
        
        // Default question format
        return "According to the text, which statement is correct?";
    }

    /**
     * Extract answer from fact
     */
    private function extractAnswerFromFact($fact)
    {
        if (preg_match('/(.+)\s+is\s+(.+)\.?$/i', $fact, $matches)) {
            return trim($matches[2]);
        }
        
        return trim($fact);
    }

    /**
     * Generate wrong answers
     */
    private function generateWrongAnswers($correctAnswer, $context, $count = 3)
    {
        $wrongAnswers = [];
        $words = explode(' ', $context);
        $uniqueWords = array_unique($words);
        
        // Generate plausible wrong answers
        for ($i = 0; $i < $count; $i++) {
            $wrongAnswer = $this->generatePlausibleWrongAnswer($correctAnswer, $uniqueWords);
            if ($wrongAnswer && !in_array($wrongAnswer, $wrongAnswers) && $wrongAnswer !== $correctAnswer) {
                $wrongAnswers[] = $wrongAnswer;
            }
        }
        
        // Fill remaining slots with generic wrong answers if needed
        while (count($wrongAnswers) < $count) {
            $generic = $this->generateGenericWrongAnswer($correctAnswer);
            if (!in_array($generic, $wrongAnswers)) {
                $wrongAnswers[] = $generic;
            }
        }
        
        return array_slice($wrongAnswers, 0, $count);
    }

    /**
     * Generate plausible wrong answer
     */
    private function generatePlausibleWrongAnswer($correctAnswer, $contextWords)
    {
        // Simple strategy: pick random words from context
        $randomWords = array_rand(array_flip($contextWords), min(3, count($contextWords)));
        if (is_array($randomWords)) {
            return implode(' ', $randomWords);
        }
        return $randomWords;
    }

    /**
     * Generate generic wrong answer
     */
    private function generateGenericWrongAnswer($correctAnswer)
    {
        $genericAnswers = [
            'Not mentioned in the text',
            'Insufficient information',
            'Cannot be determined',
            'None of the above'
        ];
        
        return $genericAnswers[array_rand($genericAnswers)];
    }

    /**
     * Check if question is duplicate
     */
    private function isDuplicateQuestion($newQuestion, $existingQuestions)
    {
        foreach ($existingQuestions as $existing) {
            $similarity = 0;
            similar_text($newQuestion['question'], $existing['question'], $similarity);
            if ($similarity > 80) { // 80% similarity threshold
                return true;
            }
        }
        return false;
    }

    /**
     * Extract key terms from text
     */
    private function extractKeyTerms($text)
    {
        // Simple keyword extraction
        $words = str_word_count($text, 1);
        $words = array_filter($words, function($word) {
            return strlen($word) > 4;
        });
        
        return array_unique($words);
    }

    /**
     * Extract main topics from text
     */
    private function extractMainTopics($text)
    {
        // Simple topic extraction based on repeated terms
        $words = str_word_count(strtolower($text), 1);
        $wordCounts = array_count_values($words);
        arsort($wordCounts);
        
        return array_slice(array_keys($wordCounts), 0, 5);
    }

    /**
     * Modify sentence to make it false
     */
    private function modifySentenceToMakeFalse($sentence)
    {
        // Simple modifications to make statements false
        $modifications = [
            'is' => 'is not',
            'are' => 'are not',
            'can' => 'cannot',
            'will' => 'will not',
            'always' => 'never',
            'all' => 'no',
            'every' => 'no'
        ];
        
        foreach ($modifications as $original => $replacement) {
            if (stripos($sentence, $original) !== false) {
                return str_ireplace($original, $replacement, $sentence);
            }
        }
        
        // If no modification found, add "not" after first verb
        return preg_replace('/(\w+)\s+(is|are|was|were|can|will|should|must)/', '$1 $2 not', $sentence, 1);
    }

    /**
     * Generate short answer response
     */
    private function generateShortAnswerResponse($term, $context)
    {
        // Extract definition or description of the term from context
        $sentences = $this->extractSentences($context);
        
        foreach ($sentences as $sentence) {
            if (stripos($sentence, $term) !== false) {
                return trim($sentence);
            }
        }
        
        return "A concept related to " . $term . " as described in the text.";
    }

    /**
     * Preprocess text for better analysis
     */
    private function preprocessText($text)
    {
        // Clean and normalize text
        $text = preg_replace('/\s+/', ' ', $text); // Normalize whitespace
        $text = preg_replace('/[^\w\s\.\!\?\,\;\:\-\(\)]/', '', $text); // Remove special chars
        $text = trim($text);

        return $text;
    }

    /**
     * Analyze text to extract key information
     */
    private function analyzeText($text)
    {
        return [
            'sentences' => $this->extractSentences($text),
            'concepts' => $this->extractConcepts($text),
            'entities' => $this->extractNamedEntities($text),
            'relationships' => $this->extractRelationships($text),
            'key_phrases' => $this->extractKeyPhrases($text),
            'definitions' => $this->extractDefinitions($text),
            'statistics' => $this->extractStatistics($text),
            'processes' => $this->extractProcesses($text)
        ];
    }

    /**
     * Generate questions by specific strategy
     */
    private function generateQuestionsByStrategy($text, $analysis, $strategy, $count, $difficulty, $types)
    {
        $questions = [];

        switch ($strategy) {
            case 'concept_based':
                $questions = $this->generateConceptBasedQuestions($analysis, $count, $difficulty, $types);
                break;
            case 'fact_based':
                $questions = $this->generateFactBasedQuestions($analysis, $count, $difficulty, $types);
                break;
            case 'relationship_based':
                $questions = $this->generateRelationshipQuestions($analysis, $count, $difficulty, $types);
                break;
            case 'application_based':
                $questions = $this->generateApplicationQuestions($analysis, $count, $difficulty, $types);
                break;
        }

        return array_filter($questions); // Remove null values
    }

    /**
     * Generate concept-based questions
     */
    private function generateConceptBasedQuestions($analysis, $count, $difficulty, $types)
    {
        $questions = [];
        $concepts = $analysis['concepts'];

        foreach (array_slice($concepts, 0, $count) as $concept) {
            $questionType = $types[array_rand($types)];

            switch ($questionType) {
                case 'multiple_choice':
                    $question = $this->createConceptMultipleChoice($concept, $analysis, $difficulty);
                    break;
                case 'true_false':
                    $question = $this->createConceptTrueFalse($concept, $analysis, $difficulty);
                    break;
                case 'short_answer':
                    $question = $this->createConceptShortAnswer($concept, $analysis, $difficulty);
                    break;
                case 'essay':
                    $question = $this->createConceptEssay($concept, $analysis, $difficulty);
                    break;
                default:
                    $question = $this->createConceptMultipleChoice($concept, $analysis, $difficulty);
            }

            if ($question) {
                $questions[] = $question;
            }
        }

        return $questions;
    }

    /**
     * Generate fact-based questions
     */
    private function generateFactBasedQuestions($analysis, $count, $difficulty, $types)
    {
        $questions = [];
        $definitions = $analysis['definitions'];
        $statistics = $analysis['statistics'];

        $facts = array_merge($definitions, $statistics);

        foreach (array_slice($facts, 0, $count) as $fact) {
            $questionType = $types[array_rand($types)];
            $question = $this->createFactBasedQuestion($fact, $questionType, $difficulty, $analysis);

            if ($question) {
                $questions[] = $question;
            }
        }

        return $questions;
    }

    /**
     * Generate relationship questions
     */
    private function generateRelationshipQuestions($analysis, $count, $difficulty, $types)
    {
        $questions = [];
        $relationships = $analysis['relationships'];

        foreach (array_slice($relationships, 0, $count) as $relationship) {
            $questionType = $types[array_rand($types)];
            $question = $this->createRelationshipQuestion($relationship, $questionType, $difficulty, $analysis);

            if ($question) {
                $questions[] = $question;
            }
        }

        return $questions;
    }

    /**
     * Generate application questions
     */
    private function generateApplicationQuestions($analysis, $count, $difficulty, $types)
    {
        $questions = [];
        $processes = $analysis['processes'];
        $concepts = $analysis['concepts'];

        // Convert concepts to proper format for application questions
        $conceptData = [];
        foreach (array_slice($concepts, 0, 3) as $concept) {
            $conceptData[] = [
                'sentence' => $this->findConceptContext($concept, $analysis) ?: "Information about " . $concept,
                'type' => 'concept'
            ];
        }

        $applicationData = array_merge($processes, $conceptData);

        foreach (array_slice($applicationData, 0, $count) as $data) {
            $questionType = $types[array_rand($types)];
            $question = $this->createApplicationQuestion($data, $questionType, $difficulty, $analysis);

            if ($question) {
                $questions[] = $question;
            }
        }

        return $questions;
    }

    /**
     * Generate fallback question when other strategies fail
     */
    private function generateFallbackQuestion($text, $analysis, $type, $difficulty)
    {
        $sentences = $analysis['sentences'];
        if (empty($sentences)) return null;

        $sentence = $sentences[array_rand($sentences)];

        switch ($type) {
            case 'multiple_choice':
                return $this->createFallbackMultipleChoice($sentence, $analysis, $difficulty);
            case 'true_false':
                return $this->createFallbackTrueFalse($sentence, $difficulty);
            case 'short_answer':
                return $this->createFallbackShortAnswer($sentence, $difficulty);
            case 'essay':
                return $this->createFallbackEssay($analysis, $difficulty);
            default:
                return $this->createFallbackMultipleChoice($sentence, $analysis, $difficulty);
        }
    }

    /**
     * Extract concepts from text using keyword analysis
     */
    private function extractConcepts($text)
    {
        $concepts = [];
        $words = str_word_count(strtolower($text), 1);

        // Filter out stop words and short words
        $filteredWords = array_filter($words, function($word) {
            return strlen($word) > 3 && !in_array($word, $this->stopWords);
        });

        // Count word frequency
        $wordCounts = array_count_values($filteredWords);
        arsort($wordCounts);

        // Extract top concepts with lower threshold for shorter texts
        $topWords = array_slice(array_keys($wordCounts), 0, 20);
        $threshold = max(1, min(2, floor(str_word_count($text) / 50))); // Dynamic threshold

        // Look for concept keywords and frequent words
        foreach ($topWords as $word) {
            if (in_array($word, $this->conceptKeywords) || $wordCounts[$word] >= $threshold) {
                $concepts[] = $word;
            }
        }

        // If no concepts found, use the most frequent non-stop words
        if (empty($concepts)) {
            $concepts = array_slice($topWords, 0, 5);
        }

        return array_unique($concepts);
    }

    /**
     * Extract named entities (proper nouns, dates, numbers)
     */
    private function extractNamedEntities($text)
    {
        $entities = [];

        // Extract proper nouns (capitalized words)
        preg_match_all('/\b[A-Z][a-z]+\b/', $text, $properNouns);
        $entities['proper_nouns'] = array_unique($properNouns[0]);

        // Extract dates
        preg_match_all('/\b\d{1,2}\/\d{1,2}\/\d{2,4}\b|\b\d{4}\b|\b[A-Z][a-z]+ \d{1,2}, \d{4}\b/', $text, $dates);
        $entities['dates'] = array_unique($dates[0]);

        // Extract numbers and percentages
        preg_match_all('/\b\d+(?:\.\d+)?%?\b/', $text, $numbers);
        $entities['numbers'] = array_unique($numbers[0]);

        return $entities;
    }

    /**
     * Extract relationships between concepts
     */
    private function extractRelationships($text)
    {
        $relationships = [];
        $sentences = $this->extractSentences($text);

        foreach ($sentences as $sentence) {
            // Look for relationship patterns
            foreach ($this->questionPatterns['relationships'] as $pattern) {
                if (preg_match($pattern, $sentence, $matches)) {
                    $relationships[] = [
                        'sentence' => $sentence,
                        'type' => 'relationship',
                        'elements' => array_slice($matches, 1)
                    ];
                }
            }
        }

        return $relationships;
    }

    /**
     * Extract key phrases using n-gram analysis
     */
    private function extractKeyPhrases($text)
    {
        $phrases = [];
        $sentences = $this->extractSentences($text);

        foreach ($sentences as $sentence) {
            // Extract 2-3 word phrases
            $words = explode(' ', strtolower($sentence));

            for ($i = 0; $i < count($words) - 1; $i++) {
                if (!in_array($words[$i], $this->stopWords) && !in_array($words[$i + 1], $this->stopWords)) {
                    $phrase = $words[$i] . ' ' . $words[$i + 1];
                    if (strlen($phrase) > 6) {
                        $phrases[] = $phrase;
                    }
                }
            }
        }

        return array_unique($phrases);
    }

    /**
     * Extract definitions from text
     */
    private function extractDefinitions($text)
    {
        $definitions = [];
        $sentences = $this->extractSentences($text);

        foreach ($sentences as $sentence) {
            // Look for definition patterns
            foreach ($this->questionPatterns['definitions'] as $pattern) {
                if (preg_match($pattern, $sentence, $matches)) {
                    $definitions[] = [
                        'term' => trim($matches[1]),
                        'definition' => trim($matches[2]),
                        'sentence' => $sentence
                    ];
                }
            }
        }

        return $definitions;
    }

    /**
     * Extract statistics and numerical data
     */
    private function extractStatistics($text)
    {
        $statistics = [];
        $sentences = $this->extractSentences($text);

        foreach ($sentences as $sentence) {
            if (preg_match('/\b\d+(?:\.\d+)?%?\b/', $sentence)) {
                $statistics[] = [
                    'sentence' => $sentence,
                    'type' => 'statistic'
                ];
            }
        }

        return $statistics;
    }

    /**
     * Extract processes and procedures
     */
    private function extractProcesses($text)
    {
        $processes = [];
        $sentences = $this->extractSentences($text);

        foreach ($sentences as $sentence) {
            // Look for process indicators
            $processWords = ['first', 'then', 'next', 'finally', 'step', 'process', 'method', 'procedure'];

            foreach ($processWords as $word) {
                if (stripos($sentence, $word) !== false) {
                    $processes[] = [
                        'sentence' => $sentence,
                        'type' => 'process'
                    ];
                    break;
                }
            }
        }

        return $processes;
    }

    /**
     * Initialize stop words
     */
    private function initializeStopWords()
    {
        $this->stopWords = [
            'the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by',
            'is', 'are', 'was', 'were', 'be', 'been', 'being', 'have', 'has', 'had', 'do', 'does', 'did',
            'will', 'would', 'could', 'should', 'may', 'might', 'can', 'this', 'that', 'these', 'those',
            'i', 'you', 'he', 'she', 'it', 'we', 'they', 'me', 'him', 'her', 'us', 'them', 'my', 'your',
            'his', 'her', 'its', 'our', 'their', 'from', 'up', 'about', 'into', 'through', 'during',
            'before', 'after', 'above', 'below', 'between', 'among', 'under', 'over', 'out', 'off',
            'down', 'up', 'again', 'further', 'then', 'once'
        ];
    }

    /**
     * Initialize question patterns for text analysis
     */
    private function initializeQuestionPatterns()
    {
        $this->questionPatterns = [
            'definitions' => [
                '/(.+?)\s+is\s+(?:a|an|the)?\s*(.+?)\./',
                '/(.+?)\s+are\s+(.+?)\./',
                '/(.+?)\s+refers to\s+(.+?)\./',
                '/(.+?)\s+means\s+(.+?)\./',
                '/(.+?)\s+defined as\s+(.+?)\./'
            ],
            'relationships' => [
                '/(.+?)\s+causes?\s+(.+?)\./',
                '/(.+?)\s+leads? to\s+(.+?)\./',
                '/(.+?)\s+results? in\s+(.+?)\./',
                '/(.+?)\s+affects?\s+(.+?)\./',
                '/(.+?)\s+influences?\s+(.+?)\./',
                '/(.+?)\s+depends on\s+(.+?)\./'
            ]
        ];
    }

    /**
     * Initialize concept keywords
     */
    private function initializeConceptKeywords()
    {
        $this->conceptKeywords = [
            'theory', 'principle', 'concept', 'method', 'approach', 'technique', 'strategy',
            'system', 'process', 'procedure', 'function', 'structure', 'component', 'element',
            'factor', 'aspect', 'characteristic', 'feature', 'property', 'attribute',
            'analysis', 'synthesis', 'evaluation', 'application', 'implementation', 'development',
            'research', 'study', 'investigation', 'experiment', 'observation', 'measurement'
        ];
    }

    /**
     * Create concept-based multiple choice question
     */
    private function createConceptMultipleChoice($concept, $analysis, $difficulty)
    {
        $templates = [
            'easy' => [
                "What is {concept}?",
                "Which of the following best describes {concept}?",
                "What does {concept} refer to?"
            ],
            'medium' => [
                "How does {concept} function in this context?",
                "What is the primary purpose of {concept}?",
                "Which statement best explains {concept}?"
            ],
            'hard' => [
                "What are the implications of {concept} in this field?",
                "How does {concept} relate to other concepts discussed?",
                "What would be the result if {concept} were modified?"
            ]
        ];

        $template = $templates[$difficulty][array_rand($templates[$difficulty])];
        $questionText = str_replace('{concept}', $concept, $template);

        // Find context for the concept
        $context = $this->findConceptContext($concept, $analysis);
        if (!$context) return null;

        $correctAnswer = $this->extractConceptAnswer($concept, $context);
        $wrongAnswers = $this->generateEnhancedWrongAnswers($correctAnswer, $analysis, 3);

        $answers = array_merge(
            [['text' => $correctAnswer, 'is_correct' => true]],
            array_map(function($answer) {
                return ['text' => $answer, 'is_correct' => false];
            }, $wrongAnswers)
        );

        shuffle($answers);

        return [
            'question' => $questionText,
            'type' => 'multiple_choice',
            'answers' => $answers
        ];
    }

    /**
     * Create concept-based true/false question
     */
    private function createConceptTrueFalse($concept, $analysis, $difficulty)
    {
        $context = $this->findConceptContext($concept, $analysis);
        if (!$context) return null;

        $isTrue = rand(0, 1) === 1;

        if ($isTrue) {
            $statement = $this->createTrueStatement($concept, $context);
            $correctAnswer = 'True';
        } else {
            $statement = $this->createFalseStatement($concept, $context);
            $correctAnswer = 'False';
        }

        return [
            'question' => "True or False: " . $statement,
            'type' => 'true_false',
            'answers' => [
                ['text' => 'True', 'is_correct' => $correctAnswer === 'True'],
                ['text' => 'False', 'is_correct' => $correctAnswer === 'False']
            ]
        ];
    }

    /**
     * Create concept-based short answer question
     */
    private function createConceptShortAnswer($concept, $analysis, $difficulty)
    {
        $templates = [
            'easy' => "Define {concept}.",
            'medium' => "Explain the significance of {concept}.",
            'hard' => "Analyze the role of {concept} in the given context."
        ];

        $questionText = str_replace('{concept}', $concept, $templates[$difficulty]);
        $context = $this->findConceptContext($concept, $analysis);

        if (!$context) return null;

        return [
            'question' => $questionText,
            'type' => 'short_answer',
            'answers' => [
                ['text' => $this->generateShortAnswerFromContext($concept, $context), 'is_correct' => true]
            ]
        ];
    }

    /**
     * Create concept-based essay question
     */
    private function createConceptEssay($concept, $analysis, $difficulty)
    {
        $templates = [
            'easy' => "Discuss the concept of {concept}.",
            'medium' => "Analyze {concept} and its applications.",
            'hard' => "Critically evaluate {concept} and its implications."
        ];

        $questionText = str_replace('{concept}', $concept, $templates[$difficulty]);

        return [
            'question' => $questionText,
            'type' => 'essay',
            'answers' => [
                ['text' => 'This essay question requires a comprehensive analysis of the concept.', 'is_correct' => true]
            ]
        ];
    }

    /**
     * Create fact-based question
     */
    private function createFactBasedQuestion($fact, $type, $difficulty, $analysis)
    {
        switch ($type) {
            case 'multiple_choice':
                return $this->createFactMultipleChoice($fact, $difficulty, $analysis);
            case 'true_false':
                return $this->createFactTrueFalse($fact, $difficulty);
            case 'short_answer':
                return $this->createFactShortAnswer($fact, $difficulty);
            default:
                return $this->createFactMultipleChoice($fact, $difficulty, $analysis);
        }
    }

    /**
     * Create relationship question
     */
    private function createRelationshipQuestion($relationship, $type, $difficulty, $analysis)
    {
        switch ($type) {
            case 'multiple_choice':
                return $this->createRelationshipMultipleChoice($relationship, $difficulty, $analysis);
            case 'true_false':
                return $this->createRelationshipTrueFalse($relationship, $difficulty);
            case 'short_answer':
                return $this->createRelationshipShortAnswer($relationship, $difficulty);
            default:
                return $this->createRelationshipMultipleChoice($relationship, $difficulty, $analysis);
        }
    }

    /**
     * Create application question
     */
    private function createApplicationQuestion($data, $type, $difficulty, $analysis)
    {
        switch ($type) {
            case 'multiple_choice':
                return $this->createApplicationMultipleChoice($data, $difficulty, $analysis);
            case 'essay':
                return $this->createApplicationEssay($data, $difficulty);
            default:
                return $this->createApplicationMultipleChoice($data, $difficulty, $analysis);
        }
    }

    /**
     * Helper methods for enhanced question generation
     */
    private function findConceptContext($concept, $analysis)
    {
        // First try exact match
        foreach ($analysis['sentences'] as $sentence) {
            if (stripos($sentence, $concept) !== false) {
                return $sentence;
            }
        }

        // Try partial match with word boundaries
        foreach ($analysis['sentences'] as $sentence) {
            if (preg_match('/\b' . preg_quote($concept, '/') . '\b/i', $sentence)) {
                return $sentence;
            }
        }

        // If no context found, return the first sentence as fallback
        return !empty($analysis['sentences']) ? $analysis['sentences'][0] : null;
    }

    private function extractConceptAnswer($concept, $context)
    {
        // Extract definition or description from context
        if (preg_match('/(.+?)\s+is\s+(.+?)\./', $context, $matches)) {
            return trim($matches[2]);
        }

        // Fallback to a portion of the context
        $words = explode(' ', $context);
        return implode(' ', array_slice($words, 0, min(8, count($words))));
    }

    private function generateEnhancedWrongAnswers($correctAnswer, $analysis, $count)
    {
        $wrongAnswers = [];
        $concepts = $analysis['concepts'];
        $entities = $analysis['entities'];

        // Use other concepts as wrong answers
        foreach ($concepts as $concept) {
            if (stripos($correctAnswer, $concept) === false && count($wrongAnswers) < $count) {
                $wrongAnswers[] = "Related to " . $concept;
            }
        }

        // Fill with generic wrong answers if needed
        $generics = [
            "Not mentioned in the text",
            "Insufficient information provided",
            "Cannot be determined from context",
            "None of the above options"
        ];

        while (count($wrongAnswers) < $count) {
            $generic = $generics[array_rand($generics)];
            if (!in_array($generic, $wrongAnswers)) {
                $wrongAnswers[] = $generic;
            }
        }

        return array_slice($wrongAnswers, 0, $count);
    }

    private function createTrueStatement($concept, $context)
    {
        return $context;
    }

    private function createFalseStatement($concept, $context)
    {
        // Modify the context to make it false
        $modifications = [
            'is' => 'is not',
            'are' => 'are not',
            'can' => 'cannot',
            'will' => 'will not',
            'always' => 'never',
            'increases' => 'decreases',
            'improves' => 'worsens'
        ];

        foreach ($modifications as $original => $replacement) {
            if (stripos($context, $original) !== false) {
                return str_ireplace($original, $replacement, $context);
            }
        }

        return "It is incorrect that " . lcfirst($context);
    }

    private function generateShortAnswerFromContext($concept, $context)
    {
        // Extract key information about the concept
        $sentences = explode('.', $context);
        $relevantSentence = '';

        foreach ($sentences as $sentence) {
            if (stripos($sentence, $concept) !== false) {
                $relevantSentence = trim($sentence);
                break;
            }
        }

        return $relevantSentence ?: $context;
    }

    /**
     * Create simple question from sentence when other methods fail
     */
    private function createSimpleQuestion($sentence, $type, $difficulty)
    {
        switch ($type) {
            case 'multiple_choice':
                return $this->createSimpleMultipleChoice($sentence, $difficulty);
            case 'true_false':
                return $this->createSimpleTrueFalse($sentence, $difficulty);
            case 'short_answer':
                return $this->createSimpleShortAnswer($sentence, $difficulty);
            case 'essay':
                return $this->createSimpleEssay($sentence, $difficulty);
            default:
                return $this->createSimpleMultipleChoice($sentence, $difficulty);
        }
    }

    private function createSimpleMultipleChoice($sentence, $difficulty)
    {
        $templates = [
            'easy' => "According to the text, which statement is true?",
            'medium' => "Based on the information provided, which is correct?",
            'hard' => "Which of the following accurately reflects the content?"
        ];

        $question = $templates[$difficulty];
        $correctAnswer = $sentence;

        // Generate simple wrong answers
        $wrongAnswers = [
            "This information is not mentioned in the text",
            "The opposite of what is stated",
            "A different interpretation of the content"
        ];

        $answers = array_merge(
            [['text' => $correctAnswer, 'is_correct' => true]],
            array_map(function($answer) {
                return ['text' => $answer, 'is_correct' => false];
            }, $wrongAnswers)
        );

        shuffle($answers);

        return [
            'question' => $question,
            'type' => 'multiple_choice',
            'answers' => $answers
        ];
    }

    private function createSimpleTrueFalse($sentence, $difficulty)
    {
        $isTrue = rand(0, 1) === 1;

        if ($isTrue) {
            $statement = $sentence;
            $correctAnswer = 'True';
        } else {
            $statement = $this->modifySentenceToMakeFalse($sentence);
            $correctAnswer = 'False';
        }

        return [
            'question' => "True or False: " . $statement,
            'type' => 'true_false',
            'answers' => [
                ['text' => 'True', 'is_correct' => $correctAnswer === 'True'],
                ['text' => 'False', 'is_correct' => $correctAnswer === 'False']
            ]
        ];
    }

    private function createSimpleShortAnswer($sentence, $difficulty)
    {
        $templates = [
            'easy' => "What does the text say about this topic?",
            'medium' => "Explain the main point of this statement.",
            'hard' => "Analyze the significance of this information."
        ];

        return [
            'question' => $templates[$difficulty],
            'type' => 'short_answer',
            'answers' => [
                ['text' => $sentence, 'is_correct' => true]
            ]
        ];
    }

    private function createSimpleEssay($sentence, $difficulty)
    {
        $templates = [
            'easy' => "Discuss the following statement from the text.",
            'medium' => "Analyze and explain the following information.",
            'hard' => "Critically evaluate the implications of this statement."
        ];

        return [
            'question' => $templates[$difficulty] . " \"" . $sentence . "\"",
            'type' => 'essay',
            'answers' => [
                ['text' => 'This essay question requires detailed analysis and discussion.', 'is_correct' => true]
            ]
        ];
    }

    // Fallback question creation methods
    private function createFallbackMultipleChoice($sentence, $analysis, $difficulty)
    {
        $question = "According to the text, which statement is correct?";
        $correctAnswer = $sentence;
        $wrongAnswers = $this->generateEnhancedWrongAnswers($correctAnswer, $analysis, 3);

        $answers = array_merge(
            [['text' => $correctAnswer, 'is_correct' => true]],
            array_map(function($answer) {
                return ['text' => $answer, 'is_correct' => false];
            }, $wrongAnswers)
        );

        shuffle($answers);

        return [
            'question' => $question,
            'type' => 'multiple_choice',
            'answers' => $answers
        ];
    }

    private function createFallbackTrueFalse($sentence, $difficulty)
    {
        $isTrue = rand(0, 1) === 1;

        if ($isTrue) {
            $statement = $sentence;
            $correctAnswer = 'True';
        } else {
            $statement = $this->modifySentenceToMakeFalse($sentence);
            $correctAnswer = 'False';
        }

        return [
            'question' => "True or False: " . $statement,
            'type' => 'true_false',
            'answers' => [
                ['text' => 'True', 'is_correct' => $correctAnswer === 'True'],
                ['text' => 'False', 'is_correct' => $correctAnswer === 'False']
            ]
        ];
    }

    private function createFallbackShortAnswer($sentence, $difficulty)
    {
        $question = "Based on the text, explain the following statement:";

        return [
            'question' => $question,
            'type' => 'short_answer',
            'answers' => [
                ['text' => $sentence, 'is_correct' => true]
            ]
        ];
    }

    private function createFallbackEssay($analysis, $difficulty)
    {
        $concepts = $analysis['concepts'];
        $mainConcept = !empty($concepts) ? $concepts[0] : 'the main topic';

        $question = "Write an essay discussing " . $mainConcept . " based on the provided text.";

        return [
            'question' => $question,
            'type' => 'essay',
            'answers' => [
                ['text' => 'This essay question requires comprehensive analysis and discussion.', 'is_correct' => true]
            ]
        ];
    }

    // Implemented question creation methods
    private function createFactMultipleChoice($fact, $difficulty, $analysis)
    {
        if (isset($fact['sentence'])) {
            $sentence = $fact['sentence'];
            $question = "According to the text, which statement about the following is correct?";

            $correctAnswer = $sentence;
            $wrongAnswers = $this->generateEnhancedWrongAnswers($correctAnswer, $analysis, 3);

            $answers = array_merge(
                [['text' => $correctAnswer, 'is_correct' => true]],
                array_map(function($answer) {
                    return ['text' => $answer, 'is_correct' => false];
                }, $wrongAnswers)
            );

            shuffle($answers);

            return [
                'question' => $question,
                'type' => 'multiple_choice',
                'answers' => $answers
            ];
        }
        return null;
    }

    private function createFactTrueFalse($fact, $difficulty)
    {
        if (isset($fact['sentence'])) {
            $sentence = $fact['sentence'];
            $isTrue = rand(0, 1) === 1;

            if ($isTrue) {
                $statement = $sentence;
                $correctAnswer = 'True';
            } else {
                $statement = $this->modifySentenceToMakeFalse($sentence);
                $correctAnswer = 'False';
            }

            return [
                'question' => "True or False: " . $statement,
                'type' => 'true_false',
                'answers' => [
                    ['text' => 'True', 'is_correct' => $correctAnswer === 'True'],
                    ['text' => 'False', 'is_correct' => $correctAnswer === 'False']
                ]
            ];
        }
        return null;
    }

    private function createFactShortAnswer($fact, $difficulty)
    {
        if (isset($fact['term']) && isset($fact['definition'])) {
            $question = "What is " . $fact['term'] . "?";
            return [
                'question' => $question,
                'type' => 'short_answer',
                'answers' => [
                    ['text' => $fact['definition'], 'is_correct' => true]
                ]
            ];
        } elseif (isset($fact['sentence'])) {
            $question = "Explain the following statement from the text:";
            return [
                'question' => $question,
                'type' => 'short_answer',
                'answers' => [
                    ['text' => $fact['sentence'], 'is_correct' => true]
                ]
            ];
        }
        return null;
    }

    private function createRelationshipMultipleChoice($relationship, $difficulty, $analysis)
    {
        if (isset($relationship['sentence'])) {
            $question = "What relationship is described in the following context?";
            $correctAnswer = $relationship['sentence'];
            $wrongAnswers = $this->generateEnhancedWrongAnswers($correctAnswer, $analysis, 3);

            $answers = array_merge(
                [['text' => $correctAnswer, 'is_correct' => true]],
                array_map(function($answer) {
                    return ['text' => $answer, 'is_correct' => false];
                }, $wrongAnswers)
            );

            shuffle($answers);

            return [
                'question' => $question,
                'type' => 'multiple_choice',
                'answers' => $answers
            ];
        }
        return null;
    }

    private function createRelationshipTrueFalse($relationship, $difficulty)
    {
        if (isset($relationship['sentence'])) {
            $sentence = $relationship['sentence'];
            $isTrue = rand(0, 1) === 1;

            if ($isTrue) {
                $statement = $sentence;
                $correctAnswer = 'True';
            } else {
                $statement = $this->modifySentenceToMakeFalse($sentence);
                $correctAnswer = 'False';
            }

            return [
                'question' => "True or False: " . $statement,
                'type' => 'true_false',
                'answers' => [
                    ['text' => 'True', 'is_correct' => $correctAnswer === 'True'],
                    ['text' => 'False', 'is_correct' => $correctAnswer === 'False']
                ]
            ];
        }
        return null;
    }

    private function createRelationshipShortAnswer($relationship, $difficulty)
    {
        if (isset($relationship['sentence'])) {
            $question = "Describe the relationship mentioned in the text:";
            return [
                'question' => $question,
                'type' => 'short_answer',
                'answers' => [
                    ['text' => $relationship['sentence'], 'is_correct' => true]
                ]
            ];
        }
        return null;
    }

    private function createApplicationMultipleChoice($data, $difficulty, $analysis)
    {
        if (isset($data['sentence'])) {
            $templates = [
                'easy' => "How would you apply the following concept?",
                'medium' => "What would be a practical application of this information?",
                'hard' => "How could this concept be implemented in a real-world scenario?"
            ];

            $question = $templates[$difficulty];
            $correctAnswer = $data['sentence'];
            $wrongAnswers = $this->generateEnhancedWrongAnswers($correctAnswer, $analysis, 3);

            $answers = array_merge(
                [['text' => $correctAnswer, 'is_correct' => true]],
                array_map(function($answer) {
                    return ['text' => $answer, 'is_correct' => false];
                }, $wrongAnswers)
            );

            shuffle($answers);

            return [
                'question' => $question,
                'type' => 'multiple_choice',
                'answers' => $answers
            ];
        }
        return null;
    }

    private function createApplicationEssay($data, $difficulty)
    {
        if (isset($data['sentence'])) {
            $templates = [
                'easy' => "Discuss how the following concept could be applied:",
                'medium' => "Analyze the practical applications of this information:",
                'hard' => "Critically evaluate the implementation possibilities of this concept:"
            ];

            $question = $templates[$difficulty] . " " . $data['sentence'];

            return [
                'question' => $question,
                'type' => 'essay',
                'answers' => [
                    ['text' => 'This essay question requires analysis of practical applications and implementation strategies.', 'is_correct' => true]
                ]
            ];
        }
        return null;
    }
}
