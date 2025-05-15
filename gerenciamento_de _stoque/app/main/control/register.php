<?php
header('Content-Type: application/json');

try {
    // Conexão com o banco de dados
    $pdo = new PDO("mysql:host=localhost;dbname=your_database", "your_username", "your_password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Inclui o modelo
    require_once '../model/model.functions.php';
    $user = new register($pdo);

    // Obtém os dados do formulário enviados pela View
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Validações básicas
    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Por favor, preencha todos os campos.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Por favor, insira um email válido.']);
        exit;
    }

    if (strlen($password) < 6) {
        echo json_encode(['success' => false, 'message' => 'A senha deve ter pelo menos 6 caracteres.']);
        exit;
    }

    // Verifica se o email já está registrado
    if ($user->findByEmail($email)) {
        echo json_encode(['success' => false, 'message' => 'Este email já está em uso.']);
        exit;
    }

    // Define os dados no modelo e salva
    $user->setEmail($email);
    $user->setPassword(password_hash($password, PASSWORD_DEFAULT));

    if ($user->save()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao criar conta. Tente novamente.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro de conexão. Tente novamente mais tarde.']);
}
?>