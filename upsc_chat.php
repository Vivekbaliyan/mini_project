<?php
/**
 * UPSC AI Agent - Chat API Backend
 * Uses Anthropic PHP SDK with tool use for MCQ generation and study planning
 */

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/upsc_config.php';

use Anthropic\Client;
use Anthropic\Messages\ToolUseBlock;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['message']) || !isset($input['history'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing message or history']);
    exit;
}

$apiKey = getenv('ANTHROPIC_API_KEY');
if (!$apiKey) {
    http_response_code(500);
    echo json_encode(['error' => 'ANTHROPIC_API_KEY not configured. Please set the environment variable.']);
    exit;
}

$client = new Client(apiKey: $apiKey);

// ──────────────────────────────────────────────
// Tool Definitions
// ──────────────────────────────────────────────
$tools = [
    [
        'name' => 'generate_mcqs',
        'description' => 'Generate multiple choice questions (MCQs) for UPSC practice on a specific topic. Use this when the user asks for practice questions, MCQs, or a quiz.',
        'inputSchema' => [
            'type' => 'object',
            'properties' => [
                'topic' => [
                    'type' => 'string',
                    'description' => 'The UPSC topic for MCQs (e.g., "Indian Polity - Fundamental Rights", "Economy - Budget")'
                ],
                'count' => [
                    'type' => 'integer',
                    'description' => 'Number of MCQs to generate (1-10)',
                    'minimum' => 1,
                    'maximum' => 10
                ],
                'difficulty' => [
                    'type' => 'string',
                    'enum' => ['easy', 'medium', 'hard'],
                    'description' => 'Difficulty level of the questions'
                ]
            ],
            'required' => ['topic', 'count', 'difficulty']
        ]
    ],
    [
        'name' => 'create_study_plan',
        'description' => 'Create a personalized UPSC study plan. Use this when the user asks for a study schedule, study plan, or preparation strategy.',
        'inputSchema' => [
            'type' => 'object',
            'properties' => [
                'duration_weeks' => [
                    'type' => 'integer',
                    'description' => 'Number of weeks for the study plan',
                    'minimum' => 1,
                    'maximum' => 52
                ],
                'target_exam' => [
                    'type' => 'string',
                    'enum' => ['prelims', 'mains', 'both'],
                    'description' => 'Target exam phase'
                ],
                'weak_areas' => [
                    'type' => 'array',
                    'items' => ['type' => 'string'],
                    'description' => 'List of subjects/topics where the student needs more focus'
                ],
                'daily_hours' => [
                    'type' => 'number',
                    'description' => 'Available study hours per day'
                ]
            ],
            'required' => ['duration_weeks', 'target_exam', 'daily_hours']
        ]
    ],
    [
        'name' => 'analyze_current_affairs',
        'description' => 'Analyze a current affairs topic and explain its UPSC relevance across different GS papers. Use this for recent news or events.',
        'inputSchema' => [
            'type' => 'object',
            'properties' => [
                'topic' => [
                    'type' => 'string',
                    'description' => 'The current affairs topic or news event to analyze'
                ],
                'detail_level' => [
                    'type' => 'string',
                    'enum' => ['brief', 'detailed'],
                    'description' => 'Level of analysis detail'
                ]
            ],
            'required' => ['topic', 'detail_level']
        ]
    ]
];

// ──────────────────────────────────────────────
// Tool Execution Functions
// ──────────────────────────────────────────────

function executeTool(string $name, array $input, Client $client): string
{
    switch ($name) {
        case 'generate_mcqs':
            return generateMCQs($input, $client);
        case 'create_study_plan':
            return createStudyPlan($input, $client);
        case 'analyze_current_affairs':
            return analyzeCurrentAffairs($input, $client);
        default:
            return "Unknown tool: $name";
    }
}

function generateMCQs(array $input, Client $client): string
{
    $topic = $input['topic'];
    $count = $input['count'] ?? 5;
    $difficulty = $input['difficulty'] ?? 'medium';

    $prompt = "Generate exactly $count UPSC-style MCQs on the topic: \"$topic\".
Difficulty: $difficulty

Format each question as:
**Q[number]. [Question text]**
(a) Option A
(b) Option B
(c) Option C
(d) Option D

**Answer:** (x) [Correct option text]
**Explanation:** [Brief UPSC-relevant explanation, mention PYQ if applicable]

---

Make questions UPSC Prelims standard. Include factual, conceptual and application-based questions.";

    $response = $client->messages->create(
        model: 'claude-opus-4-6',
        maxTokens: 2000,
        thinking: ['type' => 'adaptive'],
        messages: [['role' => 'user', 'content' => $prompt]]
    );

    foreach ($response->content as $block) {
        if ($block->type === 'text') {
            return $block->text;
        }
    }
    return "Could not generate MCQs.";
}

function createStudyPlan(array $input, Client $client): string
{
    $weeks = $input['duration_weeks'];
    $target = $input['target_exam'];
    $hours = $input['daily_hours'];
    $weakAreas = implode(', ', $input['weak_areas'] ?? []);

    $prompt = "Create a detailed $weeks-week UPSC study plan for target: $target.
Daily available hours: $hours
Weak areas needing more focus: " . ($weakAreas ?: 'none specified') . "

Include:
1. Week-wise topic breakdown
2. Daily schedule template
3. Revision strategy
4. Mock test schedule
5. Recommended resources for each subject
6. Milestone checkpoints

Format it clearly with tables or structured lists.";

    $response = $client->messages->create(
        model: 'claude-opus-4-6',
        maxTokens: 3000,
        thinking: ['type' => 'adaptive'],
        messages: [['role' => 'user', 'content' => $prompt]]
    );

    foreach ($response->content as $block) {
        if ($block->type === 'text') {
            return $block->text;
        }
    }
    return "Could not create study plan.";
}

function analyzeCurrentAffairs(array $input, Client $client): string
{
    $topic = $input['topic'];
    $detail = $input['detail_level'] ?? 'detailed';

    $prompt = "Analyze \"$topic\" for UPSC 2026 preparation ($detail analysis).

Cover:
1. **What happened**: Brief factual summary
2. **UPSC Relevance**:
   - GS-I angle (if any)
   - GS-II angle (Governance/Polity/IR if any)
   - GS-III angle (Economy/Environment/Security if any)
   - GS-IV angle (Ethics if any)
3. **Key facts to remember** (bullet points)
4. **Likely exam questions** (Prelims MCQ style + Mains question)
5. **Related static topics** to revise";

    $response = $client->messages->create(
        model: 'claude-opus-4-6',
        maxTokens: 2000,
        thinking: ['type' => 'adaptive'],
        messages: [['role' => 'user', 'content' => $prompt]]
    );

    foreach ($response->content as $block) {
        if ($block->type === 'text') {
            return $block->text;
        }
    }
    return "Could not analyze current affairs.";
}

// ──────────────────────────────────────────────
// Build conversation messages
// ──────────────────────────────────────────────

$history = $input['history'] ?? [];
$userMessage = $input['message'];

$messages = [];
foreach ($history as $turn) {
    if (isset($turn['role']) && isset($turn['content'])) {
        $messages[] = [
            'role' => $turn['role'],
            'content' => $turn['content']
        ];
    }
}
$messages[] = ['role' => 'user', 'content' => $userMessage];

// ──────────────────────────────────────────────
// Agentic tool use loop
// ──────────────────────────────────────────────

try {
    $response = $client->messages->create(
        model: 'claude-opus-4-6',
        maxTokens: 4096,
        thinking: ['type' => 'adaptive'],
        system: UPSC_SYSTEM_PROMPT,
        tools: $tools,
        messages: $messages
    );

    // Handle tool use loop
    while ($response->stopReason === 'tool_use') {
        $toolResults = [];
        foreach ($response->content as $block) {
            if ($block instanceof ToolUseBlock) {
                $result = executeTool($block->name, $block->input, $client);
                $toolResults[] = [
                    'type' => 'tool_result',
                    'toolUseID' => $block->id,
                    'content' => $result
                ];
            }
        }

        $messages[] = ['role' => 'assistant', 'content' => $response->content];
        $messages[] = ['role' => 'user', 'content' => $toolResults];

        $response = $client->messages->create(
            model: 'claude-opus-4-6',
            maxTokens: 4096,
            thinking: ['type' => 'adaptive'],
            system: UPSC_SYSTEM_PROMPT,
            tools: $tools,
            messages: $messages
        );
    }

    // Extract final text response
    $replyText = '';
    foreach ($response->content as $block) {
        if ($block->type === 'text') {
            $replyText .= $block->text;
        }
    }

    echo json_encode([
        'reply' => $replyText,
        'inputTokens' => $response->usage->inputTokens ?? 0,
        'outputTokens' => $response->usage->outputTokens ?? 0
    ]);

} catch (\Anthropic\Exceptions\UnprocessableEntityException $e) {
    http_response_code(422);
    echo json_encode(['error' => 'Invalid request: ' . $e->getMessage()]);
} catch (\Anthropic\Exceptions\RateLimitException $e) {
    http_response_code(429);
    echo json_encode(['error' => 'Rate limit reached. Please wait a moment and try again.']);
} catch (\Anthropic\Exceptions\AuthenticationException $e) {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid API key. Please check ANTHROPIC_API_KEY.']);
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Agent error: ' . $e->getMessage()]);
}
