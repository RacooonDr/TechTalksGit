<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com; img-src 'self' data: https://cdn.jsdelivr.net; font-src 'self' https://cdnjs.cloudflare.com;">
    <title>TechTalks</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="/style.css" id="main-css">
    <link rel="icon" type="image/x-icon" href="/images/favico.ico">
    <!-- Подключение для подсветки синтаксиса -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/github-dark.min.css">
</head>
<body>
<div class="app-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <header class="sidebar-header">
            <div class="logo-container">
                <img src="/images/Logo3.svg" alt="dfdf">
                <div class="text">
                    <span class="tech">Tech</span>
                    <span class="talks"></span>
                </div>
            </div>
            <div class="user-info">
                <div class="avatar" id="user-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="user-details">
                    <span class="username" id="sidebar-username">Пользователь</span>
                    <span class="user-status" id="user-status" data-status="online">🟢 В сети</span>
                </div>
                <div class="user-menu">
                    <i class="fas fa-cog" id="settings-btn"></i>
                </div>
            </div>
        </header>
        
        <!-- Переключатель тем -->
        <div class="theme-switcher">
            <button class="theme-btn active" data-theme="dark"><i class="fas fa-moon"></i></button>
            <button class="theme-btn" data-theme="light"><i class="fas fa-sun"></i></button>
            <button class="theme-btn" data-theme="purple"><i class="fas fa-palette"></i></button>
        </div>

        <div class="search">
            <input type="text" placeholder="Поиск..."><i class="fas fa-search"></i>
        </div>

        <!-- Список комнат -->
        <div class="rooms-section">
            <div class="section-header">
                <span>Комнаты</span>
                <i class="fas fa-plus" id="create-room-btn" title="Создать комнату"></i>
            </div>
            <div class="rooms-list" id="rooms-list">
                <div class="room active" data-room="general">
                    <div class="room-icon"><i class="fas fa-hashtag"></i></div>
                    <span>Общий чат</span>
                </div>
                <div class="room" data-room="tech">
                    <div class="room-icon"><i class="fas fa-code"></i></div>
                    <span>Разработка</span>
                </div>
                <div class="room" data-room="games">
                    <div class="room-icon"><i class="fas fa-gamepad"></i></div>
                    <span>Игры</span>
                </div>
            </div>
        </div>

        <!-- Список контактов -->
        <div class="contacts-section">
            <div class="section-header">
                <span>Контакты</span>
                <span class="online-count" id="contacts-online">0</span>
            </div>
            <div class="contacts-list" id="contacts-list">
                <!-- Контакты будут загружаться динамически -->
            </div>
        </div>
    </aside>

    <!-- Main Chat -->
    <main class="chat-area">
        <header class="chat-header">
            <div class="chat-info">
                <div class="avatar"><i class="fas fa-hashtag" id="current-room-icon"></i></div>
                <div>
                    <span id="current-room-name">Общий чат</span>
                    <span class="online">онлайн: <span id="online-count">0</span></span>
                </div>
            </div>
            <div class="chat-actions">
                <i class="fas fa-lock" title="Шифрование"></i>
                <i class="fas fa-shield-alt" title="Анонимность"></i>
                <i class="fas fa-ellipsis-v" id="chat-menu-btn"></i>
            </div>
        </header>

        <!-- Индикатор набора текста -->
        <div class="typing-indicator" id="typing-indicator" style="display: none;">
            <span id="typing-users"></span> печатает...
        </div>

        <div class="messages" id="messages-container"></div>

        <div class="message-input">
            <div class="input-tools">
                <i class="fas fa-smile" id="emoji-button"></i>
                <i class="fas fa-paperclip" id="attachment-button"></i>
                <i class="fas fa-microphone" id="voice-message-btn"></i>
                <i class="fas fa-code" id="code-block-btn"></i>
            </div>
            
            <!-- Эмодзи пикер -->
            <div id="emoji-picker" class="emoji-picker" style="display: none;">
                <span class="emoji-option">😀</span>
                <span class="emoji-option">😂</span>
                <span class="emoji-option">😍</span>
                <span class="emoji-option">😎</span>
                <span class="emoji-option">👍</span>
                <span class="emoji-option">❤️</span>
                <span class="emoji-option">🔥</span>
                <span class="emoji-option">🎉</span>
            </div>

            <!-- Блок для записи голосового сообщения -->
            <div class="voice-recorder" id="voice-recorder" style="display: none;">
                <div class="recording-indicator">
                    <i class="fas fa-microphone recording-pulse"></i>
                    <span>Запись...</span>
                    <span class="recording-time" id="recording-time">0:00</span>
                </div>
                <button class="stop-recording-btn" id="stop-recording-btn">
                    <i class="fas fa-stop"></i>
                </button>
            </div>

            <!-- Блок для ввода кода -->
            <div class="code-input" id="code-input" style="display: none;">
                <select id="code-language">
                    <option value="javascript">JavaScript</option>
                    <option value="python">Python</option>
                    <option value="php">PHP</option>
                    <option value="html">HTML</option>
                    <option value="css">CSS</option>
                </select>
                <textarea id="code-text" placeholder="Введите код..."></textarea>
                <div class="code-actions">
                    <button class="cancel-code-btn" id="cancel-code-btn">Отмена</button>
                    <button class="insert-code-btn" id="insert-code-btn">Вставить</button>
                </div>
            </div>

            <input type="text" placeholder="Напишите сообщение..." id="message-input">
            <button class="send-btn" id="send-btn"><i class="fas fa-paper-plane"></i></button>
        </div>
    </main>

    <!-- News Sidebar -->
    <aside class="news-sidebar">
        <div class="news-header">
            <h3>Лента новостей</h3>
            <div class="news-actions">
                <button id="open-news-form-btn" class="icon-btn" title="Добавить новость" style="display: none;">
                    <i class="fas fa-plus"></i>
                </button>
                <button id="create-poll-btn" class="icon-btn" title="Создать опрос" style="display: none;">
                    <i class="fas fa-chart-bar"></i>
                </button>
            </div>
        </div>
        
        <!-- Блок опросов -->
        <div class="polls-section" id="polls-container">
            <!-- Опросы будут загружаться динамически -->
        </div>

        <div id="news-container"></div>
        
        <!-- Форма добавления новости -->
        <div id="admin-news-form" class="news-form-container" style="display: none;">
            <div class="news-form-header">
                <span>Новая новость</span>
                <button id="close-news-form-btn" class="icon-btn" title="Закрыть">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <textarea id="news-text" placeholder="Введите текст новости..." rows="3"></textarea>
            <div class="news-form-actions">
                <button id="add-news-btn" class="send-btn">
                    <i class="fas fa-paper-plane"></i> Отправить
                </button>
            </div>
        </div>

        <!-- Форма создания опроса -->
        <div id="poll-form" class="news-form-container" style="display: none;">
            <div class="news-form-header">
                <span>Новый опрос</span>
                <button id="close-poll-form-btn" class="icon-btn" title="Закрыть">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <input type="text" id="poll-question" placeholder="Вопрос опроса" class="poll-input">
            <div id="poll-options">
                <input type="text" class="poll-option" placeholder="Вариант 1">
                <input type="text" class="poll-option" placeholder="Вариант 2">
            </div>
            <button id="add-poll-option" class="icon-btn">
                <i class="fas fa-plus"></i> Добавить вариант
            </button>
            <div class="news-form-actions">
                <button id="create-poll-submit" class="send-btn">
                    <i class="fas fa-chart-bar"></i> Создать опрос
                </button>
            </div>
        </div>

        <!-- Достижения пользователя -->
        <div class="achievements-section">
            <h4>Мои достижения</h4>
            <div class="achievements-list" id="achievements-list">
                <div class="achievement locked">
                    <i class="fas fa-comment"></i>
                    <span>Первое сообщение</span>
                </div>
                <div class="achievement locked">
                    <i class="fas fa-fire"></i>
                    <span>Активный пользователь</span>
                </div>
            </div>
        </div>
    </aside>
