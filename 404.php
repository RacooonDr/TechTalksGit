<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 404!</title>
    <link rel="icon" type="image/x-icon" href="/images/favico.ico">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background: #050505;
            font-family: "Montserrat", sans-serif;
            color: #fff;
            height: 100vh;
            overflow: hidden;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            position: relative;
        }
        .logo-container {
            display: flex;
            margin-bottom: 10px;
            align-items: center;
        }
        .logo-container img{
            width:2rem;
            height: 2rem;
        }
        .text{
            font-family: "Poppins", sans-serif;
            font-size: 1rem;
            font-weight: bold;
            display: flex;
            opacity: 0;
            margin-left: 10px
        }
        /* Текст */
        .tech {
            color: #70a8ff;
        }

        /* Talks — скрытое появление */
        .talks {
            display: inline-block;
            overflow: hidden;
            white-space: nowrap;
            color: #b47dff;
            width: auto;
        }
        .error-code {
            font-size: min(30vw, 300px);
            font-weight: 900;
            color: rgba(255, 255, 255, 0.1);
            position: absolute;
            z-index: 1;
        }
        .content {
            text-align: center;
            z-index: 2;
        }
        .cat-img {
            width: min(80vw, 500px);
            height: auto;
            margin-bottom: 20px;
            animation: float 3s ease-in-out infinite;
        }
        h1 {
            font-size: min(10vw, 50px);
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(90deg, #8a2be2, #00bfff);
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: bold;
            transition: transform 0.3s;
        }
        .btn:hover {
            transform: translateY(-3px);
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-code">404</div>
        <div class="content">
            <div class="logo-container">
                <img src="/images/Logo3.svg" alt="dfdf">
                <div class="text">
                    <span class="tech">Tech</span>
                    <span class="talks"></span>
                </div>
            </div>
            <img src="okak-cat.png" alt="cat" class="cat-img">
            <h1>ОКАК</h1>
            <a href="/" class="btn">
                <i class="fas fa-home"></i> Вернуться в мессенджер
            </a>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
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
    </script>
</body>
</html>