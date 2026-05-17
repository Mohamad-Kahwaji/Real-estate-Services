@extends('layouts/contentNavbarLayout')

@section('title', isset($receiver) ? 'Chat with ' . $receiver->name : 'Messages')

@section('page-style')
<style>
* { box-sizing: border-box; }

@keyframes toastIn {
    from { opacity: 0; transform: translateY(20px) scale(.95); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}
@keyframes toastOut {
    from { opacity: 1; } to { opacity: 0; transform: translateY(10px); }
}
.chat-toast {
    position: fixed; bottom: 24px; right: 24px; z-index: 9999;
    background: #fff; border-radius: 14px; padding: 14px 18px;
    box-shadow: 0 8px 32px rgba(105,108,255,.22);
    border-left: 4px solid #696cff; min-width: 270px; max-width: 340px;
    display: flex; align-items: center; gap: 12px;
    animation: toastIn .3s cubic-bezier(.34,1.56,.64,1); cursor: pointer;
}
.chat-toast-ava {
    width: 38px; height: 38px; border-radius: 50%; flex-shrink: 0;
    background: linear-gradient(135deg,#696cff,#9c9eff);
    display: flex; align-items: center; justify-content: center;
    font-size: 15px; font-weight: 800; color: #fff;
}
.chat-toast-title { font-weight: 700; font-size: 13px; color: #1e293b; }
.chat-toast-body  { font-size: 12px; color: #8592a3; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 200px; }

/* ── Shell ── */
.chat-shell {
    display: flex;
    height: calc(100vh - 130px);
    min-height: 500px;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 4px 32px rgba(105,108,255,.13);
    overflow: hidden;
}

/* ── Sidebar ── */
.chat-sidebar {
    width: 280px;
    flex-shrink: 0;
    border-right: 1px solid #f0eef8;
    display: flex;
    flex-direction: column;
    background: #faf9fd;
}
.sidebar-hdr {
    padding: 18px 18px 10px;
    font-size: 15px;
    font-weight: 800;
    color: #312d4b;
    border-bottom: 1px solid #f0eef8;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.sidebar-hdr span { color: #97939e; font-size: 12px; font-weight: 600; }
.sidebar-list { flex: 1; overflow-y: auto; padding: 8px 0; }
.sidebar-list::-webkit-scrollbar { width: 3px; }
.sidebar-list::-webkit-scrollbar-thumb { background: #dde0f5; border-radius: 3px; }

.conv-item {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 16px; cursor: pointer;
    text-decoration: none; transition: background .15s;
    position: relative;
    border-left: 3px solid transparent;
}
.conv-item:hover { background: #f0eeff; text-decoration: none; }
.conv-item.active {
    background: #eef0ff;
    border-left-color: #696cff;
}
.conv-ava {
    width: 42px; height: 42px; border-radius: 50%; flex-shrink: 0;
    background: linear-gradient(135deg,#696cff,#9c9eff);
    display: flex; align-items: center; justify-content: center;
    font-size: 15px; font-weight: 800; color: #fff;
    position: relative;
}
.conv-ava.has-unread::after {
    content: '';
    position: absolute; top: 1px; right: 1px;
    width: 11px; height: 11px;
    background: #ea5455;
    border-radius: 50%;
    border: 2px solid #faf9fd;
}
.conv-info { flex: 1; min-width: 0; }
.conv-name {
    font-size: 13px; font-weight: 700; color: #312d4b;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.conv-preview {
    font-size: 11px; color: #97939e;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    margin-top: 2px;
}
.conv-preview.unread-preview { color: #696cff; font-weight: 700; }
.conv-meta { display: flex; flex-direction: column; align-items: flex-end; gap: 4px; flex-shrink: 0; }
.conv-time { font-size: 10px; color: #b0aab8; }
.conv-badge {
    background: #ea5455; color: #fff;
    border-radius: 20px; padding: 1px 7px;
    font-size: 10px; font-weight: 800;
    min-width: 18px; text-align: center;
}

.sidebar-empty {
    padding: 40px 16px; text-align: center; color: #b0aab8;
}
.sidebar-empty i { font-size: 36px; display: block; margin-bottom: 8px; color: #dde0f5; }
.sidebar-empty p { font-size: 12px; margin: 0; }

/* ── Main area ── */
.chat-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-width: 0;
}

/* Chat header */
.chat-header {
    padding: 14px 20px;
    border-bottom: 1px solid #f0eef8;
    display: flex; align-items: center; gap: 14px;
    background: #fff; flex-shrink: 0;
}
.chat-header-ava {
    width: 42px; height: 42px; border-radius: 50%;
    background: linear-gradient(135deg,#696cff,#9c9eff);
    display: flex; align-items: center; justify-content: center;
    font-size: 15px; font-weight: 800; color: #fff; flex-shrink: 0;
}
.chat-header-name   { font-size: 15px; font-weight: 800; color: #1e293b; }
.chat-header-status { font-size: 12px; color: #28c76f; font-weight: 600; }
.chat-header-lock {
    margin-left: auto;
    display: flex; align-items: center; gap: 6px;
    background: #e8f8ef; color: #28c76f;
    border-radius: 30px; padding: 5px 14px;
    font-size: 12px; font-weight: 700;
}

/* Messages */
.chat-messages {
    flex: 1; overflow-y: auto;
    padding: 20px 20px 12px;
    display: flex; flex-direction: column; gap: 10px;
    background: #f9faff;
}
.chat-messages::-webkit-scrollbar { width: 4px; }
.chat-messages::-webkit-scrollbar-thumb { background: #dde0f5; border-radius: 4px; }

.chat-empty {
    flex: 1; display: flex; flex-direction: column;
    align-items: center; justify-content: center; color: #aab0be; gap: 12px;
}
.chat-empty i { font-size: 52px; color: #dde0f5; }
.chat-empty h6 { font-weight: 700; color: #8592a3; margin: 0; }
.chat-empty p  { font-size: 13px; margin: 0; }

/* Bubbles */
.msg-row { display: flex; align-items: flex-end; gap: 8px; }
.msg-row.sent { flex-direction: row-reverse; }
.msg-ava {
    width: 28px; height: 28px; border-radius: 50%; flex-shrink: 0;
    background: linear-gradient(135deg,#696cff,#9c9eff);
    display: flex; align-items: center; justify-content: center;
    font-size: 10px; font-weight: 800; color: #fff;
}
.msg-wrap { max-width: min(65%,420px); min-width: 0; }
.msg-bubble {
    display: inline-block; width: 100%;
    padding: 10px 14px; border-radius: 18px;
    font-size: 14px; line-height: 1.5; word-break: break-word;
}
.msg-row.sent .msg-bubble {
    background: linear-gradient(135deg,#696cff,#8a8dff);
    color: #fff; border-bottom-right-radius: 4px;
}
.msg-row.recv .msg-bubble {
    background: #fff; color: #1e293b;
    border-bottom-left-radius: 4px;
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
}
.msg-time { font-size: 10px; margin-top: 4px; }
.msg-row.sent .msg-time  { color: rgba(255,255,255,.7); text-align: right; }
.msg-row.recv .msg-time  { color: #aab0be; }

/* Input */
.chat-input-area {
    padding: 10px 14px; border-top: 1px solid #f0eef8;
    background: #fff; flex-shrink: 0;
}
.chat-input-row {
    display: flex; align-items: flex-end; gap: 10px;
}
.chat-input {
    flex: 1; border: 1.5px solid #e4e4eb; border-radius: 20px;
    padding: 10px 16px; font-size: 14px; background: #fafbff;
    outline: none; color: #1e293b; resize: none; max-height: 120px;
    transition: border-color .2s; font-family: inherit;
}
.chat-input:focus { border-color: #696cff; background: #fff; }

.btn-attach {
    width: 40px; height: 40px; border-radius: 50%; border: none;
    background: #f0f0ff; color: #696cff; font-size: 18px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: .2s;
}
.btn-attach:hover { background: #696cff; color: #fff; }

.btn-send {
    width: 42px; height: 42px; border-radius: 50%; border: none;
    background: linear-gradient(135deg,#696cff,#9c9eff);
    color: #fff; font-size: 18px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: .2s;
}
.btn-send:hover  { transform: scale(1.08); }
.btn-send:active { transform: scale(.96); }
.btn-send:disabled { opacity: .5; cursor: not-allowed; }

/* File preview strip */
.file-preview-strip {
    display: none; align-items: center; gap: 10px;
    background: #f4f5ff; border-radius: 12px; padding: 8px 12px;
    margin-bottom: 8px; font-size: 13px; position: relative;
}
.file-preview-strip img {
    height: 52px; width: 52px; object-fit: cover; border-radius: 8px; flex-shrink: 0;
}
.file-preview-strip .file-icon {
    width: 52px; height: 52px; border-radius: 8px;
    background: #eef0ff; display: flex; align-items: center; justify-content: center;
    font-size: 24px; flex-shrink: 0;
}
.file-preview-strip .file-name {
    flex: 1; font-weight: 600; color: #312d4b; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
.btn-clear-file {
    width: 26px; height: 26px; border-radius: 50%; border: none;
    background: #ea5455; color: #fff; font-size: 14px; cursor: pointer;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}

/* File/image in bubble */
.msg-image { max-width: 240px; border-radius: 12px; display: block; margin-bottom: 4px; cursor: pointer; }
.msg-file-card {
    display: flex; align-items: center; gap: 10px;
    background: rgba(255,255,255,.15); border-radius: 10px; padding: 8px 12px;
    margin-bottom: 4px; text-decoration: none; color: inherit;
}
.msg-row.recv .msg-file-card { background: #f0f0ff; }
.msg-file-icon { font-size: 24px; flex-shrink: 0; }
.msg-file-name { font-size: 12px; font-weight: 700; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 160px; }

/* No conversation selected */
.no-conversation {
    flex: 1; display: flex; flex-direction: column;
    align-items: center; justify-content: center; color: #aab0be; gap: 14px;
}
.no-conversation i  { font-size: 64px; color: #dde0f5; }
.no-conversation h5 { font-weight: 800; color: #8592a3; margin: 0; }
.no-conversation p  { font-size: 13px; margin: 0; }

/* Responsive */
@media (max-width: 640px) {
    .chat-sidebar { width: 64px; }
    .conv-info, .conv-meta, .sidebar-hdr span, .sidebar-hdr .sidebar-title { display: none; }
    .conv-item { padding: 10px; justify-content: center; }
    .sidebar-hdr { justify-content: center; padding: 14px 8px; }
}
</style>
@endsection

@section('content')

<div id="chat-data"
     data-auth="{{ auth('users')->id() }}"
     data-receiver="{{ isset($receiver) ? $receiver->id : '' }}"
     style="display:none;"></div>

<div class="chat-shell">

    {{-- ══ SIDEBAR ══ --}}
    <aside class="chat-sidebar">
        <div class="sidebar-hdr">
            <span class="sidebar-title">Messages</span>
            <span>{{ $conversations->count() }} chats</span>
        </div>
        <div class="sidebar-list">
            @forelse($conversations as $conv)
                @php
                    $partner   = $conv->user;
                    $initial   = strtoupper(substr($partner->name, 0, 1));
                    $isActive  = isset($receiver) && $receiver->id === $partner->id;
                    $hasUnread = $conv->unread > 0;
                    $lastBody  = $conv->last_message ? \Illuminate\Support\Str::limit($conv->last_message->body, 35) : 'No messages yet';
                    $lastTime  = $conv->last_message ? $conv->last_message->created_at->format('H:i') : '';
                @endphp
                <a href="{{ route('chat.show', $partner->id) }}"
                   class="conv-item {{ $isActive ? 'active' : '' }}">
                    <div class="conv-ava {{ $hasUnread ? 'has-unread' : '' }}" style="{{ $partner->isOnline() ? 'box-shadow:0 0 0 2.5px #28c76f;' : '' }}">
                        {{ $initial }}
                        @if($partner->isOnline())
                            <span style="position:absolute;bottom:1px;right:1px;width:10px;height:10px;background:#28c76f;border-radius:50%;border:2px solid #faf9fd;"></span>
                        @endif
                    </div>
                    <div class="conv-info">
                        <div class="conv-name">{{ $partner->name }}</div>
                        <div class="conv-preview {{ $hasUnread ? 'unread-preview' : '' }}">
                            {{ $lastBody }}
                        </div>
                    </div>
                    <div class="conv-meta">
                        <span class="conv-time">{{ $lastTime }}</span>
                        @if($hasUnread)
                            <span class="conv-badge">{{ $conv->unread > 99 ? '99+' : $conv->unread }}</span>
                        @endif
                    </div>
                </a>
            @empty
                <div class="sidebar-empty">
                    <i class="ri ri-message-3-line"></i>
                    <p>No conversations yet</p>
                </div>
            @endforelse
        </div>
    </aside>

    {{-- ══ MAIN ══ --}}
    <div class="chat-main">

        @if(isset($receiver))

        {{-- Header --}}
        <div class="chat-header">
            <div class="chat-header-ava">
                {{ strtoupper(substr($receiver->name, 0, 1)) }}
            </div>
            <div>
                <div class="chat-header-name">{{ $receiver->name }}</div>
                <div class="chat-header-status" style="color:{{ $receiver->isOnline() ? '#28c76f' : '#97939e' }};">
                    <span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:{{ $receiver->isOnline() ? '#28c76f' : '#b0aab8' }};margin-right:4px;"></span>
                    {{ $receiver->lastSeenLabel() }}
                </div>
            </div>
            <div class="chat-header-lock">
                <i class="ri ri-lock-2-line"></i> Private
            </div>
        </div>

        {{-- Messages --}}
        <div class="chat-messages" id="messagesContainer">
            @forelse($messages as $msg)
                @php
                    $isSent  = $msg->sender_id === auth('users')->id();
                    $initial = strtoupper(substr($msg->sender->name ?? '?', 0, 1));
                @endphp
                <div class="msg-row {{ $isSent ? 'sent' : 'recv' }}" id="msg-{{ $msg->id }}">
                    @if(!$isSent)
                        <div class="msg-ava">{{ $initial }}</div>
                    @endif
                    <div class="msg-wrap">
                        <div class="msg-bubble">
                            @if($msg->file_path)
                                @if($msg->isImage())
                                    <img class="msg-image"
                                         src="{{ $msg->fileUrl() }}"
                                         alt="{{ $msg->file_name }}"
                                         onclick="window.open(this.src,'_blank')">
                                @else
                                    <a class="msg-file-card" href="{{ $msg->fileUrl() }}" download="{{ $msg->file_name }}" target="_blank">
                                        <span class="msg-file-icon">📎</span>
                                        <span class="msg-file-name">{{ $msg->file_name }}</span>
                                        <i class="ri ri-download-line" style="font-size:14px;flex-shrink:0;"></i>
                                    </a>
                                @endif
                            @endif
                            @if($msg->body){{ $msg->body }}@endif
                        </div>
                        <div class="msg-time">{{ $msg->created_at->format('H:i') }}</div>
                    </div>
                    @if($isSent)
                        <div class="msg-ava" style="background:linear-gradient(135deg,#28c76f,#48da89);">
                            {{ strtoupper(substr(auth('users')->user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
            @empty
                <div class="chat-empty">
                    <i class="ri ri-chat-smile-2-line"></i>
                    <h6>No messages yet</h6>
                    <p>Be the first to say hello!</p>
                </div>
            @endforelse
        </div>

        {{-- Input --}}
        <div class="chat-input-area">
            {{-- File preview strip --}}
            <div class="file-preview-strip" id="filePreview">
                <div class="file-icon" id="previewIcon">📎</div>
                <img id="previewImg" src="" alt="" style="display:none;">
                <span class="file-name" id="previewName"></span>
                <button class="btn-clear-file" onclick="clearFile()" title="Remove">✕</button>
            </div>
            {{-- Input row --}}
            <div class="chat-input-row">
                <input type="file" id="fileInput" style="display:none;"
                       accept="image/*,.pdf,.doc,.docx,.xlsx,.zip,.txt"
                       onchange="handleFileSelect(event)">
                <button class="btn-attach" onclick="document.getElementById('fileInput').click()" title="Attach file">
                    <i class="ri ri-attachment-2"></i>
                </button>
                <textarea id="msgInput" class="chat-input" placeholder="Type a message..." rows="1"
                          onkeydown="handleKey(event)"></textarea>
                <button class="btn-send" id="sendBtn" onclick="sendMessage()">
                    <i class="ri ri-send-plane-fill"></i>
                </button>
            </div>
        </div>

        @else

        <div class="no-conversation">
            <i class="ri ri-message-3-line"></i>
            <h5>Select a conversation</h5>
            <p>Choose someone from the sidebar to start chatting</p>
        </div>

        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
const chatData    = document.getElementById('chat-data');
const AUTH_ID     = parseInt(chatData?.dataset.auth || '0');
const RECEIVER_ID = parseInt(chatData?.dataset.receiver || '0');

function scrollBottom() {
    const c = document.getElementById('messagesContainer');
    if (c) c.scrollTop = c.scrollHeight;
}
scrollBottom();

const input = document.getElementById('msgInput');
if (input) {
    input.addEventListener('input', () => {
        input.style.height = 'auto';
        input.style.height = Math.min(input.scrollHeight, 120) + 'px';
    });
}

function handleKey(e) {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
}

function escHtml(str) {
    return String(str)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function buildBubbleContent(data) {
    let html = '';
    if (data.file_url) {
        if (data.file_type === 'image') {
            html += `<img class="msg-image" src="${escHtml(data.file_url)}" alt="${escHtml(data.file_name||'')}" onclick="window.open(this.src,'_blank')">`;
        } else {
            html += `<a class="msg-file-card" href="${escHtml(data.file_url)}" download="${escHtml(data.file_name||'file')}" target="_blank">
                        <span class="msg-file-icon">📎</span>
                        <span class="msg-file-name">${escHtml(data.file_name||'file')}</span>
                        <i class="ri ri-download-line" style="font-size:14px;flex-shrink:0;"></i>
                     </a>`;
        }
    }
    if (data.body) html += escHtml(data.body);
    return html;
}

function appendMessage(data, isSent) {
    const container = document.getElementById('messagesContainer');
    if (!container) return;
    const empty = container.querySelector('.chat-empty');
    if (empty) empty.remove();

    const authInitial = '{{ strtoupper(substr(auth("users")->user()?->name ?? "U", 0, 1)) }}';
    const initial = (data.sender_name || '?').charAt(0).toUpperCase();
    const row = document.createElement('div');
    row.className = 'msg-row ' + (isSent ? 'sent' : 'recv');
    row.id = 'msg-' + (data.id || Date.now());

    const bubbleContent = buildBubbleContent(data);

    if (isSent) {
        row.innerHTML = `
            <div class="msg-wrap">
                <div class="msg-bubble">${bubbleContent}</div>
                <div class="msg-time">${data.created_at}</div>
            </div>
            <div class="msg-ava" style="background:linear-gradient(135deg,#28c76f,#48da89);">${authInitial}</div>`;
    } else {
        row.innerHTML = `
            <div class="msg-ava">${escHtml(initial)}</div>
            <div class="msg-wrap">
                <div class="msg-bubble">${bubbleContent}</div>
                <div class="msg-time">${data.created_at}</div>
            </div>`;
        updateSidebarUnread(data.sender_id);
    }

    container.appendChild(row);
    scrollBottom();
}

// ── File attachment ──────────────────────────────────────────────────────────
let selectedFile = null;
const MAX_BYTES  = 10 * 1024 * 1024; // 10 MB client-side cap

function showChatError(msg) {
    let el = document.getElementById('chat-error-bar');
    if (!el) {
        el = document.createElement('div');
        el.id = 'chat-error-bar';
        el.style.cssText = 'position:fixed;bottom:80px;left:50%;transform:translateX(-50%);background:#ea5455;color:#fff;padding:10px 22px;border-radius:12px;font-size:13px;font-weight:700;z-index:9999;box-shadow:0 4px 16px rgba(234,84,85,.4);';
        document.body.appendChild(el);
    }
    el.textContent = msg;
    el.style.display = 'block';
    setTimeout(() => { if (el) el.style.display = 'none'; }, 4000);
}

function handleFileSelect(event) {
    const file = event.target.files[0];
    if (!file) return;

    if (file.size > MAX_BYTES) {
        showChatError('File too large. Maximum size is 10 MB.');
        event.target.value = '';
        return;
    }

    selectedFile = file;

    const strip   = document.getElementById('filePreview');
    const nameEl  = document.getElementById('previewName');
    const imgEl   = document.getElementById('previewImg');
    const iconEl  = document.getElementById('previewIcon');

    nameEl.textContent = file.name;
    strip.style.display = 'flex';

    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = e => {
            imgEl.src = e.target.result;
            imgEl.style.display = 'block';
            iconEl.style.display = 'none';
        };
        reader.readAsDataURL(file);
    } else {
        imgEl.style.display = 'none';
        iconEl.style.display = 'flex';
        iconEl.textContent = '📎';
    }
}

function clearFile() {
    selectedFile = null;
    document.getElementById('fileInput').value = '';
    document.getElementById('filePreview').style.display = 'none';
    document.getElementById('previewImg').style.display = 'none';
    document.getElementById('previewIcon').style.display = 'flex';
    document.getElementById('previewImg').src = '';
}

// Update the unread badge in the sidebar when a new message arrives
function updateSidebarUnread(senderId) {
    const convItem = document.querySelector(`.conv-item[href*="/chat/${senderId}"]`);
    if (!convItem) return;

    const ava = convItem.querySelector('.conv-ava');
    if (ava && !ava.classList.contains('has-unread')) {
        ava.classList.add('has-unread');
    }

    let badge = convItem.querySelector('.conv-badge');
    if (!badge) {
        const meta = convItem.querySelector('.conv-meta');
        if (meta) {
            badge = document.createElement('span');
            badge.className = 'conv-badge';
            badge.textContent = '1';
            meta.appendChild(badge);
        }
    } else {
        const current = parseInt(badge.textContent) || 0;
        badge.textContent = current >= 99 ? '99+' : (current + 1);
    }

    const preview = convItem.querySelector('.conv-preview');
    if (preview) preview.classList.add('unread-preview');
}

async function sendMessage() {
    if (!RECEIVER_ID) return;
    const body = (input?.value || '').trim();
    if (!body && !selectedFile) return;

    const btn = document.getElementById('sendBtn');
    if (btn) btn.disabled = true;

    const formData = new FormData();
    if (body)         formData.append('body', body);
    if (selectedFile) formData.append('file', selectedFile);

    if (input) { input.value = ''; input.style.height = 'auto'; }
    clearFile();

    try {
        const res = await fetch(`/chat/${RECEIVER_ID}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json'
            },
            body: formData
        });
        if (!res.ok) {
            const err = await res.json().catch(() => ({}));
            showChatError(err.error || err.message || 'Failed to send message.');
            return;
        }
        const data = await res.json();
        appendMessage(data, true);
    } catch (err) {
        console.error('Send failed:', err);
        showChatError('Network error. Please check your connection.');
    } finally {
        if (btn) btn.disabled = false;
        if (input) input.focus();
    }
}

function showToast(senderName, msgBody, senderId) {
    const old = document.getElementById('chat-toast-el');
    if (old) old.remove();
    const toast = document.createElement('div');
    toast.id = 'chat-toast-el';
    toast.className = 'chat-toast';
    toast.onclick = () => { window.location.href = `/chat/${senderId}`; };
    toast.innerHTML = `
        <div class="chat-toast-ava">${escHtml(senderName.charAt(0).toUpperCase())}</div>
        <div>
            <div class="chat-toast-title">${escHtml(senderName)}</div>
            <div class="chat-toast-body">${escHtml(msgBody)}</div>
        </div>`;
    document.body.appendChild(toast);
    setTimeout(() => {
        if (!toast.parentNode) return;
        toast.style.animation = 'toastOut .3s ease forwards';
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}

function initEchoChannel() {
    if (!RECEIVER_ID || !window.Echo) return;
    const ids = [AUTH_ID, RECEIVER_ID].sort((a,b) => a-b);
    window.Echo.private(`chat.${ids[0]}.${ids[1]}`)
        .listen('.message.sent', data => {
            if (parseInt(data.sender_id) !== AUTH_ID) {
                appendMessage(data, false);
                if (document.hidden && 'Notification' in window && Notification.permission === 'granted') {
                    new Notification('New message from ' + data.sender_name, { body: data.body });
                }
                showToast(data.sender_name, data.body, data.sender_id);
            }
        });
}

if (window.Echo) { initEchoChannel(); }
else { window.addEventListener('load', () => setTimeout(initEchoChannel, 300)); }

// ── Online status polling ──────────────────────────────────────────────────
@if(isset($receiver))
(function pollStatus() {
    const statusEl = document.querySelector('.chat-header-status');
    if (!statusEl || !RECEIVER_ID) return;

    async function refresh() {
        try {
            const res  = await fetch(`/user/${RECEIVER_ID}/status`, { headers: { 'Accept': 'application/json' } });
            const data = await res.json();
            if (!statusEl) return;

            const dot   = statusEl.querySelector('span') || statusEl;
            const isOn  = data.is_online;
            const color = isOn ? '#28c76f' : '#b0aab8';

            statusEl.style.color = isOn ? '#28c76f' : '#97939e';
            statusEl.innerHTML =
                `<span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:${color};margin-right:4px;"></span>${data.label}`;

            // Also update sidebar dot for this user
            const sidebarAva = document.querySelector(`.conv-item[href*="/chat/${RECEIVER_ID}"] .conv-ava`);
            if (sidebarAva) {
                const dot = sidebarAva.querySelector('.online-dot');
                if (isOn && !dot) {
                    const d = document.createElement('span');
                    d.className = 'online-dot';
                    d.style.cssText = 'position:absolute;bottom:1px;right:1px;width:10px;height:10px;background:#28c76f;border-radius:50%;border:2px solid #faf9fd;';
                    sidebarAva.appendChild(d);
                    sidebarAva.style.boxShadow = '0 0 0 2.5px #28c76f';
                } else if (!isOn && dot) {
                    dot.remove();
                    sidebarAva.style.boxShadow = '';
                }
            }
        } catch (_) {}
    }

    refresh();
    setInterval(refresh, 30000);
})();
@endif
</script>
@endpush
