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
    from { opacity: 1; transform: translateY(0); }
    to   { opacity: 0; transform: translateY(10px); }
}
.chat-toast {
    position: fixed; bottom: 24px; right: 24px; z-index: 9999;
    background: #fff; border-radius: 14px; padding: 14px 18px;
    box-shadow: 0 8px 32px rgba(105,108,255,.22);
    border-left: 4px solid #696cff; min-width: 270px; max-width: 340px;
    display: flex; align-items: center; gap: 12px;
    animation: toastIn .3s cubic-bezier(.34,1.56,.64,1);
    cursor: pointer;
}
.chat-toast-ava {
    width: 38px; height: 38px; border-radius: 50%; flex-shrink: 0;
    background: linear-gradient(135deg, #696cff, #9c9eff);
    display: flex; align-items: center; justify-content: center;
    font-size: 15px; font-weight: 800; color: #fff;
}
.chat-toast-title  { font-weight: 700; font-size: 13px; color: #1e293b; }
.chat-toast-body   { font-size: 12px; color: #8592a3; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 200px; }
.notify-btn {
    position: fixed; bottom: 24px; left: 24px; z-index: 9998;
    background: #696cff; color: #fff; border: none; border-radius: 30px;
    padding: 8px 18px; font-size: 12px; font-weight: 700; cursor: pointer;
    box-shadow: 0 4px 16px rgba(105,108,255,.35);
    display: none; align-items: center; gap: 6px;
}

.chat-wrap {
    display: flex;
    flex-direction: column;
    height: calc(100vh - 130px);
    min-height: 500px;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 4px 32px rgba(105,108,255,.13);
    overflow: hidden;
}

/* Chat header */
.chat-header {
    padding: 16px 24px;
    border-bottom: 1px solid #f0eef8;
    display: flex; align-items: center; gap: 14px;
    background: #fff; flex-shrink: 0;
}
.chat-header-ava {
    width: 44px; height: 44px; border-radius: 50%;
    background: linear-gradient(135deg, #696cff, #9c9eff);
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; font-weight: 800; color: #fff; flex-shrink: 0;
}
.chat-header-name  { font-size: 16px; font-weight: 800; color: #1e293b; }
.chat-header-status { font-size: 12px; color: #28c76f; font-weight: 600; }
.chat-header-lock {
    margin-left: auto;
    display: flex; align-items: center; gap: 6px;
    background: #e8f8ef; color: #28c76f;
    border-radius: 30px; padding: 5px 14px;
    font-size: 12px; font-weight: 700;
}

/* Messages area */
.chat-messages {
    flex: 1; overflow-y: auto;
    padding: 24px 24px 16px;
    display: flex; flex-direction: column; gap: 10px;
    background: #f9faff;
}
.chat-messages::-webkit-scrollbar { width: 4px; }
.chat-messages::-webkit-scrollbar-thumb { background: #dde0f5; border-radius: 4px; }

/* Empty chat */
.chat-empty {
    flex: 1; display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    color: #aab0be; gap: 12px;
}
.chat-empty i { font-size: 56px; color: #dde0f5; }
.chat-empty h6 { font-weight: 700; color: #8592a3; margin: 0; }
.chat-empty p  { font-size: 13px; margin: 0; }

/* Message bubbles */
.msg-row {
    display: flex; align-items: flex-end; gap: 8px;
}
.msg-row.sent  { flex-direction: row-reverse; }
.msg-row.recv  { flex-direction: row; }

.msg-ava {
    width: 30px; height: 30px; border-radius: 50%; flex-shrink: 0;
    background: linear-gradient(135deg, #696cff, #9c9eff);
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 800; color: #fff;
}

/* wrapper keeps max-width so bubble text doesn't break per-character */
.msg-wrap {
    max-width: min(65%, 420px);
    min-width: 0;
}

.msg-bubble {
    display: inline-block;
    width: 100%;
    padding: 10px 14px;
    border-radius: 18px; font-size: 14px; line-height: 1.5;
    word-break: break-word;
    box-sizing: border-box;
}
.msg-row.sent .msg-bubble {
    background: linear-gradient(135deg, #696cff, #8a8dff);
    color: #fff;
    border-bottom-right-radius: 4px;
}
.msg-row.recv .msg-bubble {
    background: #fff;
    color: #1e293b;
    border-bottom-left-radius: 4px;
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
}
.msg-time {
    font-size: 10px; margin-top: 4px;
    text-align: right;
}
.msg-row.sent  .msg-time { color: rgba(255,255,255,.7); }
.msg-row.recv  .msg-time { color: #aab0be; text-align: left; }

/* Input area */
.chat-input-area {
    padding: 14px 20px;
    border-top: 1px solid #f0eef8;
    display: flex; align-items: center; gap: 12px;
    background: #fff; flex-shrink: 0;
}
.chat-input {
    flex: 1; border: 1.5px solid #e4e4eb; border-radius: 24px;
    padding: 11px 18px; font-size: 14px; background: #fafbff;
    outline: none; color: #1e293b; resize: none; max-height: 120px;
    transition: border-color .2s;
    font-family: inherit;
}
.chat-input:focus { border-color: #696cff; background: #fff; }
.btn-send {
    width: 44px; height: 44px; border-radius: 50%; border: none;
    background: linear-gradient(135deg, #696cff, #9c9eff);
    color: #fff; font-size: 18px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: .2s ease;
}
.btn-send:hover   { transform: scale(1.08); }
.btn-send:active  { transform: scale(.96); }
.btn-send:disabled { opacity: .5; cursor: not-allowed; }

/* No receiver selected */
.no-conversation {
    flex: 1; display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    color: #aab0be; gap: 14px;
}
.no-conversation i { font-size: 64px; color: #dde0f5; }
.no-conversation h5 { font-weight: 800; color: #8592a3; margin: 0; }
.no-conversation p  { font-size: 13px; margin: 0; }
</style>
@endsection

@section('content')

<button id="notif-btn" class="notify-btn" onclick="enableNotifications()">
    <i class="ri ri-notification-line"></i> تفعيل الإشعارات
</button>

<div id="chat-data"
     data-auth="{{ auth('users')->id() }}"
     data-receiver="{{ isset($receiver) ? $receiver->id : '' }}"
     style="display:none;"></div>

<div class="chat-wrap">

    @if(isset($receiver))

    {{-- Header --}}
    <div class="chat-header">
        <div class="chat-header-ava">
            {{ strtoupper(substr($receiver->name, 0, 1)) }}
        </div>
        <div>
            <div class="chat-header-name">{{ $receiver->name }}</div>
            <div class="chat-header-status">
                <span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:#28c76f;margin-right:4px;"></span>
                Online
            </div>
        </div>
        <div class="chat-header-lock">
            <i class="ri ri-lock-2-line"></i> Private conversation
        </div>
    </div>

    {{-- Messages --}}
    <div class="chat-messages" id="messagesContainer">
        @forelse($messages as $msg)
            @php
                $isSent = $msg->sender_id === auth('users')->id();
                $initial = strtoupper(substr($msg->sender->name ?? '?', 0, 1));
            @endphp
            <div class="msg-row {{ $isSent ? 'sent' : 'recv' }}" id="msg-{{ $msg->id }}">
                @if(!$isSent)
                    <div class="msg-ava">{{ $initial }}</div>
                @endif
                <div class="msg-wrap">
                    <div class="msg-bubble">{{ $msg->body }}</div>
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
        <textarea id="msgInput" class="chat-input" placeholder="Type a message..." rows="1"
                  onkeydown="handleKey(event)"></textarea>
        <button class="btn-send" id="sendBtn" onclick="sendMessage()">
            <i class="ri ri-send-plane-fill"></i>
        </button>
    </div>

    @else

    <div class="no-conversation">
        <i class="ri ri-message-3-line"></i>
        <h5>No conversation selected</h5>
        <p>Go to <code>/chat/{userId}</code> to open a conversation</p>
    </div>

    @endif

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
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
}

function appendMessage(data, isSent) {
    const container = document.getElementById('messagesContainer');
    if (!container) return;

    const empty = container.querySelector('.chat-empty');
    if (empty) empty.remove();

    const initial     = (data.sender_name || '?').charAt(0).toUpperCase();
    const authInitial = '{{ strtoupper(substr(auth("users")->user()?->name ?? "U", 0, 1)) }}';

    const row = document.createElement('div');
    row.className = 'msg-row ' + (isSent ? 'sent' : 'recv');
    row.id = 'msg-' + (data.id || Date.now());

    if (isSent) {
        row.innerHTML = `
            <div class="msg-wrap">
                <div class="msg-bubble">${escHtml(data.body)}</div>
                <div class="msg-time">${data.created_at}</div>
            </div>
            <div class="msg-ava" style="background:linear-gradient(135deg,#28c76f,#48da89);">${authInitial}</div>`;
    } else {
        row.innerHTML = `
            <div class="msg-ava">${escHtml(initial)}</div>
            <div class="msg-wrap">
                <div class="msg-bubble">${escHtml(data.body)}</div>
                <div class="msg-time">${data.created_at}</div>
            </div>`;
    }

    container.appendChild(row);
    scrollBottom();
}

function escHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

async function sendMessage() {
    if (!RECEIVER_ID) return;
    const body = (input?.value || '').trim();
    if (!body) return;

    const btn = document.getElementById('sendBtn');
    if (btn) btn.disabled = true;
    if (input) { input.value = ''; input.style.height = 'auto'; }

    try {
        const res = await fetch(`/chat/${RECEIVER_ID}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({ body })
        });
        const data = await res.json();
        appendMessage(data, true);
    } catch (err) {
        console.error('Send failed:', err);
    } finally {
        if (btn) btn.disabled = false;
        if (input) input.focus();
    }
}

// ── Notifications ────────────────────────────────────────────
function requestNotifPermission() {
    if (!('Notification' in window)) return;
    if (Notification.permission === 'default') {
        const btn = document.getElementById('notif-btn');
        if (btn) btn.style.display = 'flex';
    }
}

function enableNotifications() {
    Notification.requestPermission().then(p => {
        const btn = document.getElementById('notif-btn');
        if (btn) btn.style.display = 'none';
    });
}

function showBrowserNotification(title, body) {
    if ('Notification' in window && Notification.permission === 'granted') {
        const n = new Notification(title, { body, icon: '/favicon.ico' });
        n.onclick = () => { window.focus(); n.close(); };
    }
}

function showToast(senderName, msgBody) {
    const old = document.getElementById('chat-toast-el');
    if (old) { old.remove(); }

    const toast = document.createElement('div');
    toast.id = 'chat-toast-el';
    toast.className = 'chat-toast';
    toast.onclick = () => toast.remove();
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

requestNotifPermission();

function initEchoChannel() {
    console.log('[Chat] initEchoChannel called — RECEIVER_ID:', RECEIVER_ID, '| window.Echo:', !!window.Echo);
    if (!RECEIVER_ID || !window.Echo) return;

    const ids = [AUTH_ID, RECEIVER_ID].sort((a, b) => a - b);
    const channelName = `chat.${ids[0]}.${ids[1]}`;
    console.log('[Chat] subscribing to private channel:', channelName);

    window.Echo.private(channelName)
        .listen('.message.sent', data => {
            console.log('[Chat] message received via Echo:', data);
            if (parseInt(data.sender_id) !== AUTH_ID) {
                appendMessage(data, false);

                const preview = data.body.length > 70 ? data.body.substring(0, 70) + '…' : data.body;

                if (document.hidden) {
                    showBrowserNotification('رسالة جديدة من ' + data.sender_name, preview);
                }

                showToast(data.sender_name, data.body);
            }
        })
        .error(err => {
            console.error('[Chat] channel auth/subscription error:', err);
        });
}

// Echo is loaded as a deferred ES module, so we wait for it
if (window.Echo) {
    initEchoChannel();
} else {
    window.addEventListener('load', () => {
        setTimeout(initEchoChannel, 300);
    });
}
</script>
@endpush
