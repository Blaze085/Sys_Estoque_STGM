<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Página de criação de conta do sistema EEEP Salaberga">
    <meta name="keywords" content="criar conta, EEEP Salaberga, escola, educação">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTcomDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="icon" href="/img/favicon.svg" type="image/svg+xml">
    <link rel="icon" href="../img/Design sem nome.svg" type="image/x-icon">
    <link rel="icon" href="/img/favicon.png" type="image/png">
    <title>Criar Conta - EEEP Salaberga</title>
    <style>
        :root {
            --primary-color: #4CAF50;
            --secondary-color: #FFB74D;
            --bg-color: #F0F2F5;
            --shadow-color: rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: var(--bg-color);
        }

        .retangulo {
            position: relative;
            width: 900px;
            height: 550px;
            background-color: #fff;
            border-radius: 15px;
            display: flex;
            align-items: center;
            box-shadow: 0 20px 40px var(--shadow-color);
            overflow: hidden;
        }

        .img-gradient {
            width: 45%;
            height: 100%;
            background: linear-gradient(40deg, #005A24, #FFA500);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #fff;
            padding: 2rem;
            text-align: center;
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        p {
            font-size: 1rem;
            line-height: 1.5;
        }

        .form-container {
            width: 55%;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        h2 {
            font-size: 2.5rem;
            font-weight: 900;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .form-control {
            background-color: #f5f5f5;
            border-radius: 5px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            width: 100%;
            margin-bottom: 1rem;
        }

        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-group i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            font-size: 1.2rem;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, #007A33, #FF8C00);
            border: none;
            font-weight: 600;
            font-size: 1.1rem;
            padding: 0.75rem 1rem;
            border-radius: 5px;
            width: 100%;
        }

        .btn-primary:hover,
        .btn-primary:focus,
        .btn-primary:active,
        .btn-primary:focus-visible {
            background: linear-gradient(135deg, #005A24, #FFA500);
            box-shadow: none;
            outline: none;
        }

        .links {
            margin-top: 1.5rem;
            text-align: center;
        }

        .links a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.9rem;
            margin: 0 1rem;
        }

        .links a:hover {
            text-decoration: underline;
            color: #218838;
        }

        .error {
            color: red;
            font-size: 0.9rem;
            margin-top: 0.5rem;
            text-align: center;
        }

        @media (max-width: 768px) {
            .retangulo {
                flex-direction: column;
                width: 90%;
                height: auto;
            }

            .img-gradient {
                width: 100%;
                padding: 1rem;
            }

            .form-container {
                width: 100%;
                padding: 1.5rem;
            }

            h2 {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>
    <div class="retangulo">
        <div class="img-gradient">
            <h1>EEEP Salaberga</h1>
            <p>Transformando o futuro através da educação e inovação</p>
            <img src="/img/salaberga_logo.png" alt="Logo EEEP Salaberga" class="mt-3" style="max-width: 150px;">
        </div>
        <div class="form-container">
            <h2>Criar Conta</h2>
            <form id="register-form" action="../controller/register.php" method="POST">
                <div class="input-group">
                    <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Digite seu email" required aria-required="true">
                    <i class="fas fa-user"></i>
                </div>
                <div class="input-group">
                    <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Digite sua senha" required aria-required="true">
                    <i class="fas fa-eye toggle-password"></i>
                </div>
                <div class="input-group">
                    <input type="password" class="form-control" id="inputConfirmPassword" name="confirmPassword" placeholder="Confirme sua senha" required aria-required="true">
                    <i class="fas fa-eye toggle-confirm-password"></i>
                </div>
                <div class="d-grid">
                    <button class="btn btn-primary" type="submit">Criar Conta</button>
                </div>
                <div class="links">
                    <a href="../index.php">Já tem uma conta? Faça login</a>
                </div>
                <div class="error" id="error-message"></div>
            </form>
        </div>
    </div>
    <script>
        const togglePassword = document.querySelector('.toggle-password');
        const passwordInput = document.getElementById('inputPassword');
        togglePassword.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            togglePassword.classList.toggle('fa-eye');
            togglePassword.classList.toggle('fa-eye-slash');
        });

        const toggleConfirmPassword = document.querySelector('.toggle-confirm-password');
        const confirmPasswordInput = document.getElementById('inputConfirmPassword');
        toggleConfirmPassword.addEventListener('click', () => {
            const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordInput.setAttribute('type', type);
            toggleConfirmPassword.classList.toggle('fa-eye');
            toggleConfirmPassword.classList.toggle('fa-eye-slash');
        });

        const form = document.getElementById('register-form');
        const errorMessage = document.getElementById('error-message');
        const button = form.querySelector('button');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const email = document.getElementById('inputEmail').value.trim();
            const password = document.getElementById('inputPassword').value.trim();
            const confirmPassword = document.getElementById('inputConfirmPassword').value.trim();

            if (!email || !password || !confirmPassword) {
                errorMessage.textContent = 'Por favor, preencha todos os campos.';
                return;
            }

            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                errorMessage.textContent = 'Por favor, insira um email válido.';
                return;
            }

            if (password.length < 6) {
                errorMessage.textContent = 'A senha deve ter pelo menos 6 caracteres.';
                return;
            }

            if (password !== confirmPassword) {
                errorMessage.textContent = 'As senhas não coincidem.';
                return;
            }

            button.textContent = 'Criando...';
            button.disabled = true;
            errorMessage.textContent = '';

            try {
                const response = await fetch('../control/register.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        email: email,
                        password: password
                    })
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = '../index.php';
                } else {
                    errorMessage.textContent = data.message || 'Erro ao criar conta. Tente novamente.';
                }
            } catch (error) {
                errorMessage.textContent = 'Erro de conexão. Tente novamente mais tarde.';
            } finally {
                button.textContent = 'Criar Conta';
                button.disabled = false;
            }
        });
    </script>
</body>

</html>