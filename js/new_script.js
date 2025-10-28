function animateLogos() {
  const logos = document.querySelectorAll(".text");

  logos.forEach(logo => {
    const talks = logo.querySelector(".talks");
    if (!talks) return;

    const placeholder = "•••••";
    const text = "Talks";

    // удаляем старый курсор, если есть
    const oldCursor = talks.querySelector(".typing-cursor");
    if (oldCursor) oldCursor.remove();

    // плавное появление блока
    logo.style.opacity = "0";
    logo.style.transform = "translateY(10px)";
    logo.style.transition = "opacity 1s ease, transform 1s ease";

    setTimeout(() => {
      logo.style.opacity = "1";
      logo.style.transform = "translateY(0)";
    }, 300);

    // эффект печати
    setTimeout(() => {
      let i = 0;
      const chars = placeholder.split("");

      function typeNext() {
        if (i < text.length) {
          chars[i] = text[i];
          talks.textContent = chars.join("");

          // случайная задержка для эффекта человека
          const delay = 80 + Math.random() * 120;
          i++;
          setTimeout(typeNext, delay);
        } else {
          talks.appendChild(cursor);
          cursor.classList.add("idle");
        }
      }

      typeNext();
    }, 1000);
  });
}

document.addEventListener("DOMContentLoaded", animateLogos);
observer.observe(document.body, { attributes: true, subtree: true, attributeFilter: ["class"] });
class Messenger {
    constructor() {
        this.user = null;
        this.elements = {};
        document.addEventListener("DOMContentLoaded", () => {
            this.cacheElements();
            this.setupEvents();
            this.checkAuth();
        });
    }

    cacheElements() {
        this.elements = {
            authModal: document.getElementById("auth-modal"),
            loginForm: document.getElementById("login-form"),
            registerForm: document.getElementById("register-form"),
            messageInput: document.getElementById("message-input"),
            sendBtn: document.getElementById("send-btn"),
            messagesContainer: document.getElementById("messages-container"),
            newsContainer: document.getElementById("news-container"),
            openNewsFormBtn: document.getElementById("open-news-form-btn"),
            adminNewsForm: document.getElementById("admin-news-form"),
            newsText: document.getElementById("news-text"),
            addNewsBtn: document.getElementById("add-news-btn"),
            closeNewsFormBtn: document.getElementById("close-news-form-btn"),
            userId: document.getElementById("user-id")
        };
    }

    setupEvents() {
        document.querySelectorAll(".tab").forEach(tab => {
            tab.addEventListener("click", e => {
                e.preventDefault();
                const target = tab.dataset.tab;
                document.querySelectorAll(".tab, .auth-form").forEach(el => el.classList.remove("active"));
                tab.classList.add("active");
                document.getElementById(`${target}-form`).classList.add("active");
            });
        });

        this.elements.loginForm?.addEventListener("submit", e => this.handleLogin(e));
        this.elements.registerForm?.addEventListener("submit", e => this.handleRegister(e));
        this.elements.sendBtn?.addEventListener("click", () => this.sendMessage());
        this.elements.messageInput?.addEventListener("keypress", e => { if (e.key === "Enter") this.sendMessage(); });
        this.elements.addNewsBtn?.addEventListener("click", () => this.addNews());
        this.elements.openNewsFormBtn?.addEventListener("click", () => this.toggleNewsForm(true));
        this.elements.closeNewsFormBtn?.addEventListener("click", () => this.toggleNewsForm(false));
    }

    async checkAuth() {
        try {
            const res = await fetch("/api/validate.php");
            const data = await res.json();
            if (!res.ok) throw new Error();
            this.startSession(data);
        } catch {
            this.showAuth();
        }
    }

    showAuth() {
        this.elements.authModal.style.display = "flex";
    }

