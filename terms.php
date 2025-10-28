<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Пользовательское соглашение - TechTalks</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/style.css">
    <link rel="icon" type="image/x-icon" href="/images/favico.ico">
    <style>
        /* Сброс стилей для страницы соглашения */
        body {
            margin: 0;
            padding: 0;
            background: var(--bg-primary, #0f0f0f);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-primary, #ffffff);
            min-height: 100vh;
        }

        .terms-container {
            min-height: 100vh;
            background: var(--bg-primary, #0f0f0f);
            display: flex;
            flex-direction: column;
        }

        /* УМЕНЬШЕННЫЙ HEADER */
        .terms-header {
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

        .terms-header .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary-color, #667eea);
            font-weight: 600;
            font-size: 1.1rem;
        }

        .terms-header .logo i {
            font-size: 1.2rem;
        }

        .terms-content {
            flex: 1;
            max-width: 800px;
            margin: 0 auto;
            padding: 1.5rem 2rem;
            background: var(--bg-primary, #0f0f0f);
            overflow-y: visible;
        }

        .terms-title {
            color: var(--text-primary, #ffffff);
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 1.6rem;
        }

        .terms-section {
            margin-bottom: 1.5rem;
            padding: 1.25rem;
            background: var(--sidebar-bg, #1a1a1a);
            border-radius: 8px;
            border: 1px solid var(--border-color, #333333);
        }

        .terms-section h2 {
            color: var(--primary-color, #667eea);
            margin-bottom: 0.75rem;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .terms-section h2 i {
            font-size: 1rem;
        }

        .terms-section p {
            color: var(--text-secondary, #888888);
            line-height: 1.6;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
        }

        .terms-list {
            color: var(--text-secondary, #888888);
            margin: 0.5rem 0 1rem 1rem;
            padding-left: 1rem;
        }

        .terms-list li {
            margin-bottom: 0.5rem;
            line-height: 1.5;
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

        .terms-footer {
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

        .terms-content {
            overflow: visible;
            min-height: auto;
        }

        /* Адаптивность для мобильных */
        @media (max-width: 768px) {
            .terms-header {
                padding: 0.4rem 1rem;
                min-height: 45px;
            }
            
            .terms-header .logo {
                font-size: 1rem;
            }
            
            .terms-content {
                padding: 1rem;
            }
            
            .terms-section {
                padding: 1rem;
                margin-bottom: 1rem;
            }
            
            .terms-title {
                font-size: 1.4rem;
                margin-bottom: 1rem;
            }
            
            .terms-section h2 {
                font-size: 1.1rem;
            }
        }

        /* Плавное появление */
        .terms-container {
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
    <div class="terms-container">
        <header class="terms-header">
            <div class="logo-container">
                <img src="images/Logo3.svg" alt="dfdf">
                <div class="text">
                    <span class="tech">Tech</span>
                    <span class="talks"></span>
                </div>
            </div>
        </header>

        <main class="terms-content">
            <h1 class="terms-title">Пользовательское соглашение</h1>
            
            <div class="terms-section">
                <h2><i class="fas fa-file-contract"></i> 1. Общие положения</h2>
                <p>1.1. Настоящее Пользовательское соглашение (далее — «Соглашение») регулирует отношения между администрацией сервиса TechTalks (далее — «Сервис», «Администрация») и физическим лицом (далее — «Пользователь»), использующим Сервис.</p>
                <p>1.2. Используя Сервис TechTalks, Пользователь подтверждает свое согласие с условиями настоящего Соглашения в полном объеме.</p>
                <p>1.3. В случае несогласия Пользователя с какими-либо из положений Соглашения, Пользователь не вправе использовать Сервис.</p>
            </div>

            <div class="terms-section">
                <h2><i class="fas fa-user-shield"></i> 2. Условия использования</h2>
                <p>2.1. Пользователь обязуется использовать Сервис только в законных целях и способами, не нарушающими права третьих лиц.</p>
                <p>2.2. Запрещается размещение, передача, распространение контента, который является незаконным, угрожающим, оскорбительным, клеветническим, нарушающим авторские права.</p>
                <p>2.3. Пользователь самостоятельно несет ответственность за сохранность своих учетных данных (логина и пароля) для доступа к Сервису.</p>
                <p>2.4. Пользователь соглашается не использовать Сервис для рассылки спама, вредоносного программного обеспечения или любого другого нежелательного контента.</p>
            </div>

            <div class="terms-section">
                <h2><i class="fas fa-lock"></i> 3. Конфиденциальность и безопасность</h2>
                <p>3.1. Сервис обеспечивает сквозное шифрование сообщений для защиты конфиденциальности переписки.</p>
                <p>3.2. Персональные данные Пользователя обрабатываются в соответствии с Политикой конфиденциальности Сервиса.</p>
                <p>3.3. Пользователь осознает, что технические особенности Сервиса не могут гарантировать абсолютную конфиденциальность и безопасность информации.</p>
            </div>

            <div class="terms-section">
                <h2><i class="fas fa-gavel"></i> 4. Права Администрации Сервиса</h2>
                <p>4.1. Администрация Сервиса вправе осуществлять модерацию контента и действий Пользователей в целях обеспечения безопасности и соблюдения условий настоящего Соглашения.</p>
                <p>4.2. Администрация обладает правом удалять сообщения, комментарии и другой контент, который:</p>
                <ul class="terms-list">
                    <li>Нарушает условия настоящего Соглашения</li>
                    <li>Содержит противоправную информацию</li>
                    <li>Направлен на распространение спама или вредоносного ПО</li>
                    <li>Нарушает права интеллектуальной собственности</li>
                    <li>Содержит угрозы, оскорбления или дискриминацию</li>
                </ul>
                <p>4.3. Администрация вправе применять к Пользователям следующие меры воздействия:</p>
                <ul class="terms-list">
                    <li><strong>Предупреждение</strong> - уведомление о нарушении с требованием прекратить противоправные действия</li>
                    <li><strong>Временная блокировка (заморозка аккаунта)</strong> - ограничение доступа к Сервису на определенный срок</li>
                    <li><strong>Перманентная блокировка (бан)</strong> - полное прекращение доступа к Сервису</li>
                </ul>
                <p>4.4. Основаниями для применения мер воздействия являются:</p>
                <ul class="terms-list">
                    <li>Систематическое нарушение условий Соглашения</li>
                    <li>Распространение противоправного контента</li>
                    <li>Попытки взлома или нарушения работы Сервиса</li>
                    <li>Создание помех в работе Сервиса для других пользователей</li>
                    <li>Иные грубые нарушения условий использования</li>
                </ul>
                <p>4.5. В случае грубых нарушений Пользовательского соглашения, Администрация вправе применить меры воздействия без предварительного предупреждения.</p>
                <p>4.6. Решения Администрации по вопросам модерации и применения мер воздействия являются окончательными, и обжалованию не подлежат.</p>
            </div>

            <div class="terms-section">
                <h2><i class="fas fa-exclamation-triangle"></i> 5. Ограничение ответственности</h2>
                <p>5.1. Администрация Сервиса не несет ответственность за содержание сообщений, передаваемых Пользователями через Сервис.</p>
                <p>5.2. Сервис предоставляется на условиях «как есть». Администрация не гарантирует бесперебойную работу Сервиса и соответствие Сервиса ожиданиям Пользователя.</p>
                <p>5.3. Администрация не несет ответственности за любые прямые или косвенные убытки, возникшие в результате использования или невозможности использования Сервиса.</p>
                <p>5.4. Пользователь самостоятельно несет ответственность за все действия, совершенные с использованием его учетной записи.</p>
            </div>

            <div class="terms-section">
                <h2><i class="fas fa-ban"></i> 6. Запрещенная деятельность</h2>
                <p>6.1. Запрещается использование Сервиса для распространения спама, вирусов, вредоносного программного обеспечения.</p>
                <p>6.2. Запрещается попытка несанкционированного доступа к данным других пользователей или к системам Сервиса.</p>
                <p>6.3. Запрещается использование Сервиса для целей, нарушающих законодательство Российской Федерации.</p>
                <p>6.4. Запрещается создание помех в работе Сервиса, включая DDoS-атаки, попытки взлома и иные действия, направленные на нарушение функционирования платформы.</p>
                <p>6.5. Запрещается размещение контента, содержащего:</p>
                <ul class="terms-list">
                    <li>Порнографические материалы</li>
                    <li>Призывы к насилию и экстремизму</li>
                    <li>Информацию о способах совершения преступлений</li>
                    <li>Персональные данные третьих лиц без их согласия</li>
                    <li>Коммерческую рекламу без разрешения Администрации</li>
                </ul>
            </div>

            <div class="terms-section">
                <h2><i class="fas fa-sync-alt"></i> 7. Изменения в соглашении</h2>
                <p>7.1. Администрация оставляет за собой право в одностороннем порядке изменять условия настоящего Соглашения.</p>
                <p>7.2. Изменения вступают в силу с момента их публикации на Сайте, если иной срок не указан в публикации.</p>
                <p>7.3. Продолжение использования Сервиса после внесения изменений означает согласие Пользователя с новыми условиями Соглашения.</p>
            </div>

            <div class="terms-section">
                <h2><i class="fas fa-balance-scale"></i> 8. Интеллектуальная собственность</h2>
                <p>8.1. Все объекты интеллектуальной собственности, связанные с Сервисом, принадлежат Администрации.</p>
                <p>8.2. Пользователь сохраняет права на контент, который он создает и распространяет через Сервис.</p>
                <p>8.3. Предоставляя контент через Сервис, Пользователь гарантирует, что обладает необходимыми правами на его распространение.</p>
            </div>

            <div class="terms-section">
                <h2><i class="fas fa-hourglass-half"></i> 9. Порядок разрешения споров</h2>
                <p>9.1. Все споры и разногласия, возникающие из настоящего Соглашения, подлежат разрешению путем переговоров.</p>
                <p>9.2. В случае невозможности разрешения споров путем переговоров, они подлежат рассмотрению в суде по месту нахождения Администрации Сервиса.</p>
                <p>9.3. Претензионный порядок разрешения споров является обязательным. Срок рассмотрения претензии - 30 календарных дней с момента ее получения.</p>
            </div>

            <div class="terms-section">
                <h2><i class="fas fa-flag"></i> 10. Заключительные положения</h2>
                <p>10.1. Настоящее Соглашение регулируется законодательством Российской Федерации.</p>
                <p>10.2. Все споры подлежат разрешению в соответствии с законодательством Российской Федерации.</p>
                <p>10.3. Если какое-либо положение Соглашения будет признано недействительным, остальные положения сохраняют свою силу.</p>
                <p>10.4. Администрация вправе передавать права и обязанности по настоящему Соглашению третьим лицам в порядке правопреемства.</p>
                <p>10.5. Настоящее Соглашение является публичной офертой в соответствии со статьей 437 Гражданского кодекса Российской Федерации.</p>
            </div>

            <div class="terms-footer">
                <a href="/" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    Вернуться к регистрации
                </a>
                <p style="margin-top: 1rem; color: var(--text-secondary, #888888); font-size: 0.85rem;">
                    Пользовательское соглашение • Дата последнего обновления: 16.10.2025
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
            console.log('Terms page loaded successfully');
            
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