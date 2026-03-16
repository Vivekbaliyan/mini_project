<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UPSC 2026 AI Agent | Your Personal Mentor</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #0f1117;
            color: #e2e8f0;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #1a1d2e 0%, #16213e 100%);
            border-bottom: 1px solid #2d3561;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            flex-shrink: 0;
        }
        .header-icon {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, #f97316, #ea580c);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
        }
        .header-title { font-size: 20px; font-weight: 700; color: #f8fafc; }
        .header-subtitle { font-size: 12px; color: #94a3b8; margin-top: 2px; }
        .header-badge {
            margin-left: auto;
            background: #1e3a5f;
            color: #60a5fa;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid #2563eb33;
        }

        /* Main Layout */
        .main { display: flex; flex: 1; overflow: hidden; }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: #13151f;
            border-right: 1px solid #1e2236;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            flex-shrink: 0;
        }
        .sidebar-section { padding: 16px; border-bottom: 1px solid #1e2236; }
        .sidebar-section h3 {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
            margin-bottom: 10px;
        }

        /* Quick Action Buttons */
        .quick-btn {
            width: 100%;
            background: #1a1d2e;
            border: 1px solid #2d3561;
            color: #cbd5e1;
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 13px;
            cursor: pointer;
            text-align: left;
            margin-bottom: 6px;
            transition: all 0.2s;
            line-height: 1.4;
        }
        .quick-btn:hover { background: #1e2236; border-color: #3b4680; color: #e2e8f0; }
        .quick-btn .btn-icon { margin-right: 8px; }

        /* Subject Tags */
        .subject-tag {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            margin: 3px 3px 3px 0;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .subject-tag:hover { opacity: 0.8; }
        .tag-polity   { background: #1e3a5f; color: #60a5fa; }
        .tag-history  { background: #3b1f1f; color: #f87171; }
        .tag-geo      { background: #1a3b2e; color: #34d399; }
        .tag-economy  { background: #3b2c0f; color: #fbbf24; }
        .tag-env      { background: #1e3320; color: #4ade80; }
        .tag-ethics   { background: #2d1b3d; color: #c084fc; }
        .tag-science  { background: #0f2d3d; color: #38bdf8; }
        .tag-ir       { background: #3d1f0f; color: #fb923c; }

        /* Stats */
        .stat-row { display: flex; justify-content: space-between; margin-bottom: 8px; }
        .stat-item { text-align: center; flex: 1; }
        .stat-num { font-size: 18px; font-weight: 700; color: #60a5fa; }
        .stat-label { font-size: 11px; color: #64748b; }

        /* Chat Area */
        .chat-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Messages Container */
        #messages {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        #messages::-webkit-scrollbar { width: 6px; }
        #messages::-webkit-scrollbar-track { background: transparent; }
        #messages::-webkit-scrollbar-thumb { background: #2d3561; border-radius: 3px; }

        /* Welcome Screen */
        .welcome-screen {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            text-align: center;
            padding: 40px;
            gap: 16px;
        }
        .welcome-icon { font-size: 56px; }
        .welcome-title { font-size: 24px; font-weight: 700; color: #f8fafc; }
        .welcome-sub { color: #94a3b8; font-size: 15px; max-width: 450px; line-height: 1.6; }
        .welcome-tips {
            display: flex;
            gap: 12px;
            margin-top: 8px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .tip-card {
            background: #1a1d2e;
            border: 1px solid #2d3561;
            border-radius: 10px;
            padding: 14px 18px;
            font-size: 13px;
            color: #94a3b8;
            cursor: pointer;
            transition: all 0.2s;
            text-align: left;
            max-width: 200px;
        }
        .tip-card:hover { border-color: #f97316; color: #f8fafc; }
        .tip-card .tip-icon { font-size: 20px; margin-bottom: 6px; }
        .tip-card .tip-text { line-height: 1.4; }

        /* Message Bubbles */
        .message {
            display: flex;
            gap: 12px;
            max-width: 85%;
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: none; } }

        .message.user { margin-left: auto; flex-direction: row-reverse; }
        .message.assistant { margin-right: auto; }

        .avatar {
            width: 34px; height: 34px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }
        .avatar.user-avatar { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
        .avatar.ai-avatar   { background: linear-gradient(135deg, #f97316, #ea580c); }

        .bubble {
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 14px;
            line-height: 1.65;
        }
        .message.user .bubble {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
            color: #eff6ff;
            border-radius: 12px 4px 12px 12px;
        }
        .message.assistant .bubble {
            background: #1a1d2e;
            border: 1px solid #2d3561;
            color: #e2e8f0;
            border-radius: 4px 12px 12px 12px;
        }

        /* Markdown-like formatting inside AI bubbles */
        .bubble strong { color: #fbbf24; font-weight: 600; }
        .bubble em { color: #94a3b8; font-style: italic; }
        .bubble code {
            background: #0f1117;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 13px;
            color: #34d399;
        }
        .bubble pre {
            background: #0f1117;
            padding: 12px;
            border-radius: 8px;
            overflow-x: auto;
            margin: 8px 0;
            border: 1px solid #2d3561;
        }
        .bubble pre code { background: none; padding: 0; }
        .bubble ul, .bubble ol { padding-left: 20px; margin: 6px 0; }
        .bubble li { margin-bottom: 4px; }
        .bubble h1, .bubble h2, .bubble h3 {
            color: #f8fafc;
            margin: 12px 0 6px;
        }
        .bubble h1 { font-size: 18px; }
        .bubble h2 { font-size: 16px; color: #f97316; }
        .bubble h3 { font-size: 14px; color: #fbbf24; }
        .bubble hr { border: none; border-top: 1px solid #2d3561; margin: 10px 0; }
        .bubble p { margin-bottom: 8px; }
        .bubble table { border-collapse: collapse; width: 100%; margin: 8px 0; font-size: 13px; }
        .bubble td, .bubble th {
            border: 1px solid #2d3561;
            padding: 6px 10px;
        }
        .bubble th { background: #16213e; color: #60a5fa; }
        .bubble tr:nth-child(even) { background: #0f1117; }

        /* Thinking indicator */
        .thinking {
            display: flex; align-items: center; gap: 8px;
            color: #64748b; font-size: 13px; padding: 8px 0;
        }
        .thinking-dots { display: flex; gap: 4px; }
        .thinking-dot {
            width: 6px; height: 6px;
            background: #f97316;
            border-radius: 50%;
            animation: bounce 1.2s infinite;
        }
        .thinking-dot:nth-child(2) { animation-delay: 0.2s; }
        .thinking-dot:nth-child(3) { animation-delay: 0.4s; }
        @keyframes bounce {
            0%, 80%, 100% { transform: scale(0.6); opacity: 0.4; }
            40% { transform: scale(1); opacity: 1; }
        }

        /* Input Area */
        .input-area {
            padding: 16px 24px;
            background: #13151f;
            border-top: 1px solid #1e2236;
            flex-shrink: 0;
        }
        .input-wrapper {
            display: flex;
            gap: 10px;
            background: #1a1d2e;
            border: 1px solid #2d3561;
            border-radius: 12px;
            padding: 8px 12px;
            align-items: flex-end;
            transition: border-color 0.2s;
        }
        .input-wrapper:focus-within { border-color: #f97316; }

        #user-input {
            flex: 1;
            background: none;
            border: none;
            color: #e2e8f0;
            font-size: 14px;
            resize: none;
            max-height: 120px;
            line-height: 1.5;
            outline: none;
            font-family: inherit;
        }
        #user-input::placeholder { color: #475569; }

        .send-btn {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, #f97316, #ea580c);
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: opacity 0.2s;
            flex-shrink: 0;
        }
        .send-btn:hover { opacity: 0.85; }
        .send-btn:disabled { opacity: 0.4; cursor: not-allowed; }

        .input-footer {
            display: flex; justify-content: space-between;
            margin-top: 8px;
            font-size: 11px;
            color: #475569;
        }

        /* Clear button */
        .clear-btn {
            background: none;
            border: 1px solid #2d3561;
            color: #64748b;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .clear-btn:hover { border-color: #f87171; color: #f87171; }

        /* Error bubble */
        .error-bubble {
            background: #3b1f1f;
            border: 1px solid #f87171;
            color: #fca5a5;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
        }

        /* Scrollbar for sidebar */
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-thumb { background: #2d3561; border-radius: 2px; }

        @media (max-width: 768px) {
            .sidebar { display: none; }
        }
    </style>
</head>
<body>

<div class="header">
    <div class="header-icon">📚</div>
    <div>
        <div class="header-title">UPSC 2026 AI Agent</div>
        <div class="header-subtitle">Powered by Claude Opus 4.6 with Adaptive Thinking</div>
    </div>
    <div class="header-badge">🎯 UPSC 2026</div>
</div>

<div class="main">
    <!-- Sidebar -->
    <div class="sidebar">

        <div class="sidebar-section">
            <h3>Quick Actions</h3>
            <button class="quick-btn" onclick="quickAction('Generate 5 MCQs on Indian Polity - Fundamental Rights with detailed explanations.')">
                <span class="btn-icon">📝</span> Practice MCQs
            </button>
            <button class="quick-btn" onclick="quickAction('Create a 12-week study plan for UPSC Prelims 2026. I can study 6 hours per day.')">
                <span class="btn-icon">📅</span> Study Plan Generator
            </button>
            <button class="quick-btn" onclick="quickAction('Analyze the most important current affairs from early 2026 for UPSC relevance.')">
                <span class="btn-icon">📰</span> Current Affairs Analysis
            </button>
            <button class="quick-btn" onclick="quickAction('Give me tips on how to write a high-scoring Mains answer with structure and examples.')">
                <span class="btn-icon">✍️</span> Answer Writing Tips
            </button>
            <button class="quick-btn" onclick="quickAction('Suggest an essay outline on the topic: \"Technology as an enabler of inclusive growth in India\"')">
                <span class="btn-icon">🖊️</span> Essay Outline
            </button>
            <button class="quick-btn" onclick="quickAction('What are the most important topics I should revise in the last 30 days before UPSC Prelims?')">
                <span class="btn-icon">🔥</span> Last-Minute Revision
            </button>
        </div>

        <div class="sidebar-section">
            <h3>Subject Focus</h3>
            <span class="subject-tag tag-polity" onclick="quickAction('Explain the key features of the Indian Constitution and important articles for UPSC.')">⚖️ Polity</span>
            <span class="subject-tag tag-history" onclick="quickAction('What are the most important topics in Modern Indian History for UPSC Prelims and Mains?')">🏛️ History</span>
            <span class="subject-tag tag-geo" onclick="quickAction('Explain important geographical concepts: monsoons, soil types, rivers for UPSC.')">🗺️ Geography</span>
            <span class="subject-tag tag-economy" onclick="quickAction('Explain key economic concepts: GDP, inflation, fiscal deficit important for UPSC.')">💹 Economy</span>
            <span class="subject-tag tag-env" onclick="quickAction('What are the key environment and ecology topics for UPSC - biodiversity, climate change, conservation?')">🌿 Environment</span>
            <span class="subject-tag tag-ethics" onclick="quickAction('Explain the key concepts in GS-IV Ethics: integrity, aptitude, emotional intelligence with examples.')">🧭 Ethics</span>
            <span class="subject-tag tag-science" onclick="quickAction('What are important Science & Technology topics for UPSC - space, defense, biotech, AI?')">🔬 Science & Tech</span>
            <span class="subject-tag tag-ir" onclick="quickAction('What are the key International Relations topics important for UPSC Mains GS-II?')">🌐 Int. Relations</span>
        </div>

        <div class="sidebar-section">
            <h3>Exam Info</h3>
            <div class="stat-row">
                <div class="stat-item">
                    <div class="stat-num" id="days-left">—</div>
                    <div class="stat-label">Days to Prelims</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num" id="msg-count">0</div>
                    <div class="stat-label">Messages</div>
                </div>
            </div>
            <div style="font-size:12px; color:#64748b; margin-top:4px;">
                UPSC CSE 2026 Prelims expected: May/June 2026
            </div>
        </div>

        <div class="sidebar-section">
            <h3>Suggested Questions</h3>
            <button class="quick-btn" onclick="quickAction('What is the difference between Directive Principles of State Policy and Fundamental Rights? Give examples from Constitution.')">
                DPSP vs Fundamental Rights
            </button>
            <button class="quick-btn" onclick="quickAction('Explain the significance of the National Education Policy 2020 for UPSC.')">
                NEP 2020 Analysis
            </button>
            <button class="quick-btn" onclick="quickAction('What is One Nation One Election? Discuss its pros and cons for UPSC Mains.')">
                One Nation One Election
            </button>
        </div>
    </div>

    <!-- Chat Area -->
    <div class="chat-area">
        <div id="messages">
            <div class="welcome-screen" id="welcome">
                <div class="welcome-icon">🏆</div>
                <div class="welcome-title">Your Personal UPSC 2026 Mentor</div>
                <div class="welcome-sub">
                    Powered by Claude Opus 4.6 with adaptive thinking. Ask me anything about UPSC preparation — from concept explanations to MCQ practice to personalized study plans.
                </div>
                <div class="welcome-tips">
                    <div class="tip-card" onclick="quickAction('Generate 5 MCQs on Indian Polity - Fundamental Rights at medium difficulty.')">
                        <div class="tip-icon">📝</div>
                        <div class="tip-text">Practice MCQs on any topic</div>
                    </div>
                    <div class="tip-card" onclick="quickAction('Create a 16-week study plan for UPSC 2026 covering both Prelims and Mains. I can study 8 hours per day. My weak areas are Economy and Ethics.')">
                        <div class="tip-icon">📅</div>
                        <div class="tip-text">Get a personalized study plan</div>
                    </div>
                    <div class="tip-card" onclick="quickAction('Explain the CAA-NRC controversy and its constitutional implications for UPSC.')">
                        <div class="tip-icon">📰</div>
                        <div class="tip-text">Current affairs with UPSC angle</div>
                    </div>
                    <div class="tip-card" onclick="quickAction('Explain the concept of Cooperative Federalism in India with examples. How has it evolved?')">
                        <div class="tip-icon">💡</div>
                        <div class="tip-text">Deep concept explanations</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="input-area">
            <div class="input-wrapper">
                <textarea
                    id="user-input"
                    placeholder="Ask anything about UPSC preparation... (e.g., 'Explain the Preamble of the Constitution')"
                    rows="1"
                    onkeydown="handleKeyDown(event)"
                    oninput="autoResize(this)"
                ></textarea>
                <button class="send-btn" id="send-btn" onclick="sendMessage()">➤</button>
            </div>
            <div class="input-footer">
                <span>Press Enter to send · Shift+Enter for new line</span>
                <button class="clear-btn" onclick="clearChat()">Clear Chat</button>
            </div>
        </div>
    </div>
</div>

<script>
    // ── State ──────────────────────────────────────
    let conversationHistory = [];
    let messageCount = 0;
    let isLoading = false;

    // ── Days to Prelims counter ────────────────────
    function updateDaysLeft() {
        // UPSC Prelims 2026 estimated date (typically late May)
        const prelims2026 = new Date('2026-05-31');
        const today = new Date();
        const diff = Math.ceil((prelims2026 - today) / (1000 * 60 * 60 * 24));
        document.getElementById('days-left').textContent = diff > 0 ? diff : '—';
    }
    updateDaysLeft();

    // ── Markdown parser (lightweight) ─────────────
    function parseMarkdown(text) {
        return text
            // Code blocks
            .replace(/```([\s\S]*?)```/g, '<pre><code>$1</code></pre>')
            // Inline code
            .replace(/`([^`]+)`/g, '<code>$1</code>')
            // Headers
            .replace(/^### (.+)$/gm, '<h3>$1</h3>')
            .replace(/^## (.+)$/gm, '<h2>$1</h2>')
            .replace(/^# (.+)$/gm, '<h1>$1</h1>')
            // Bold
            .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
            // Italic
            .replace(/\*(.+?)\*/g, '<em>$1</em>')
            // Horizontal rule
            .replace(/^---$/gm, '<hr>')
            // Unordered lists
            .replace(/^\s*[-•]\s+(.+)$/gm, '<li>$1</li>')
            .replace(/(<li>[\s\S]*?<\/li>)/g, (m) => `<ul>${m}</ul>`)
            // Numbered lists
            .replace(/^\d+\.\s+(.+)$/gm, '<li>$1</li>')
            // Paragraphs (double newlines)
            .replace(/\n\n/g, '</p><p>')
            // Single newlines
            .replace(/\n/g, '<br>')
            // Wrap in p tags
            .replace(/^(.+)/, '<p>$1</p>');
    }

    // ── Auto-resize textarea ───────────────────────
    function autoResize(el) {
        el.style.height = 'auto';
        el.style.height = Math.min(el.scrollHeight, 120) + 'px';
    }

    // ── Key handler ────────────────────────────────
    function handleKeyDown(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    }

    // ── Quick action ────────────────────────────────
    function quickAction(prompt) {
        document.getElementById('user-input').value = prompt;
        autoResize(document.getElementById('user-input'));
        sendMessage();
    }

    // ── Send message ────────────────────────────────
    async function sendMessage() {
        if (isLoading) return;
        const input = document.getElementById('user-input');
        const text = input.value.trim();
        if (!text) return;

        // Hide welcome screen
        const welcome = document.getElementById('welcome');
        if (welcome) welcome.remove();

        isLoading = true;
        document.getElementById('send-btn').disabled = true;
        input.value = '';
        input.style.height = 'auto';

        // Add user message
        appendMessage('user', text);
        conversationHistory.push({ role: 'user', content: text });

        // Show thinking indicator
        const thinkingId = 'thinking-' + Date.now();
        appendThinking(thinkingId);

        try {
            const response = await fetch('upsc_chat.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    message: text,
                    history: conversationHistory.slice(0, -1) // exclude last user msg (sent separately)
                })
            });

            const data = await response.json();
            removeThinking(thinkingId);

            if (data.error) {
                appendError(data.error);
            } else {
                appendMessage('assistant', data.reply);
                conversationHistory.push({ role: 'assistant', content: data.reply });
                messageCount++;
                document.getElementById('msg-count').textContent = messageCount;
            }
        } catch (err) {
            removeThinking(thinkingId);
            appendError('Network error: ' + err.message);
        } finally {
            isLoading = false;
            document.getElementById('send-btn').disabled = false;
            input.focus();
        }
    }

    // ── Append message to chat ─────────────────────
    function appendMessage(role, content) {
        const container = document.getElementById('messages');
        const div = document.createElement('div');
        div.className = `message ${role}`;

        const avatar = role === 'user'
            ? '<div class="avatar user-avatar">👤</div>'
            : '<div class="avatar ai-avatar">🎓</div>';

        const bubbleContent = role === 'assistant'
            ? parseMarkdown(content)
            : escapeHtml(content).replace(/\n/g, '<br>');

        div.innerHTML = `${avatar}<div class="bubble">${bubbleContent}</div>`;
        if (role === 'user') div.style.flexDirection = 'row-reverse';
        container.appendChild(div);
        scrollToBottom();
    }

    function appendError(msg) {
        const container = document.getElementById('messages');
        const div = document.createElement('div');
        div.className = 'message assistant';
        div.innerHTML = `<div class="avatar ai-avatar">⚠️</div><div class="error-bubble">${escapeHtml(msg)}</div>`;
        container.appendChild(div);
        scrollToBottom();
    }

    function appendThinking(id) {
        const container = document.getElementById('messages');
        const div = document.createElement('div');
        div.id = id;
        div.className = 'thinking';
        div.innerHTML = `
            <div class="avatar ai-avatar" style="width:34px;height:34px;border-radius:8px;background:linear-gradient(135deg,#f97316,#ea580c);display:flex;align-items:center;justify-content:center;font-size:16px;">🎓</div>
            <div class="thinking-dots">
                <div class="thinking-dot"></div>
                <div class="thinking-dot"></div>
                <div class="thinking-dot"></div>
            </div>
            <span>Thinking...</span>
        `;
        container.appendChild(div);
        scrollToBottom();
    }

    function removeThinking(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    function escapeHtml(text) {
        return text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function scrollToBottom() {
        const container = document.getElementById('messages');
        container.scrollTop = container.scrollHeight;
    }

    // ── Clear chat ──────────────────────────────────
    function clearChat() {
        conversationHistory = [];
        messageCount = 0;
        document.getElementById('msg-count').textContent = '0';
        const container = document.getElementById('messages');
        container.innerHTML = `
            <div class="welcome-screen" id="welcome">
                <div class="welcome-icon">🏆</div>
                <div class="welcome-title">Chat cleared. Ready to help!</div>
                <div class="welcome-sub">Ask me anything about UPSC 2026 preparation.</div>
            </div>
        `;
    }

    // Focus input on load
    document.getElementById('user-input').focus();
</script>
</body>
</html>