</div>

<!-- Auth Modal -->
<div class="auth-modal" id="auth-modal">
    <!-- Существующая форма авторизации -->
</div>

<!-- Модальное окно настроек -->
<div class="modal" id="settings-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Настройки</h3>
            <button class="close-modal" id="close-settings">&times;</button>
        </div>
        <div class="modal-body">
            <div class="settings-tabs">
                <button class="settings-tab active" data-tab="profile">Профиль</button>
                <button class="settings-tab" data-tab="notifications">Уведомления</button>
                <button class="settings-tab" data-tab="privacy">Приватность</button>
            </div>
            
            <div class="settings-content">
                <!-- Вкладка профиля -->
                <div class="settings-pane active" id="profile-pane">
                    <div class="avatar-upload">
                        <div class="avatar-preview" id="avatar-preview">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <input type="file" id="avatar-upload" accept="image/*" style="display: none;">
                        <button class="upload-btn" id="upload-avatar-btn">Загрузить аватар</button>
                    </div>
                    <div class="form-group">
                        <label>Статус</label>
                        <select id="status-select">
                            <option value="online">🟢 В сети</option>
                            <option value="away">🟡 Отошёл</option>
                            <option value="dnd">🔴 Не беспокоить</option>
                            <option value="offline">⚫ Не в сети</option>
                        </select>
                    </div>
                </div>
                
                <!-- Вкладка уведомлений -->
                <div class="settings-pane" id="notifications-pane">
                    <div class="form-group">
                        <label>
                            <input type="checkbox" id="notify-messages" checked>
                            Уведомления о новых сообщениях
                        </label>
                    </div>
                    <div class="form-group">
                        <label>
                            <input type="checkbox" id="notify-mentions" checked>
                            Уведомления об упоминаниях
                        </label>
                    </div>
                    <div class="form-group">
                        <label>
                            <input type="checkbox" id="sound-enabled" checked>
                            Звуковые уведомления
                        </label>
                    </div>
                </div>
                
                <!-- Вкладка приватности -->
                <div class="settings-pane" id="privacy-pane">
                    <div class="form-group">
                        <label>
                            <input type="checkbox" id="allow-dm" checked>
                            Разрешить личные сообщения
                        </label>
                    </div>
                    <div class="form-group">
                        <label>
                            <input type="checkbox" id="show-online" checked>
                            Показывать статус онлайн
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" id="save-settings">Сохранить</button>
        </div>
    </div>
</div>

<!-- Модальное окно создания комнаты -->
<div class="modal" id="create-room-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Создать комнату</h3>
            <button class="close-modal" id="close-room-modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>Название комнаты</label>
                <input type="text" id="room-name" placeholder="Введите название">
            </div>
            <div class="form-group">
                <label>Описание</label>
                <textarea id="room-description" placeholder="Описание комнаты"></textarea>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" id="room-private">
                    Приватная комната
                </label>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" id="create-room-submit">Создать</button>
        </div>
    </div>
</div>

<!-- Контейнер для уведомлений -->
<div id="notifications-container" class="notifications-container"></div>

<!-- Мобильное меню -->
<div class="mobile-menu">
    <button class="mobile-btn" id="mobile-chats-btn">
        <i class="fas fa-comments"></i>
    </button>
    <button class="mobile-btn" id="mobile-news-btn">
        <i class="fas fa-newspaper"></i>
    </button>
    <button class="mobile-btn" id="mobile-settings-btn">
        <i class="fas fa-cog"></i>
    </button>
</div>

<script src="https://cdn.jsdelivr.net/npm/emoji-picker-element@1.21.2/index.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>
<script src="/js/crypto.js"></script>
<script src="/js/script.js?v=4" defer></script>
</body>
</html>