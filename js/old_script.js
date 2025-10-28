// script.js — версия с анти-дубликатом и устойчивым SSE

class Messenger {
    constructor() {
        this.user = null;
        this.eventSource = null;
        this.isPageLoaded = false;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 5;
        this.lastMessageId = 0;
        this.receivedMessageIds = new Set(); // фильтр от дубликатов
        this.isMobile = this.checkMobile();
        this.init();
    }

    checkMobile() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }

    async init() {
        window.addEventListener("load", async () => {
            this.isPageLoaded = true;
            this.cacheElements();
            this.setupEvents();
            await this.checkAuth();

            if (this.isMobile) {
                setInterval(() => {
                    if (!this.eventSource || this.eventSource.readyState === EventSource.CLOSED) {
                        console.log("Mobile reconnect...");
                        this.connectSSE();
                    }
                }, 10000);
            }
        });
    }

    cacheElements() {
        this.elements = {
            authModal: document.querySelector(".auth-modal"),
            loginForm: document.getElementById("login-form"),
            registerForm: document.getElementById("register-form"),
            messageInput: document.getElementById("message-input"),
            messagesContainer: document.getElementById("messages-container"),
            sendBtn: document.getElementById("send-btn"),
            userId: document.getElementById("user-id"),
            onlineCount: document.getElementById("online-count"),
            newsContainer: document.getElementById("news-container"),
            adminNewsForm: document.getElementById("admin-news-form"),
            newsText: document.getElementById("news-text"),
            addNewsBtn: document.getElementById("add-news-btn"),
            openNewsFormBtn: document.getElementById("open-news-form-btn"),
            closeNewsFormBtn: document.getElementById("close-news-form-btn"),
            emojiButton: document.getElementById("emoji-button"),
            emojiPicker: document.getElementById("emoji-picker"),
            attachmentButton: document.getElementById("attachment-button")
        };
    }

    setupEvents() {
        console.log("Setting up events...");

        const tabs = document.querySelectorAll(".tab");
        if (tabs.length > 0) {
            tabs.forEach(tab => {
                tab.addEventListener("click", e => {
                    e.preventDefault();
                    const tabName = tab.getAttribute("data-tab");
                    tabs.forEach(t => t.classList.remove("active"));
                    document.querySelectorAll(".auth-form").forEach(f => f.classList.remove("active"));
                    tab.classList.add("active");
                    const targetForm = document.getElementById(`${tabName}-form`);
                    if (targetForm) targetForm.classList.add("active");
                });
            });
        }

        if (this.elements.loginForm)
            this.elements.loginForm.addEventListener("submit", e => this.handleLogin(e));

        if (this.elements.registerForm)
            this.elements.registerForm.addEventListener("submit", e => this.handleRegister(e));

        if (this.elements.sendBtn)
            this.elements.sendBtn.addEventListener("click", () => this.sendMessage());

        if (this.elements.messageInput)
            this.elements.messageInput.addEventListener("keypress", e => {
                if (e.key === "Enter") this.sendMessage();
            });

        if (this.elements.addNewsBtn)
            this.elements.addNewsBtn.addEventListener("click", () => this.addNews());

        if (this.elements.openNewsFormBtn)
            this.elements.openNewsFormBtn.addEventListener("click", () => {
                this.elements.adminNewsForm.style.display = "block";
                if (this.elements.newsText) this.elements.newsText.focus();
            });

        if (this.elements.closeNewsFormBtn)
            this.elements.closeNewsFormBtn.addEventListener("click", () => {
                this.elements.adminNewsForm.style.display = "none";
                if (this.elements.newsText) this.elements.newsText.value = "";
            });

        this.setupEmojiPicker();

        if (this.elements.attachmentButton)
            this.elements.attachmentButton.addEventListener("click", e => {
                e.preventDefault();
                this.handleAttachment();
            });
    }

    async handleLogin(e) {
        e.preventDefault();
        const username = document.getElementById("login-username").value;
        const password = document.getElementById("login-password").value;
        if (!username || !password) return this.showError("Заполните все поля");

        try {
            const response = await fetch("/api/login.php", {
                method: "POST",
                headers: { "Content-Type": "application/json", "Accept": "application/json" },
                body: JSON.stringify({ username, password })
            });
            const data = await response.json();
            if (!response.ok) throw new Error(data.error || "Ошибка входа");
            await this.startSession(data);
        } catch (error) {
            this.showError(error.message);
        }
    }

    async handleRegister(e) {
        e.preventDefault();
        const username = document.getElementById("register-username").value;
        const password = document.getElementById("register-password").value;
        const confirmPassword = document.getElementById("confirm-password").value;
        if (password !== confirmPassword) return this.showError("Пароли не совпадают");

        try {
            const keyPair = await window.generateKeys();
            const publicKey = await crypto.subtle.exportKey("jwk", keyPair.publicKey);
            const response = await fetch("/api/register.php", {
                method: "POST",
                headers: { "Content-Type": "application/json", "Accept": "application/json" },
                body: JSON.stringify({ username, password, publicKey })
            });
            const data = await response.json();
            if (!response.ok) throw new Error(data.error || "Ошибка регистрации");
            localStorage.setItem("privateKey", JSON.stringify(await crypto.subtle.exportKey("jwk", keyPair.privateKey)));
            localStorage.setItem("username", username);
            await this.startSession(data);
        } catch (error) {
            this.showError("Ошибка регистрации: " + error.message);
        }
    }

    async startSession(authData) {
        this.user = { id: authData.userId, username: authData.username };
        localStorage.setItem("username", authData.username);
        if (this.elements.userId) this.elements.userId.textContent = authData.userId.toString().slice(-4);
        if (this.elements.authModal) this.elements.authModal.style.display = "none";
        await this.loadMessages();
        await this.loadNews();
        this.connectSSE();
        this.checkAdminStatus();
        this.showConnectionStatus("connected", "Подключено к чату");
    }

    connectSSE() {
        if (this.eventSource) this.eventSource.close();
        this.reconnectAttempts++;
        if (this.reconnectAttempts > this.maxReconnectAttempts) {
            this.showConnectionStatus("error", "Нет подключения к чату. Перезагрузите страницу.");
            return;
        }

        const url = `/api/sse.php?last_id=${this.lastMessageId}&t=${Date.now()}`;
        console.log("Connecting to SSE:", url);

        try {
            this.eventSource = new EventSource(url, { withCredentials: true });

            this.eventSource.onopen = () => {
                console.log("SSE connected");
                this.reconnectAttempts = 0;
                this.showConnectionStatus("connected", "Подключено");
            };

            this.eventSource.onmessage = event => {
                if (event.data.trim().startsWith(": ping")) return;
                try {
                    const data = JSON.parse(event.data);
                    if (data.id && this.receivedMessageIds.has(data.id)) return;
                    if (data.id) this.receivedMessageIds.add(data.id);
                    this.handleSSEData(data);
                    if (data.id && data.id > this.lastMessageId) this.lastMessageId = data.id;
                } catch (err) {
                    console.warn("SSE parse error", err);
                }
            };

            this.eventSource.onerror = () => {
                console.warn("SSE connection lost, retrying...");
                this.showConnectionStatus("connecting", "Переподключение...");
                this.eventSource.close();
                setTimeout(() => this.connectSSE(), 3000);
            };
        } catch (e) {
            console.error("SSE failed:", e);
            setTimeout(() => this.connectSSE(), 5000);
        }
    }

    handleSSEData(data) {
        switch (data.type) {
            case "new_message":
                this.displayMessage({
                    sender: data.sender,
                    content: data.content,
                    timestamp: new Date(data.timestamp),
                    isOwn: data.sender === this.user?.username
                });
                break;
            case "news_update":
                this.loadNews();
                break;
            case "online_count":
                if (this.elements.onlineCount) this.elements.onlineCount.textContent = data.count;
                break;
        }
    }

    async sendMessage() {
        const text = this.elements.messageInput.value.trim();
        if (!text) return;
        try {
            const response = await fetch("/api/messages.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ content: text })
            });
            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.error || "Ошибка отправки");
            }
            this.elements.messageInput.value = "";
        } catch (err) {
            this.showError("Ошибка отправки сообщения");
        }
    }

    async loadMessages() {
        try {
            const response = await fetch("/api/messages.php");
            if (!response.ok) throw new Error("Failed to load messages");
            const data = await response.json();
            this.elements.messagesContainer.innerHTML = "";
            data.messages.forEach(msg => {
                this.displayMessage({
                    sender: msg.sender,
                    content: msg.content,
                    timestamp: new Date(msg.timestamp),
                    isOwn: msg.sender === this.user?.username
                }, false);
                if (msg.id > this.lastMessageId) this.lastMessageId = msg.id;
                this.receivedMessageIds.add(msg.id);
            });
            this.scrollToBottom();
        } catch (err) {
            this.showError("Ошибка загрузки сообщений");
        }
    }

    displayMessage(message, animate = true) {
        if (!this.isPageLoaded) return;
        const messageEl = document.createElement("div");
        messageEl.className = `message ${message.isOwn ? "own" : ""}`;
        const avatarLetter = message.sender.charAt(0).toUpperCase();
        const isAdmin = message.sender === "admin";
        const timeString = message.timestamp.toLocaleTimeString("ru-RU", { hour: "2-digit", minute: "2-digit" });
        messageEl.innerHTML = `
            <div class="message-avatar">${avatarLetter}</div>
            <div class="message-content">
                <div class="sender">
                    ${this.sanitize(message.sender)}
                    ${isAdmin ? '<span class="sender-badge">АДМИН</span>' : ""}
                </div>
                <div class="content">${this.sanitize(message.content)}</div>
                <div class="time">${timeString} ${message.isOwn ? '<i class="fas fa-check read-indicator"></i>' : ""}</div>
            </div>
        `;
        if (animate) {
            messageEl.classList.add("new-message");
            setTimeout(() => messageEl.classList.remove("new-message"), 1000);
        }
        this.elements.messagesContainer.appendChild(messageEl);
        this.scrollToBottom();
    }

    scrollToBottom() {
        requestAnimationFrame(() => {
            this.elements.messagesContainer.scrollTop = this.elements.messagesContainer.scrollHeight;
        });
    }

    async checkAuth() {
        const username = localStorage.getItem("username");
        if (!username) return this.showAuthModal();
        try {
            const response = await fetch("/api/validate.php");
            if (response.ok) {
                const data = await response.json();
                await this.startSession(data);
            } else this.logout();
        } catch {
            this.logout();
        }
    }

    logout() {
        localStorage.removeItem("username");
        localStorage.removeItem("privateKey");
        this.user = null;
        this.showAuthModal();
    }

    showAuthModal() {
        if (this.elements.authModal)
            this.elements.authModal.style.display = "flex";
    }

    showConnectionStatus(status, message) {
        const existing = document.querySelector(".connection-status");
        if (existing) existing.remove();
        if (status === "connected") return;
        const el = document.createElement("div");
        el.className = `connection-status ${status}`;
        el.innerHTML = `<div class="status-indicator"></div><span>${message}</span>`;
        document.body.appendChild(el);
        if (status === "connected") setTimeout(() => el.remove(), 3000);
    }

    async loadNews() {
        try {
            const res = await fetch("/api/news.php");
            if (!res.ok) throw new Error();
            const data = await res.json();
            this.renderNews(data.news);
        } catch {}
    }

    renderNews(news) {
        if (!this.elements.newsContainer) return;
        this.elements.newsContainer.innerHTML = "";
        if (!news.length) {
            this.elements.newsContainer.innerHTML = `<div class="news-item"><p style="text-align:center;color:var(--text-secondary)">Новостей пока нет</p></div>`;
            return;
        }
        news.forEach(n => {
            const el = document.createElement("div");
            el.className = "news-item";
            el.innerHTML = `<p>${this.sanitize(n.text)}</p><span>${new Date(n.created_at).toLocaleString()}</span>`;
            this.elements.newsContainer.appendChild(el);
        });
    }

    checkAdminStatus() {
        const isAdmin = this.user?.username === "admin";
        if (this.elements.openNewsFormBtn)
            this.elements.openNewsFormBtn.style.display = isAdmin ? "flex" : "none";
        if (!isAdmin && this.elements.adminNewsForm)
            this.elements.adminNewsForm.style.display = "none";
    }

    async addNews() {
        if (this.user?.username !== "admin") return this.showError("Нет прав для добавления новостей");
        const text = this.elements.newsText.value.trim();
        if (!text) return this.showError("Введите текст новости");
        try {
            const res = await fetch("/api/news.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ text })
            });
            if (!res.ok) throw new Error((await res.json()).error);
            this.elements.newsText.value = "";
            this.elements.adminNewsForm.style.display = "none";
            this.loadNews();
        } catch (e) {
            this.showError("Ошибка добавления новости");
        }
    }

    showError(message) {
        const el = document.createElement("div");
        el.className = "error-message";
        el.textContent = message;
        document.body.appendChild(el);
        setTimeout(() => el.remove(), 4000);
    }

    sanitize(t) {
        const d = document.createElement("div");
        d.textContent = t;
        return d.innerHTML;
    }

    setupEmojiPicker() {
        if (!this.elements.emojiButton || !this.elements.emojiPicker) return;
        this.elements.emojiButton.addEventListener("click", e => {
            e.stopPropagation();
            const visible = this.elements.emojiPicker.style.display !== "none";
            this.elements.emojiPicker.style.display = visible ? "none" : "block";
        });
        this.elements.emojiPicker.querySelectorAll(".emoji-option").forEach(emoji => {
            emoji.addEventListener("click", () => {
                this.insertTextAtCursor(emoji.textContent);
                this.elements.emojiPicker.style.display = "none";
            });
        });
        document.addEventListener("click", e => {
            if (!this.elements.emojiPicker.contains(e.target) && e.target !== this.elements.emojiButton)
                this.elements.emojiPicker.style.display = "none";
        });
    }

    insertTextAtCursor(text) {
        const input = this.elements.messageInput;
        if (!input) return;
        const start = input.selectionStart, end = input.selectionEnd;
        input.value = input.value.substring(0, start) + text + input.value.substring(end);
        input.setSelectionRange(start + text.length, start + text.length);
        input.focus();
    }

    handleAttachment() {
        this.showError("Функционал вложений пока не реализован.");
    }
}

document.addEventListener("DOMContentLoaded", () => new Messenger());