    async handleLogin(e) {
        e.preventDefault();
        const username = document.getElementById("login-username").value.trim();
        const password = document.getElementById("login-password").value.trim();
        if (!username || !password) return this.showError("Введите логин и пароль");

        try {
            const res = await fetch("/api/login.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ username, password })
            });
            const data = await res.json();
            if (!res.ok) throw new Error(data.error || "Ошибка входа");
            this.startSession(data);
        } catch (err) {
            this.showError(err.message);
        }
    }

    async handleRegister(e) {
        e.preventDefault();
        const u = document.getElementById("register-username").value.trim();
        const p = document.getElementById("register-password").value.trim();
        const c = document.getElementById("confirm-password").value.trim();
        if (p !== c) return this.showError("Пароли не совпадают");

        try {
            const res = await fetch("/api/register.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ username: u, password: p })
            });
            const data = await res.json();
            if (!res.ok) throw new Error(data.error || "Ошибка регистрации");
            this.startSession(data);
        } catch (err) {
            this.showError(err.message);
        }
    }

    startSession(user) {
        this.user = user;
        this.elements.authModal.style.display = "none";
        this.elements.userId.textContent = user.userId.toString().slice(-4);
        this.loadMessages();
        this.loadNews();
        this.checkAdminStatus();
    }

    async loadMessages() {
        try {
            const res = await fetch("/api/messages.php");
            const data = await res.json();
            this.elements.messagesContainer.innerHTML = "";
            if (!data.messages?.length) return;
            data.messages.forEach(m => this.displayMessage({
                sender: m.sender,
                content: m.content,
                timestamp: new Date(m.timestamp),
                isOwn: m.sender === this.user?.username
            }));
        } catch {
            this.showError("Ошибка загрузки сообщений");
        }
    }

    async sendMessage() {
        const content = this.elements.messageInput.value.trim();
        if (!content) return;
        try {
            await fetch("/api/messages.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ content })
            });
            this.elements.messageInput.value = "";
            this.loadMessages(); // просто перезагружаем чат
        } catch {
            this.showError("Ошибка отправки");
        }
    }

    displayMessage(m) {
        const div = document.createElement("div");
        div.className = `message ${m.isOwn ? "own" : ""}`;
        const t = m.timestamp.toLocaleTimeString("ru-RU", { hour: "2-digit", minute: "2-digit" });

        div.innerHTML = `
            <div class="message-avatar">${m.sender[0].toUpperCase()}</div>
            <div class="message-content">
                <div class="sender">
                    <span>${this.sanitize(m.sender)}</span>
                </div>
                <div class="content">${this.sanitize(m.content)}</div>
                <div class="time">${t}</div>
            </div>`;
        this.elements.messagesContainer.appendChild(div);
        this.elements.messagesContainer.scrollTop = this.elements.messagesContainer.scrollHeight;
    }

    async loadNews() {
        try {
            const res = await fetch("/api/news.php");
            const data = await res.json();
            this.renderNews(data.news);
        } catch {
            this.showError("Ошибка загрузки новостей");
        }
    }

    renderNews(news) {
        this.elements.newsContainer.innerHTML = "";
        if (!news?.length) {
            this.elements.newsContainer.innerHTML = `<div class="news-item">Новостей нет</div>`;
            return;
        }
        for (const n of news) {
            const el = document.createElement("div");
            el.className = "news-item";
            el.innerHTML = `<p>${this.sanitize(n.text)}</p><span>${new Date(n.created_at).toLocaleString()}</span>`;
            this.elements.newsContainer.appendChild(el);
        }
    }

    async addNews() {
        const text = this.elements.newsText.value.trim();
        if (!text) return this.showError("Введите текст новости");
        await fetch("/api/news.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ text })
        });
        this.elements.newsText.value = "";
        this.toggleNewsForm(false);
        this.loadNews();
    }

    toggleNewsForm(show) {
        this.elements.adminNewsForm.style.display = show ? "block" : "none";
    }

    checkAdminStatus() {
        const isAdmin = this.user?.prefix === "ADMIN" || this.user?.username === "admin";
        this.elements.openNewsFormBtn.style.display = isAdmin ? "flex" : "none";
    }

    sanitize(t) {
        const d = document.createElement("div");
        d.textContent = t;
        return d.innerHTML;
    }

    showError(m) {
        const e = document.createElement("div");
        e.className = "error-message";
        e.textContent = m;
        document.body.appendChild(e);
        setTimeout(() => e.remove(), 3000);
    }
}

new Messenger();
