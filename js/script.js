class AdvancedMessenger {
    constructor() {
        this.user = null;
        this.currentRoom = 'general';
        this.rooms = {};
        this.contacts = [];
        this.typingUsers = new Set();
        this.typingTimeout = null;
        this.notificationSound = null;
        this.settings = this.loadSettings();
        this.elements = {};
        
        document.addEventListener("DOMContentLoaded", () => {
            this.cacheElements();
            this.setupEvents();
            this.checkAuth();
            this.initNotifications();
            this.initTheme();
            this.loadContacts();
        });
    }

    cacheElements() {
        this.elements = {
            // Основные элементы
            authModal: document.getElementById("auth-modal"),
            loginForm: document.getElementById("login-form"),
            registerForm: document.getElementById("register-form"),
            messageInput: document.getElementById("message-input"),
            sendBtn: document.getElementById("send-btn"),
            messagesContainer: document.getElementById("messages-container"),
            newsContainer: document.getElementById("news-container"),
            userId: document.getElementById("user-id"),
            
            // Новые элементы интерфейса
            themeSwitcher: document.querySelector('.theme-switcher'),
            roomsList: document.getElementById('rooms-list'),
            contactsList: document.getElementById('contacts-list'),
            typingIndicator: document.getElementById('typing-indicator'),
            typingUsers: document.getElementById('typing-users'),
            currentRoomName: document.getElementById('current-room-name'),
            currentRoomIcon: document.getElementById('current-room-icon'),
            
            // Модальные окна
            settingsModal: document.getElementById('settings-modal'),
            createRoomModal: document.getElementById('create-room-modal'),
            settingsBtn: document.getElementById('settings-btn'),
            closeSettings: document.getElementById('close-settings'),
            saveSettings: document.getElementById('save-settings'),
            createRoomBtn: document.getElementById('create-room-btn'),
            closeRoomModal: document.getElementById('close-room-modal'),
            createRoomSubmit: document.getElementById('create-room-submit'),
            
            // Голосовые сообщения
            voiceMessageBtn: document.getElementById('voice-message-btn'),
            voiceRecorder: document.getElementById('voice-recorder'),
            stopRecordingBtn: document.getElementById('stop-recording-btn'),
            recordingTime: document.getElementById('recording-time'),
            
            // Блоки кода
            codeBlockBtn: document.getElementById('code-block-btn'),
            codeInput: document.getElementById('code-input'),
            cancelCodeBtn: document.getElementById('cancel-code-btn'),
            insertCodeBtn: document.getElementById('insert-code-btn'),
            codeLanguage: document.getElementById('code-language'),
            codeText: document.getElementById('code-text'),
            
            // Опросы
            createPollBtn: document.getElementById('create-poll-btn'),
            pollForm: document.getElementById('poll-form'),
            closePollFormBtn: document.getElementById('close-poll-form-btn'),
            createPollSubmit: document.getElementById('create-poll-submit'),
            pollOptions: document.getElementById('poll-options'),
            addPollOption: document.getElementById('add-poll-option'),
            pollsContainer: document.getElementById('polls-container'),
            
            // Настройки
            avatarUpload: document.getElementById('avatar-upload'),
            uploadAvatarBtn: document.getElementById('upload-avatar-btn'),
            avatarPreview: document.getElementById('avatar-preview'),
            statusSelect: document.getElementById('status-select'),
            
            // Уведомления
            notificationsContainer: document.getElementById('notifications-container')
        };
    }

    setupEvents() {
        // Основные события
        this.elements.loginForm?.addEventListener("submit", e => this.handleLogin(e));
        this.elements.registerForm?.addEventListener("submit", e => this.handleRegister(e));
        this.elements.sendBtn?.addEventListener("click", () => this.sendMessage());
        this.elements.messageInput?.addEventListener("keypress", e => {
            if (e.key === "Enter") this.sendMessage();
            else this.startTyping();
        });

        // События тем
        this.elements.themeSwitcher?.addEventListener('click', e => {
            if (e.target.classList.contains('theme-btn')) {
                this.switchTheme(e.target.dataset.theme);
            }
        });

        // События комнат
        this.elements.roomsList?.addEventListener('click', e => {
            const room = e.target.closest('.room');
            if (room) {
                this.switchRoom(room.dataset.room);
            }
        });

        // События контактов
        this.elements.contactsList?.addEventListener('click', e => {
            const contact = e.target.closest('.contact');
            if (contact) {
                this.startPrivateChat(contact.dataset.userId);
            }
        });

        // Модальные окна
        this.elements.settingsBtn?.addEventListener('click', () => this.showModal('settings'));
        this.elements.closeSettings?.addEventListener('click', () => this.hideModal('settings'));
        this.elements.createRoomBtn?.addEventListener('click', () => this.showModal('create-room'));
        this.elements.closeRoomModal?.addEventListener('click', () => this.hideModal('create-room'));
        this.elements.createRoomSubmit?.addEventListener('click', () => this.createRoom());

        // Голосовые сообщения
        this.elements.voiceMessageBtn?.addEventListener('click', () => this.startRecording());
        this.elements.stopRecordingBtn?.addEventListener('click', () => this.stopRecording());

        // Блоки кода
        this.elements.codeBlockBtn?.addEventListener('click', () => this.toggleCodeInput());
        this.elements.cancelCodeBtn?.addEventListener('click', () => this.toggleCodeInput(false));
        this.elements.insertCodeBtn?.addEventListener('click', () => this.insertCode());

        // Опросы
        this.elements.createPollBtn?.addEventListener('click', () => this.togglePollForm());
        this.elements.closePollFormBtn?.addEventListener('click', () => this.togglePollForm(false));
        this.elements.createPollSubmit?.addEventListener('click', () => this.createPoll());
        this.elements.addPollOption?.addEventListener('click', () => this.addPollOption());

        // Настройки
        this.elements.uploadAvatarBtn?.addEventListener('click', () => this.elements.avatarUpload.click());
        this.elements.avatarUpload?.addEventListener('change', e => this.handleAvatarUpload(e));
        this.elements.saveSettings?.addEventListener('click', () => this.saveSettings());

        // Закрытие модальных окон по клику вне области
        document.addEventListener('click', e => {
            if (e.target.classList.contains('modal')) {
                e.target.classList.remove('show');
            }
        });

        // Обработка ссылок для предпросмотра
        document.addEventListener('click', e => {
            if (e.target.tagName === 'A' && e.target.href) {
                e.preventDefault();
                this.showLinkPreview(e.target.href);
            }
        });
    }

    // === СИСТЕМА АУТЕНТИФИКАЦИИ ===
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
        if (!username || !password) return this.showNotification("Введите логин и пароль", "error");

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
            this.showNotification(err.message, "error");
        }
    }

    async handleRegister(e) {
        e.preventDefault();
        const u = document.getElementById("register-username").value.trim();
        const p = document.getElementById("register-password").value.trim();
        const c = document.getElementById("confirm-password").value.trim();
        if (p !== c) return this.showNotification("Пароли не совпадают", "error");

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
            this.showNotification(err.message, "error");
        }
    }

    startSession(user) {
        this.user = user;
        this.elements.authModal.style.display = "none";
        this.elements.userId.textContent = user.userId.toString().slice(-4);
        
        // Загрузка начальных данных
        this.loadMessages();
        this.loadNews();
        this.loadPolls();
        this.checkAdminStatus();
        this.loadAchievements();
        
        // Применение настроек
        this.applySettings();
        
        this.showNotification(`Добро пожаловать, ${user.username}!`, "success");
    }

    // === СИСТЕМА КОМНАТ ===
    async switchRoom(roomId) {
        this.currentRoom = roomId;
        
        // Обновление UI
        document.querySelectorAll('.room').forEach(r => r.classList.remove('active'));
        document.querySelector(`.room[data-room="${roomId}"]`).classList.add('active');
        
        // Обновление заголовка
        const roomNames = {
            general: 'Общий чат',
            tech: 'Разработка',
            games: 'Игры'
        };
        
        const roomIcons = {
            general: 'fas fa-hashtag',
            tech: 'fas fa-code',
            games: 'fas fa-gamepad'
        };
        
        this.elements.currentRoomName.textContent = roomNames[roomId] || roomId;
        this.elements.currentRoomIcon.className = roomIcons[roomId] || 'fas fa-hashtag';
        
        // Загрузка сообщений комнаты
        await this.loadMessages();
    }

    async createRoom() {
        const name = document.getElementById('room-name').value.trim();
        const description = document.getElementById('room-description').value.trim();
        const isPrivate = document.getElementById('room-private').checked;

        if (!name) {
            this.showNotification('Введите название комнаты', 'error');
            return;
        }

        try {
            const res = await fetch('/api/rooms.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ name, description, is_private: isPrivate })
            });

            if (!res.ok) throw new Error('Ошибка создания комнаты');
            
            this.hideModal('create-room');
            this.showNotification('Комната создана успешно!', 'success');
            this.loadRooms();
        } catch (err) {
            this.showNotification(err.message, 'error');
        }
    }

    // === ЛИЧНЫЕ СООБЩЕНИЯ ===
    async loadContacts() {
        try {
            const res = await fetch('/api/contacts.php');
            const data = await res.json();
            
            if (data.success) {
                this.contacts = data.contacts;
                this.renderContacts();
            }
        } catch (err) {
            console.error('Ошибка загрузки контактов:', err);
        }
    }

    renderContacts() {
        if (!this.elements.contactsList) return;
        
        this.elements.contactsList.innerHTML = this.contacts.map(contact => `
            <div class="contact" data-user-id="${contact.id}">
                <div class="contact-avatar">
                    ${contact.avatar ? 
                        `<img src="${contact.avatar}" alt="${contact.username}">` : 
                        contact.username[0].toUpperCase()
                    }
                </div>
                <div class="contact-details">
                    <div class="contact-name">${this.sanitize(contact.username)}</div>
                    <div class="contact-status">${contact.status_text}</div>
                </div>
                <div class="status-indicator status-${contact.status}"></div>
            </div>
        `).join('');
        
        // Обновляем счетчик онлайн
        const onlineCount = this.contacts.filter(c => c.status === 'online').length;
        document.getElementById('contacts-online').textContent = onlineCount;
    }

    async startPrivateChat(userId) {
        const contact = this.contacts.find(c => c.id === userId);
        if (!contact) return;

        // Создаем временную комнату для личных сообщений
        this.currentRoom = `private_${userId}`;
        this.elements.currentRoomName.textContent = contact.username;
        this.elements.currentRoomIcon.className = 'fas fa-user';
        
        this.showNotification(`Открыт чат с ${contact.username}`, 'info');
        await this.loadMessages();
    }

    // === СИСТЕМА СООБЩЕНИЙ ===
    async loadMessages() {
        try {
            const res = await fetch(`/api/messages.php?room=${this.currentRoom}`);
            const data = await res.json();
            this.elements.messagesContainer.innerHTML = "";
            
            if (!data.messages?.length) {
                this.elements.messagesContainer.innerHTML = `
                    <div class="no-messages">
                        <i class="fas fa-comments"></i>
                        <p>Пока нет сообщений в этой комнате</p>
                    </div>
                `;
                return;
            }
            
            data.messages.forEach(m => this.displayMessage({
                id: m.id,
                sender: m.sender,
                content: m.content,
                timestamp: new Date(m.timestamp),
                isOwn: m.sender === this.user?.username,
                reactions: m.reactions || {},
                type: m.type || 'text'
            }));
        } catch {
            this.showNotification("Ошибка загрузки сообщений", "error");
        }
    }

    async sendMessage() {
        const content = this.elements.messageInput.value.trim();
        if (!content) return;
        
        try {
            await fetch("/api/messages.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ 
                    content, 
                    room: this.currentRoom,
                    type: 'text'
                })
            });
            
            this.elements.messageInput.value = "";
            this.stopTyping();
            this.loadMessages();
            
            // Проверяем достижения
            this.checkMessageAchievements();
        } catch {
            this.showNotification("Ошибка отправки", "error");
        }
    }

    displayMessage(message) {
        const div = document.createElement("div");
        div.className = `message ${message.isOwn ? "own" : ""} ${message.type}`;
        div.dataset.messageId = message.id;
        
        const time = message.timestamp.toLocaleTimeString("ru-RU", { 
            hour: "2-digit", 
            minute: "2-digit" 
        });

        // Определяем бейдж роли
        const roleBadge = this.getRoleBadge(message.sender);
        
        // Обрабатываем разный тип контента
        const contentHtml = this.formatMessageContent(message.content, message.type);

        div.innerHTML = `
            <div class="message-avatar">
                ${message.sender[0].toUpperCase()}
            </div>
            <div class="message-content">
                <div class="sender">
                    <span>${this.sanitize(message.sender)}</span>
                    ${roleBadge}
                </div>
                <div class="content">${contentHtml}</div>
                ${this.renderReactions(message.reactions)}
                <div class="time">${time}</div>
            </div>`;
        
        this.elements.messagesContainer.appendChild(div);
        this.elements.messagesContainer.scrollTop = this.elements.messagesContainer.scrollHeight;
        
        // Подсветка синтаксиса для блоков кода
        this.highlightCodeBlocks();
    }

    formatMessageContent(content, type) {
        switch (type) {
            case 'code':
                const [language, code] = content.split('|::|');
                return `
                    <div class="code-block">
                        <div class="code-header">
                            <span class="code-language">${language}</span>
                            <button class="copy-code-btn" onclick="messenger.copyCode(this)">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre class="code-content"><code class="language-${language}">${this.sanitize(code)}</code></pre>
                    </div>
                `;
                
            case 'poll':
                return this.renderPoll(content);
                
            case 'voice':
                return `
                    <div class="voice-message">
                        <i class="fas fa-play-circle"></i>
                        <span>Голосовое сообщение</span>
                        <span class="voice-duration">${content}</span>
                    </div>
                `;
                
            default:
                // Обработка ссылок и форматирования
                return this.processTextContent(content);
        }
    }

    processTextContent(text) {
        // Обработка ссылок
        const urlRegex = /(https?:\/\/[^\s]+)/g;
        text = text.replace(urlRegex, '<a href="$1" target="_blank">$1</a>');
        
        // Обработка эмодзи
        const emojiRegex = /:([a-z0-9_+-]+):/g;
        text = text.replace(emojiRegex, '<span class="emoji">$1</span>');
        
        return this.sanitize(text);
    }

    // === РЕАКЦИИ НА СООБЩЕНИЯ ===
    renderReactions(reactions) {
        if (!reactions || Object.keys(reactions).length === 0) return '';
        
        const reactionHtml = Object.entries(reactions).map(([emoji, users]) => {
            const isUserReacted = users.includes(this.user?.username);
            return `
                <div class="reaction ${isUserReacted ? 'active' : ''}" 
                     onclick="messenger.toggleReaction('${emoji}', this)">
                    <span class="emoji">${emoji}</span>
                    <span class="reaction-count">${users.length}</span>
                </div>
            `;
        }).join('');
        
        return `<div class="message-reactions">${reactionHtml}</div>`;
    }

    async toggleReaction(emoji, element) {
        const messageId = element.closest('.message').dataset.messageId;
        
        try {
            await fetch('/api/reactions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    message_id: messageId,
                    emoji: emoji
                })
            });
            
            this.loadMessages(); // Перезагружаем сообщения для обновления реакций
        } catch (err) {
            this.showNotification('Ошибка добавления реакции', 'error');
        }
    }

    // === ГОЛОСОВЫЕ СООБЩЕНИЯ ===
    startRecording() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            this.showNotification('Ваш браузер не поддерживает запись аудио', 'error');
            return;
        }

        this.elements.voiceRecorder.style.display = 'flex';
        this.elements.messageInput.style.display = 'none';

        this.recordingStartTime = Date.now();
        this.recordingInterval = setInterval(() => {
            const elapsed = Math.floor((Date.now() - this.recordingStartTime) / 1000);
            const minutes = Math.floor(elapsed / 60);
            const seconds = elapsed % 60;
            this.elements.recordingTime.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        }, 1000);

        navigator.mediaDevices.getUserMedia({ audio: true })
            .then(stream => {
                this.mediaRecorder = new MediaRecorder(stream);
                this.audioChunks = [];
                
                this.mediaRecorder.ondataavailable = event => {
                    this.audioChunks.push(event.data);
                };
                
                this.mediaRecorder.onstop = () => {
                    this.sendVoiceMessage();
                };
                
                this.mediaRecorder.start();
            })
            .catch(err => {
                this.showNotification('Ошибка доступа к микрофону', 'error');
                this.stopRecording();
            });
    }

    stopRecording() {
        if (this.mediaRecorder && this.mediaRecorder.state !== 'inactive') {
            this.mediaRecorder.stop();
        }
        
        this.elements.voiceRecorder.style.display = 'none';
        this.elements.messageInput.style.display = 'block';
        
        if (this.recordingInterval) {
            clearInterval(this.recordingInterval);
        }
        
        // Останавливаем все аудио потоки
        if (this.mediaRecorder) {
            this.mediaRecorder.stream.getTracks().forEach(track => track.stop());
        }
    }

    async sendVoiceMessage() {
        const audioBlob = new Blob(this.audioChunks, { type: 'audio/webm' });
        const duration = Math.floor((Date.now() - this.recordingStartTime) / 1000);
        
        const formData = new FormData();
        formData.append('audio', audioBlob);
        formData.append('duration', duration);
        formData.append('room', this.currentRoom);
        formData.append('type', 'voice');

        try {
            await fetch('/api/messages.php', {
                method: 'POST',
                body: formData
            });
            
            this.loadMessages();
            this.showNotification('Голосовое сообщение отправлено', 'success');
        } catch (err) {
            this.showNotification('Ошибка отправки голосового сообщения', 'error');
        }
    }

    // === БЛОКИ КОДА ===
    toggleCodeInput(show = true) {
        this.elements.codeInput.style.display = show ? 'block' : 'none';
        this.elements.messageInput.style.display = show ? 'none' : 'block';
    }

    insertCode() {
        const language = this.elements.codeLanguage.value;
        const code = this.elements.codeText.value.trim();
        
        if (!code) {
            this.showNotification('Введите код', 'error');
            return;
        }

        // Форматируем код для отправки
        const formattedCode = `${language}|::|${code}`;
        this.elements.messageInput.value = formattedCode;
        this.elements.messageInput.dataset.type = 'code';
        
        this.toggleCodeInput(false);
        this.sendMessage();
    }

    copyCode(button) {
        const codeContent = button.closest('.code-block').querySelector('.code-content').textContent;
        navigator.clipboard.writeText(codeContent).then(() => {
            const originalHtml = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check"></i>';
            setTimeout(() => {
                button.innerHTML = originalHtml;
            }, 2000);
        });
    }

    highlightCodeBlocks() {
        document.querySelectorAll('pre code').forEach(block => {
            hljs.highlightElement(block);
        });
    }

    // === ОПРОСЫ ===
    togglePollForm(show = true) {
        this.elements.pollForm.style.display = show ? 'block' : 'none';
    }

    addPollOption() {
        const optionCount = this.elements.pollOptions.children.length;
        const newOption = document.createElement('input');
        newOption.type = 'text';
        newOption.className = 'poll-option';
        newOption.placeholder = `Вариант ${optionCount + 1}`;
        this.elements.pollOptions.appendChild(newOption);
    }

    async createPoll() {
        const question = document.getElementById('poll-question').value.trim();
        const options = Array.from(document.getElementsByClassName('poll-option'))
            .map(input => input.value.trim())
            .filter(opt => opt !== '');

        if (!question || options.length < 2) {
            this.showNotification('Введите вопрос и хотя бы 2 варианта ответа', 'error');
            return;
        }

        try {
            await fetch('/api/polls.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    question,
                    options,
                    room: this.currentRoom
                })
            });

            this.togglePollForm(false);
            this.showNotification('Опрос создан успешно!', 'success');
            this.loadPolls();
        } catch (err) {
            this.showNotification('Ошибка создания опроса', 'error');
        }
    }

    async loadPolls() {
        try {
            const res = await fetch('/api/polls.php');
            const data = await res.json();
            
            if (data.success) {
                this.renderPolls(data.polls);
            }
        } catch (err) {
            console.error('Ошибка загрузки опросов:', err);
        }
    }

    renderPolls(polls) {
        if (!this.elements.pollsContainer) return;
        
        this.elements.pollsContainer.innerHTML = polls.map(poll => `
            <div class="poll" data-poll-id="${poll.id}">
                <div class="poll-question">${this.sanitize(poll.question)}</div>
                <div class="poll-options">
                    ${poll.options.map((option, index) => `
                        <div class="poll-option" onclick="messenger.votePoll(${poll.id}, ${index})">
                            <span class="option-text">${this.sanitize(option.text)}</span>
                            <div class="poll-bar" style="width: ${option.percentage || 0}%"></div>
                            <span class="option-percentage">${option.percentage || 0}%</span>
                        </div>
                    `).join('')}
                </div>
                <div class="poll-stats">
                    <span>Всего голосов: ${poll.total_votes || 0}</span>
                    <span>${new Date(poll.created_at).toLocaleDateString()}</span>
                </div>
            </div>
        `).join('');
    }

    async votePoll(pollId, optionIndex) {
        try {
            await fetch('/api/polls.php', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    poll_id: pollId,
                    option_index: optionIndex
                })
            });
            
            this.loadPolls();
        } catch (err) {
            this.showNotification('Ошибка голосования', 'error');
        }
    }

    // === СИСТЕМА ТЕМ ===
    initTheme() {
        const savedTheme = localStorage.getItem('theme') || 'dark';
        this.switchTheme(savedTheme);
    }

    switchTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        
        // Обновляем кнопки темы
        document.querySelectorAll('.theme-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.theme === theme);
        });
        
        this.showNotification(`Тема "${this.getThemeName(theme)}" применена`, 'info');
    }

    getThemeName(theme) {
        const names = {
            dark: 'Тёмная',
            light: 'Светлая',
            purple: 'Фиолетовая'
        };
        return names[theme] || theme;
    }

    // === НАСТРОЙКИ ===
    loadSettings() {
        const defaultSettings = {
            theme: 'dark',
            notifications: {
                messages: true,
                mentions: true,
                sound: true
            },
            privacy: {
                allowDM: true,
                showOnline: true
            },
            status: 'online'
        };
        
        try {
            return { ...defaultSettings, ...JSON.parse(localStorage.getItem('userSettings')) };
        } catch {
            return defaultSettings;
        }
    }

    saveSettings() {
        // Сохраняем настройки уведомлений
        this.settings.notifications.messages = document.getElementById('notify-messages').checked;
        this.settings.notifications.mentions = document.getElementById('notify-mentions').checked;
        this.settings.notifications.sound = document.getElementById('sound-enabled').checked;
        
        // Сохраняем настройки приватности
        this.settings.privacy.allowDM = document.getElementById('allow-dm').checked;
        this.settings.privacy.showOnline = document.getElementById('show-online').checked;
        
        // Сохраняем статус
        this.settings.status = document.getElementById('status-select').value;
        
        localStorage.setItem('userSettings', JSON.stringify(this.settings));
        this.applySettings();
        this.hideModal('settings');
        
        this.showNotification('Настройки сохранены', 'success');
    }

    applySettings() {
        // Применяем тему
        this.switchTheme(this.settings.theme);
        
        // Применяем статус
        this.updateUserStatus(this.settings.status);
        
        // Настраиваем уведомления
        this.initNotifications();
    }

    async handleAvatarUpload(event) {
        const file = event.target.files[0];
        if (!file) return;

        if (!file.type.startsWith('image/')) {
            this.showNotification('Выберите изображение', 'error');
            return;
        }

        const formData = new FormData();
        formData.append('avatar', file);

        try {
            const res = await fetch('/api/avatar.php', {
                method: 'POST',
                body: formData
            });

            if (res.ok) {
                const reader = new FileReader();
                reader.onload = e => {
                    this.elements.avatarPreview.innerHTML = `<img src="${e.target.result}" alt="Аватар">`;
                };
                reader.readAsDataURL(file);
                this.showNotification('Аватар обновлён', 'success');
            }
        } catch (err) {
            this.showNotification('Ошибка загрузки аватара', 'error');
        }
    }

    updateUserStatus(status) {
        const statusTexts = {
            online: '🟢 В сети',
            away: '🟡 Отошёл',
            dnd: '🔴 Не беспокоить',
            offline: '⚫ Не в сети'
        };

        this.settings.status = status;
        document.getElementById('user-status').textContent = statusTexts[status];
        document.getElementById('user-status').dataset.status = status;
        
        // Отправляем статус на сервер
        fetch('/api/status.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ status })
        }).catch(console.error);
    }

    // === СИСТЕМА УВЕДОМЛЕНИЙ ===
    initNotifications() {
        if (this.settings.notifications.sound) {
            this.notificationSound = new Audio('/sounds/notification.mp3');
        }
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        
        const icons = {
            success: 'fas fa-check-circle',
            error: 'fas fa-exclamation-circle',
            info: 'fas fa-info-circle'
        };

        notification.innerHTML = `
            <div class="notification-icon">
                <i class="${icons[type]}"></i>
            </div>
            <div class="notification-content">
                <div class="notification-message">${message}</div>
            </div>
            <button class="notification-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;

        this.elements.notificationsContainer.appendChild(notification);
        
        // Воспроизводим звук, если включен
        if (this.settings.notifications.sound && this.notificationSound) {
            this.notificationSound.play().catch(() => {});
        }
        
        // Автоматическое удаление через 5 секунд
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    }

    // === ИНДИКАТОР НАБОРА ТЕКСТА ===
    startTyping() {
        if (this.typingTimeout) {
            clearTimeout(this.typingTimeout);
        }
        
        // Отправляем серверу, что пользователь печатает
        fetch('/api/typing.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                room: this.currentRoom,
                typing: true 
            })
        }).catch(console.error);
        
        this.typingTimeout = setTimeout(() => this.stopTyping(), 3000);
    }

    stopTyping() {
        if (this.typingTimeout) {
            clearTimeout(this.typingTimeout);
            this.typingTimeout = null;
        }
        
        fetch('/api/typing.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                room: this.currentRoom,
                typing: false 
            })
        }).catch(console.error);
    }

    updateTypingIndicator(users) {
        if (users.length === 0) {
            this.elements.typingIndicator.style.display = 'none';
            return;
        }
        
        const userList = users.slice(0, 3).join(', ');
        const moreCount = users.length - 3;
        
        this.elements.typingUsers.textContent = moreCount > 0 ? 
            `${userList} и ещё ${moreCount}` : userList;
        
        this.elements.typingIndicator.style.display = 'block';
    }

    // === ДОСТИЖЕНИЯ ===
    async loadAchievements() {
        try {
            const res = await fetch('/api/achievements.php');
            const data = await res.json();
            
            if (data.success) {
                this.renderAchievements(data.achievements);
            }
        } catch (err) {
            console.error('Ошибка загрузки достижений:', err);
        }
    }

    renderAchievements(achievements) {
        const container = document.getElementById('achievements-list');
        if (!container) return;
        
        container.innerHTML = achievements.map(ach => `
            <div class="achievement ${ach.unlocked ? 'unlocked' : 'locked'}">
                <i class="fas fa-${ach.icon}"></i>
                <span>${ach.name}</span>
                ${ach.unlocked ? '<i class="fas fa-check"></i>' : ''}
            </div>
        `).join('');
    }

    checkMessageAchievements() {
        // Проверяем различные достижения
        const messageCount = parseInt(localStorage.getItem('messageCount') || '0') + 1;
        localStorage.setItem('messageCount', messageCount.toString());
        
        if (messageCount === 1) {
            this.unlockAchievement('first_message');
        }
        
        if (messageCount >= 10) {
            this.unlockAchievement('active_user');
        }
    }

    async unlockAchievement(achievementId) {
        try {
            await fetch('/api/achievements.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ achievement_id: achievementId })
            });
            
            this.loadAchievements();
        } catch (err) {
            console.error('Ошибка разблокировки достижения:', err);
        }
    }

    // === ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ ===
    showModal(modalType) {
        document.getElementById(`${modalType}-modal`).classList.add('show');
    }

    hideModal(modalType) {
        document.getElementById(`${modalType}-modal`).classList.remove('show');
    }

    getRoleBadge(username) {
        const roles = {
            'admin': { class: 'admin', text: 'ADMIN' },
            'moderator': { class: 'moderator', text: 'MOD' },
            'vip': { class: 'vip', text: 'VIP' }
        };
        
        const role = Object.keys(roles).find(r => username.toLowerCase().includes(r));
        return role ? `<span class="sender-badge ${roles[role].class}">${roles[role].text}</span>` : '';
    }

    sanitize(text) {
        const div = document.createElement("div");
        div.textContent = text;
        return div.innerHTML;
    }

    // === ПРЕДПРОСМОТР ССЫЛОК ===
    async showLinkPreview(url) {
        try {
            const res = await fetch('/api/link-preview.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ url })
            });
            
            const data = await res.json();
            if (data.success) {
                this.displayLinkPreview(data.preview);
            } else {
                window.open(url, '_blank');
            }
        } catch {
            window.open(url, '_blank');
        }
    }

    displayLinkPreview(preview) {
        const previewHtml = `
            <div class="link-preview">
                ${preview.image ? `
                    <img src="${preview.image}" class="link-preview-image" alt="${preview.title}">
                ` : ''}
                <div class="link-preview-content">
                    <div class="link-preview-title">${this.sanitize(preview.title)}</div>
                    ${preview.description ? `
                        <div class="link-preview-description">${this.sanitize(preview.description)}</div>
                    ` : ''}
                    <div class="link-preview-url">${this.sanitize(preview.url)}</div>
                </div>
            </div>
        `;
        
        // Вставляем превью в сообщение
        this.elements.messageInput.value += `\n${preview.url}`;
        this.showNotification('Превью ссылки добавлено', 'info');
    }

    // === PWA ФУНКЦИОНАЛ ===
    initPWA() {
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js')
                .then(registration => {
                    console.log('SW registered: ', registration);
                })
                .catch(registrationError => {
                    console.log('SW registration failed: ', registrationError);
                });
        }
        
        // Обработка офлайн режима
        window.addEventListener('online', () => {
            this.showNotification('Соединение восстановлено', 'success');
            this.syncOfflineMessages();
        });
        
        window.addEventListener('offline', () => {
            this.showNotification('Вы в офлайн режиме', 'error');
        });
    }

    syncOfflineMessages() {
        const offlineMessages = JSON.parse(localStorage.getItem('offlineMessages') || '[]');
        if (offlineMessages.length > 0) {
            this.showNotification(`Синхронизация ${offlineMessages.length} сообщений...`, 'info');
            
            offlineMessages.forEach(message => {
                this.sendOfflineMessage(message);
            });
            
            localStorage.removeItem('offlineMessages');
        }
    }

    async sendOfflineMessage(message) {
        try {
            await fetch('/api/messages.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(message)
            });
        } catch (err) {
            // Сохраняем обратно в офлайн хранилище
            const offlineMessages = JSON.parse(localStorage.getItem('offlineMessages') || '[]');
            offlineMessages.push(message);
            localStorage.setItem('offlineMessages', JSON.stringify(offlineMessages));
        }
    }
}

// Инициализация мессенджера
const messenger = new AdvancedMessenger();

// Анимация логотипа (существующий код)
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