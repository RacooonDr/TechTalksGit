<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechTalks - Временные технические работы</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #8a2be2;
            --secondary-color: #00bfff;
            --dark-bg: #121826;
            --darker-bg: #0a0f18;
            --light-text: #e0e0ff;
            --gray-text: #a0a0c0;
            --card-bg: rgba(42, 50, 74, 0.6);
            --border-color: rgba(138, 43, 226, 0.3);
        }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: linear-gradient(135deg, var(--darker-bg) 0%, var(--dark-bg) 50%, #1a1f2e 100%);
            color: var(--light-text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            padding: 20px;
        }

        .maintenance-container {
            width: 100%;
            max-width: 1200px;
            padding: 40px;
            text-align: center;
            background: rgba(26, 32, 50, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            border: 1px solid var(--border-color);
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
            margin: 0 auto;
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .logo img {
            width: 60px;
            height: 60px;
        }

        .logo-text {
            font-family: "Poppins", sans-serif;
            font-size: 2.5rem;
            font-weight: bold;
            display: flex;
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 1s ease, transform 1s ease;
        }

        .tech { 
            color: #70a8ff; 
        }

        .talks { 
            color: #b47dff; 
            display: inline-block;
            overflow: hidden;
            white-space: nowrap;
            width: auto;
        }

        .typing-cursor {
            display: inline-block;
            width: 3px;
            height: 1em;
            background-color: #b47dff;
            margin-left: 2px;
            animation: blink 1s infinite;
            vertical-align: bottom;
        }

        .typing-cursor.idle {
            animation: blink 2s infinite;
        }

        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0; }
        }

        h1 {
            font-size: 2.8rem;
            margin-bottom: 20px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .subtitle {
            font-size: 1.4rem;
            margin-bottom: 30px;
            color: var(--gray-text);
            line-height: 1.6;
        }

        .status-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 15px;
            padding: 30px;
            margin: 30px 0;
            text-align: left;
        }

        .status-item {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .status-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .status-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .icon-working { background: rgba(0, 204, 153, 0.2); color: #00cc99; }
        .icon-maintenance { background: rgba(255, 167, 38, 0.2); color: #ffa726; }
        .icon-waiting { background: rgba(138, 43, 226, 0.2); color: #8a2be2; }

        .status-content h3 {
            font-size: 1.2rem;
            margin-bottom: 5px;
            color: var(--light-text);
        }

        .status-content p {
            color: var(--gray-text);
            line-height: 1.5;
        }

        .progress-container {
            margin: 30px 0;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 0.9rem;
            color: var(--gray-text);
        }

        .progress-bar {
            height: 8px;
            background: rgba(42, 50, 74, 0.8);
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            border-radius: 10px;
            width: 0%;
            transition: width 0.5s ease-in-out;
        }

        .countdown {
            background: rgba(138, 43, 226, 0.1);
            border: 1px solid var(--border-color);
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
        }

        .countdown-title {
            font-size: 1.1rem;
            margin-bottom: 15px;
            color: var(--gray-text);
        }

        .countdown-timer {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .time-unit {
            text-align: center;
        }

        .time-value {
            font-size: 2.5rem;
            font-weight: bold;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
        }

        .time-label {
            font-size: 0.8rem;
            color: var(--gray-text);
            text-transform: uppercase;
            margin-top: 5px;
        }

        .completion-message {
            background: rgba(0, 204, 153, 0.1);
            border: 1px solid rgba(0, 204, 153, 0.3);
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            display: none;
        }

        .completion-message h3 {
            color: #00cc99;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin: 40px 0;
        }

        .feature-card {
            background: rgba(42, 50, 74, 0.4);
            border: 1px solid rgba(138, 43, 226, 0.2);
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            transition: all 0.3s ease;
            min-height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            border-color: rgba(138, 43, 226, 0.5);
            box-shadow: 0 10px 30px rgba(138, 43, 226, 0.2);
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .feature-card h3 {
            font-size: 1.1rem;
            margin-bottom: 10px;
            color: var(--light-text);
            white-space: nowrap;
        }

        .feature-card p {
            font-size: 0.9rem;
            color: var(--gray-text);
            line-height: 1.5;
        }

        .telegram-section {
            margin: 40px 0;
            padding: 30px;
            background: rgba(138, 43, 226, 0.1);
            border: 1px solid var(--border-color);
            border-radius: 15px;
        }

        .telegram-title {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: var(--light-text);
        }

        .telegram-btn {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 15px 30px;
            background: linear-gradient(135deg, #0088cc, #00bfff);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 136, 204, 0.3);
        }

        .telegram-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 136, 204, 0.4);
        }

        .telegram-btn i {
            font-size: 1.3rem;
        }

        .update-notice {
            background: rgba(255, 167, 38, 0.1);
            border: 1px solid rgba(255, 167, 38, 0.3);
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
        }

        .update-notice h3 {
            color: #ffa726;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .floating-elements {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .floating-element {
            position: absolute;
            font-size: 2rem;
            opacity: 0.1;
            animation: floatElement 15s infinite linear;
            will-change: transform;
        }

        @keyframes floatElement {
            0%, 100% {
                transform: translateY(0px) translateX(0px);
            }
            25% {
                transform: translateY(-30px) translateX(10px);
            }
            50% {
                transform: translateY(-15px) translateX(-10px);
            }
            75% {
                transform: translateY(-45px) translateX(5px);
            }
        }

        /* Адаптивность для мобильных устройств */
        @media (max-width: 1024px) {
            .maintenance-container {
                max-width: 95%;
                padding: 30px 20px;
            }
            
            .features-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 15px;
            }
            
            .feature-card {
                padding: 20px 15px;
                min-height: 180px;
            }
            
            .feature-card h3 {
                font-size: 1rem;
            }
            
            .feature-card p {
                font-size: 0.85rem;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
                align-items: flex-start;
                min-height: 100vh;
                height: auto;
            }
            
            .maintenance-container {
                padding: 25px 15px;
                margin: 10px auto;
                border-radius: 15px;
            }
            
            .logo {
                flex-direction: column;
                gap: 10px;
                margin-bottom: 20px;
            }
            
            .logo-text {
                font-size: 2rem;
            }
            
            h1 {
                font-size: 1.8rem;
                margin-bottom: 15px;
            }
            
            .subtitle {
                font-size: 1.1rem;
                margin-bottom: 20px;
            }
            
            .status-card {
                padding: 20px;
                margin: 20px 0;
            }
            
            .status-item {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
            
            .status-icon {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
            }
            
            .progress-container {
                margin: 20px 0;
            }
            
            .countdown {
                padding: 20px 15px;
                margin: 20px 0;
            }
            
            .countdown-timer {
                gap: 10px;
            }
            
            .time-value {
                font-size: 1.8rem;
            }
            
            .time-label {
                font-size: 0.7rem;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
                gap: 15px;
                margin: 30px 0;
            }
            
            .feature-card {
                min-height: auto;
                padding: 20px;
            }
            
            .feature-card h3 {
                white-space: normal;
            }
            
            .telegram-section {
                padding: 20px;
                margin: 30px 0;
            }
            
            .telegram-title {
                font-size: 1.3rem;
            }
            
            .telegram-btn {
                padding: 12px 25px;
                font-size: 1rem;
            }
            
            .floating-element {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .maintenance-container {
                padding: 20px 10px;
            }
            
            .logo-text {
                font-size: 1.8rem;
            }
            
            h1 {
                font-size: 1.5rem;
            }
            
            .subtitle {
                font-size: 1rem;
            }
            
            .status-content h3 {
                font-size: 1.1rem;
            }
            
            .status-content p {
                font-size: 0.9rem;
            }
            
            .countdown-timer {
                flex-direction: column;
                gap: 15px;
            }
            
            .time-unit {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 15px;
            }
            
            .time-value {
                font-size: 1.5rem;
            }
            
            .telegram-btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* Отключение анимаций для устройств с предпочтением reduced motion */
        @media (prefers-reduced-motion: reduce) {
            .floating-element {
                animation: none;
            }
            
            .feature-card:hover {
                transform: none;
            }
            
            .telegram-btn:hover {
                transform: none;
            }
        }
    </style>
</head>
<body>
    <div class="floating-elements" id="floating-container">
        <!-- Смайлики будут добавлены через JavaScript -->
    </div>

    <div class="maintenance-container">
        <div class="logo">
            <img src="/images/Logo3.svg" alt="TechTalks">
            <div class="logo-text" id="animated-logo">
                <span class="tech">Tech</span>
                <span class="talks" id="talks-text">•••••</span>
            </div>
        </div>

        <h1>Мы переезжаем на новый сервер!</h1>
        <p class="subtitle">
            Проводим масштабное обновление инфраструктуры для улучшения производительности и стабильности
        </p>

        <div class="status-card">
            <div class="status-item">
                <div class="status-icon icon-maintenance">
                    <i class="fas fa-server"></i>
                </div>
                <div class="status-content">
                    <h3>Миграция данных</h3>
                    <p>Переносим базы данных и файлы на новый высокопроизводительный сервер</p>
                </div>
            </div>

            <div class="status-item">
                <div class="status-icon icon-working">
                    <i class="fas fa-bolt"></i>
                </div>
                <div class="status-content">
                    <h3>Обновление функций</h3>
                    <p>Добавляем новые возможности и улучшаем существующий функционал</p>
                </div>
            </div>

            <div class="status-item">
                <div class="status-icon icon-waiting">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="status-content">
                    <h3>Улучшение безопасности</h3>
                    <p>Внедряем дополнительные меры защиты и шифрования данных</p>
                </div>
            </div>
        </div>

        <div class="progress-container">
            <div class="progress-label">
                <span>Прогресс миграции</span>
                <span id="progress-percent">0%</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" id="progress-fill"></div>
            </div>
        </div>

        <div class="countdown" id="countdown">
            <div class="countdown-title">Ожидаемое время восстановления:</div>
            <div class="countdown-timer">
                <div class="time-unit">
                    <div class="time-value" id="days">000</div>
                    <div class="time-label">дней</div>
                </div>
                <div class="time-unit">
                    <div class="time-value" id="hours">00</div>
                    <div class="time-label">часов</div>
                </div>
                <div class="time-unit">
                    <div class="time-value" id="minutes">00</div>
                    <div class="time-label">минут</div>
                </div>
            </div>
        </div>

        <div class="completion-message" id="completion-message">
            <h3><i class="fas fa-check-circle"></i> Работы завершены! Обновите страницу</h3>
        </div>

        <div class="update-notice">
            <h3><i class="fas fa-info-circle"></i> Что нового ждет вас после обновления:</h3>
            <p>Улучшенная производительность, новые комнаты чатов, голосовые сообщения, система реакций и многое другое!</p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-rocket"></i>
                </div>
                <h3>Высокая скорость</h3>
                <p>Новый сервер обеспечит мгновенную доставку сообщений</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Улучшенная безопасность</h3>
                <p>Дополнительные уровни шифрования и защиты данных</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3>Оптимизация</h3>
                <p>Лучшая производительность на мобильных устройствах</p>
            </div>
        </div>

        <div class="telegram-section">
            <h3 class="telegram-title">Следите за обновлениями в Telegram</h3>
            <a href="https://t.me/techtalks_updates" class="telegram-btn" target="_blank">
                <i class="fab fa-telegram"></i>
                Подписаться на обновления
            </a>
        </div>
    </div>

    <script>
        // Анимация логотипа TechTalks
        function animateLogo() {
            const logo = document.getElementById('animated-logo');
            const talks = document.getElementById('talks-text');
            
            if (!talks) return;

            const placeholder = "•••••";
            const text = "Talks";

            // Плавное появление блока
            logo.style.opacity = "1";
            logo.style.transform = "translateY(0)";

            // Эффект печати
            setTimeout(() => {
                let i = 0;
                const chars = placeholder.split("");

                function typeNext() {
                    if (i < text.length) {
                        chars[i] = text[i];
                        talks.textContent = chars.join("");

                        // Случайная задержка для эффекта человека
                        const delay = 80 + Math.random() * 120;
                        i++;
                        setTimeout(typeNext, delay);
                    } else {
                        // Добавляем курсор
                        const cursor = document.createElement('span');
                        cursor.className = 'typing-cursor';
                        talks.appendChild(cursor);
                        
                        setTimeout(() => {
                            cursor.classList.add('idle');
                        }, 1000);
                    }
                }

                typeNext();
            }, 500);
        }

        // Дата завершения работ - 11 ноября 2025 года 00:00
        const endDate = new Date('2025-11-11T00:00:00').getTime();
        // Дата начала работ - 28 октября 2025 года 09:00
        const startDate = new Date('2025-10-28T09:00:00').getTime();
        const totalDuration = endDate - startDate;

        function updateProgressAndCountdown() {
            const now = new Date().getTime();
            const timeRemaining = endDate - now;

            // Обновляем прогресс-бар
            const elapsed = now - startDate;
            const progressPercent = Math.min(Math.max((elapsed / totalDuration) * 100, 0), 100);
            
            document.getElementById('progress-fill').style.width = progressPercent + '%';
            document.getElementById('progress-percent').textContent = Math.round(progressPercent) + '%';

            // Проверяем, не наступила ли дата завершения
            if (timeRemaining <= 0) {
                // Работы завершены
                document.getElementById('countdown').style.display = 'none';
                document.getElementById('completion-message').style.display = 'block';
                return;
            }

            // Обновляем обратный отсчет
            const days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));

            document.getElementById('days').textContent = days.toString().padStart(3, '0');
            document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
            document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
        }

        // Создаем плавающие смайлики (упрощенная версия для производительности)
        function createFloatingElements() {
            const container = document.getElementById('floating-container');
            const emojis = ['🚀', '💻', '📱', '🎮', '🔒', '⚡', '🔧', '🛠️', '💬', '🌟'];
            
            // Уменьшаем количество элементов для мобильных устройств
            const isMobile = window.innerWidth <= 768;
            const elementCount = isMobile ? 20 : 40;

            for (let i = 0; i < elementCount; i++) {
                const element = document.createElement('div');
                element.className = 'floating-element';
                
                // Случайный смайлик
                const randomEmoji = emojis[Math.floor(Math.random() * emojis.length)];
                element.textContent = randomEmoji;
                
                // Случайная позиция
                element.style.left = Math.random() * 100 + '%';
                element.style.top = Math.random() * 100 + '%';
                
                // Случайная задержка анимации
                element.style.animationDelay = (Math.random() * -20) + 's';
                
                // Случайная продолжительность анимации
                element.style.animationDuration = (10 + Math.random() * 10) + 's';
                
                container.appendChild(element);
            }
        }

        // Запускаем всё при загрузке страницы
        document.addEventListener('DOMContentLoaded', function() {
            // Запускаем анимацию логотипа
            animateLogo();
            
            // Сразу обновляем прогресс и отсчет
            updateProgressAndCountdown();
            
            // Обновляем каждую секунду
            setInterval(updateProgressAndCountdown, 1000);
            
            // Создаем плавающие смайлики
            createFloatingElements();

            // Плавное появление элементов при скролле
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Наблюдаем за карточками features
            document.querySelectorAll('.feature-card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(card);
            });
        });

        // Оптимизация для мобильных устройств
        window.addEventListener('load', function() {
            // Отключаем некоторые анимации на слабых устройствах
            if (navigator.hardwareConcurrency && navigator.hardwareConcurrency <= 4) {
                const floatingElements = document.querySelectorAll('.floating-element');
                floatingElements.forEach(el => {
                    el.style.animation = 'none';
                });
            }
        });
    </script>
</body>
</html>