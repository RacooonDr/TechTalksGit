<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Политика конфиденциальности - TechTalks</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/style.css">
    <link rel="icon" type="image/x-icon" href="/images/favico.ico">
    <style>
        /* Сброс стилей для страницы конфиденциальности */
        body {
            margin: 0;
            padding: 0;
            background: var(--bg-primary, #0f0f0f);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-primary, #ffffff);
            min-height: 100vh;
        }

        .privacy-container {
            min-height: 100vh;
            background: var(--bg-primary, #0f0f0f);
            display: flex;
            flex-direction: column;
        }

        /* Компактный HEADER */
        .privacy-header {
            background: var(--sidebar-bg, #1a1a1a);
            padding: 0.5rem 2rem;
            border-bottom: 1px solid var(--border-color, #333333);
            display: flex;
            align-items: center;
            gap: 1rem;
            position: sticky;
            top: 0;
            z-index: 100;
            min-height: 50px;
        }

        .privacy-header .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary-color, #667eea);
            font-weight: 600;
            font-size: 1.1rem;
        }

        .privacy-header .logo i {
            font-size: 1.2rem;
        }

        .privacy-content {
            flex: 1;
            max-width: 800px;
            margin: 0 auto;
            padding: 1.5rem 2rem;
            background: var(--bg-primary, #0f0f0f);
            overflow-y: visible;
        }

        .privacy-title {
            color: var(--text-primary, #ffffff);
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 1.6rem;
        }

        .privacy-section {
            margin-bottom: 1.5rem;
            padding: 1.25rem;
            background: var(--sidebar-bg, #1a1a1a);
            border-radius: 8px;
            border: 1px solid var(--border-color, #333333);
        }

        .privacy-section h2 {
            color: var(--primary-color, #667eea);
            margin-bottom: 0.75rem;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .privacy-section h2 i {
            font-size: 1rem;
        }

        .privacy-section p {
            color: var(--text-secondary, #888888);
            line-height: 1.6;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
        }

        .privacy-list {
            color: var(--text-secondary, #888888);
            margin: 0.5rem 0 1rem 1rem;
            padding-left: 1rem;
        }

        .privacy-list li {
            margin-bottom: 0.5rem;
            line-height: 1.5;
        }

        .security-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.3rem 0.6rem;
            background: var(--primary-color, #667eea);
            color: white;
            border-radius: 4px;
            font-size: 0.8rem;
            margin: 0.2rem;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.25rem;
            background: var(--primary-color, #667eea);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: background-color 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 0.85rem;
        }

        .back-btn:hover {
            background: var(--primary-hover, #5a6fd8);
        }

        .privacy-footer {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color, #333333);
        }

        /* Убедимся, что скролл работает */
        html, body {
            overflow-x: hidden;
            overflow-y: auto;
        }

        .privacy-content {
            overflow: visible;
            min-height: auto;
        }

        /* Адаптивность для мобильных */
        @media (max-width: 768px) {
            .privacy-header {
                padding: 0.4rem 1rem;
                min-height: 45px;
            }
            
            .privacy-header .logo {
                font-size: 1rem;
            }
            
            .privacy-content {
                padding: 1rem;
            }
            
            .privacy-section {
                padding: 1rem;
                margin-bottom: 1rem;
            }
            
            .privacy-title {
                font-size: 1.4rem;
                margin-bottom: 1rem;
            }
            
            .privacy-section h2 {
                font-size: 1.1rem;
            }
        }

        /* Плавное появление */
        .privacy-container {
            opacity: 0;
            animation: fadeIn 0.5s ease forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="privacy-container">
        <header class="privacy-header">
            <div class="logo-container">
                <img src="/images/Logo3.svg" alt="dfdf">
                <div class="text">
                    <span class="tech">Tech</span>
                    <span class="talks"></span>
                </div>
            </div>
        </header>

        <main class="privacy-content">
            <h1 class="privacy-title">Политика конфиденциальности</h1>
            
            <div class="privacy-section">
                <h2><i class="fas fa-shield-alt"></i> 1. Основные принципы конфиденциальности</h2>
                <p>1.1. Сервис TechTalks (далее — «Сервис») построен на принципах максимальной конфиденциальности и безопасности пользовательских данных.</p>
                <p>1.2. Мы применяем передовые технологии шифрования и защиты информации для обеспечения полной конфиденциальности ваших коммуникаций.</p>
                <p>1.3. Основной принцип нашей работы: <strong>минимальное хранение данных, максимальная защита</strong>.</p>
            </div>

            <div class="privacy-section">
                <h2><i class="fas fa-lock"></i> 2. Сквозное шифрование</h2>
                <p>2.1. Все сообщения в Сервисе защищены <strong>сквозным шифрованием (End-to-End Encryption)</strong>.</p>
                <p>2.2. Ключи шифрования генерируются и хранятся исключительно на устройствах пользователей.</p>
                <p>2.3. Сервис технически не имеет доступа к содержимому ваших сообщений — они шифруются на вашем устройстве и расшифровываются только на устройстве получателя.</p>
                <p>2.4. Используемые алгоритмы шифрования: 
                    <span class="security-badge">AES-256</span>
                    <span class="security-badge">RSA-2048</span>
                    <span class="security-badge">SHA-256</span>
                </p>
            </div>

            <div class="privacy-section">
                <h2><i class="fas fa-database"></i> 3. Сбор и хранение данных</h2>
                <p>3.1. <strong>Минимальная коллекция данных</strong> — мы собираем только те данные, которые необходимы для функционирования Сервиса:</p>
                <ul class="privacy-list">
                    <li>Идентификатор пользователя (анонимный)</li>
                    <li>Хэшированный логин</li>
                    <li>Дата регистрации</li>
                    <li>Публичный ключ шифрования</li>
                </ul>
                <p>3.2. <strong>Мы НЕ собираем и не храним</strong>:</p>
                <ul class="privacy-list">
                    <li>Содержание сообщений (только в зашифрованном виде)</li>
                    <li>Метаданные сообщений (кто, кому, когда)</li>
                    <li>IP-адреса пользователей</li>
                    <li>Геолокационные данные</li>
                    <li>Информацию об устройствах</li>
                    <li>Историю подключений</li>
                </ul>
            </div>

            <div class="privacy-section">
                <h2><i class="fas fa-ban"></i> 4. Запрет передачи данных третьим лицам</h2>
                <p>4.1. Мы <strong>не передаем и не продаем</strong> пользовательские данные третьим лицам.</p>
                <p>4.2. <strong>Нет доступа для рекламных сетей</strong> — Сервис не интегрирован с рекламными платформами и трекерами.</p>
                <p>4.3. <strong>Нет аналитических систем</strong> — мы не используем системы аналитики, отслеживающие поведение пользователей.</p>
                <p>4.4. <strong>Нет внешних API</strong> — Сервис работает изолированно без интеграций с внешними сервисами.</p>
            </div>

            <div class="privacy-section">
                <h2><i class="fas fa-user-secret"></i> 5. Анонимность и псевдонимизация</h2>
                <p>5.1. Регистрация осуществляется без привязки к реальной личности — используются только псевдонимы.</p>
                <p>5.2. Идентификаторы пользователей генерируются случайным образом и не содержат персональной информации.</p>
                <p>5.3. Для входа в Сервис не требуется предоставление телефона или других контактных данных.</p>
                <p>5.4. Для входа в Сервис будет требоваться email <strong>исключительно</strong> для контроля над аккаутом пользователя.</p>
            </div>

            <div class="privacy-section">
                <h2><i class="fas fa-fire"></i> 6. Уничтожение данных и защита от требований третьих лиц</h2>
                <p>6.1. <strong>Принцип нулевого хранения</strong> — сообщения автоматически удаляются из базы данных после доставки получателям.</p>
                <p>6.2. <strong>Техническая невозможность предоставления данных</strong> — в связи с использованием сквозного шифрования, мы технически не можем предоставить содержимое сообщений даже по официальным запросам.</p>
                <p>6.3. <strong>Протокол уничтожения данных при внешних угрозах</strong>:</p>
                <ul class="privacy-list">
                    <li>При получении неправомерных требований о предоставлении данных</li>
                    <li>При попытках несанкционированного доступа к системам</li>
                    <li> По требованию пользователей о полном удалении их данных</li>
                </ul>
                <p>6.4. <strong>Процесс безопасного удаления</strong>:</p>
                <ul class="privacy-list">
                    <li>Многократная перезапись данных случайными значениями</li>
                    <li>Физическое уничтожение резервных копий</li>
                    <li>Очистка системных логов</li>
                    <li>Уничтожение временных файлов</li>
                </ul>
            </div>

            <div class="privacy-section">
                <h2><i class="fas fa-shield-virus"></i> 7. Защита от утечек и атак</h2>
                <p>7.1. <strong>Регулярные аудиты безопасности</strong> — проводим регулярные проверки систем на уязвимости.</p>
                <p>7.2. <strong>Защита от DDoS-атак</strong> — используем многоуровневую систему защиты от распределенных атак.</p>
                <p>7.3. <strong>Шифрование данных на дисках</strong> — все данные на серверах хранятся в зашифрованном виде.</p>
                <p>7.4. <strong>Протокол действий при утечке данных</strong> — в случае потенциальной утечки немедленно уведомляем пользователей и предпринимаем меры по защите.</p>
            </div>

            <div class="privacy-section">
                <h2><i class="fas fa-key"></i> 8. Контроль пользователей над данными</h2>
                <p>8.1. <strong>Право на удаление аккаунта</strong> — пользователь может в любой момент полностью удалить свой аккаунт со всеми данными.</p>
                <p>8.2. <strong>Экспорт данных</strong> — предоставляем возможность экспорта ваших данных в читаемом формате, кроме пароля.</p>
                <p>8.3. <strong>Просмотр хранимой информации</strong> — пользователь может запросить информацию о том, какие его данные хранятся в системе.</p>
            </div>

            <div class="privacy-section">
                <h2><i class="fas fa-server"></i> 9. Технические меры защиты</h2>
                <p>9.1. <strong>Физическая безопасность серверов</strong> — оборудование размещено в защищенных дата-центрах с круглосуточной охраной.</p>
                <p>9.2. <strong>Сетевая безопасность</strong>:</p>
                <ul class="privacy-list">
                    <li>Firewall следующего поколения</li>
                    <li>Системы обнаружения вторжений (IDS/IPS)</li>
                    <li>Защита от SQL-инъекций</li>
                    <li>Регулярное обновление security-патчей</li>
                </ul>
                <p>9.3. <strong>Резервное копирование с шифрованием</strong> — резервные копии создаются в зашифрованном виде и хранятся отдельно от основных систем.</p>
            </div>

            <div class="privacy-section">
                <h2><i class="fas fa-gavel"></i> 10. Юридические аспекты</h2>
                <p>10.1. <strong>Соответствие законодательству</strong> — деятельность Сервиса осуществляется в соответствии с законодательством о защите персональных данных.</p>
                <p>10.2. <strong>Ответ на официальные запросы</strong> — при получении законных судебных запросов мы предоставляем только ту информацию, которая технически доступна и не нарушает принципы конфиденциальности.</p>
                <p>10.3. <strong>Уведомление пользователей</strong> — в случае получения законных требований о предоставлении данных, мы уведомляем affected пользователей, если это не запрещено законом.</p>
            </div>

            <div class="privacy-section">
                <h2><i class="fas fa-undo"></i> 11. Изменение политики конфиденциальности</h2>
                <p>11.1. Уведомление об изменениях — обо всех изменениях в политике конфиденциальности мы уведомляем пользователей за 30 дней до их вступления в силу.</p>
                <p>11.2. Сохранение прав — изменения не имеют обратной силы и не затрагивают уровень защиты данных, собранных до изменений.</p>
                <p>11.3. Право на отказ — если пользователь не согласен с изменениями, он может удалить свой аккаунт до вступления изменений в силу.</p>
            </div>

            <div class="privacy-section">
                <h2><i class="fas fa-headset"></i> 12. Контакты и обратная связь</h2>
                <p>12.1. По всем вопросам, связанным с конфиденциальностью и защитой данных, вы можете обращаться через систему обратной связи Сервиса.</p>
                <p>12.2. Срок рассмотрения обращений — 10 рабочих дней.</p>
                <p>12.3. Мы ценим ваше мнение и постоянно работаем над улучшением системы защиты.</p>
            </div>

            <div class="privacy-footer">
                <a href="/" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    Вернуться на главную
                </a>
                <p style="margin-top: 1rem; color: var(--text-secondary, #888888); font-size: 0.85rem;">
                    Политика конфиденциальности • Дата последнего обновления: 16.10.2025
                </p>
            </div>
        </main>
    </div>

    <script>
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
            document.addEventListener('DOMContentLoaded', function() {
            console.log('Privacy policy page loaded successfully');
            
            // Проверяем CSS переменные
            const root = document.documentElement;
            const primaryColor = getComputedStyle(root).getPropertyValue('--primary-color').trim();
            
            if (!primaryColor) {
                console.log('CSS variables not found, setting defaults');
                root.style.setProperty('--bg-primary', '#0f0f0f');
                root.style.setProperty('--sidebar-bg', '#1a1a1a');
                root.style.setProperty('--primary-color', '#667eea');
                root.style.setProperty('--primary-hover', '#5a6fd8');
                root.style.setProperty('--text-primary', '#ffffff');
                root.style.setProperty('--text-secondary', '#888888');
                root.style.setProperty('--border-color', '#333333');
            }
        });
    </script>
</body>
</html>