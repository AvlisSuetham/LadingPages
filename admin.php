<?php
session_start();

// Caminho do arquivo de credenciais
$adminFile = __DIR__ . '/admin.json';

// Ler credenciais
$adminData = json_decode(file_get_contents($adminFile), true);
$usuarioCorreto = $adminData['usuario'];
$senhaCorreta   = $adminData['senha'];

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

// Se já estiver logado → mostra painel
$logado = isset($_SESSION['logado']) && $_SESSION['logado'] === true;

// Login
$erro = "";
if (isset($_POST['usuario']) && isset($_POST['senha'])) {
    if ($_POST['usuario'] === $usuarioCorreto && $_POST['senha'] === $senhaCorreta) {
        $_SESSION['logado'] = true;
        header("Location: admin.php");
        exit;
    } else {
        $erro = "Usuário ou senha incorretos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Administração</title>

<style>
:root {
    --bg: #f2f5f7;
    --box-bg: #ffffff;
    --text: #2e2e2e;
    --muted: #6c757d;
    --primary: #4f7cff;
    --primary-hover: #365cff;
    --danger: #dc3545;
    --success: #28a745;
    --radius: 14px;
    --shadow: 0 8px 20px rgba(0,0,0,0.12);
}

body {
    margin: 0;
    padding: 0;
    font-family: "Inter", Arial, sans-serif;
    background: var(--bg);
    color: var(--text);
}

/* Caixa central utilizada tanto no login quanto no painel */
.center-box {
    max-width: 430px;
    margin: 70px auto;
    padding: 40px;
    background: var(--box-bg);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    animation: fade 0.5s ease;
}

@keyframes fade {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}

h2 {
    text-align: center;
    font-weight: 600;
    margin-bottom: 25px;
}

/* Inputs e botões agora perfeitamente iguais */
input {
    width: 100%;
    padding: 14px;
    margin-bottom: 15px;
    border-radius: 10px;
    border: 1px solid #ccc;
    font-size: 15px;
    box-sizing: border-box;
}

.btn {
    width: 100%;
    padding: 14px; /* mesmo padding dos campos */
    border-radius: 10px;
    border: none;
    cursor: pointer;
    font-size: 16px;
    margin-bottom: 12px;
    transition: 0.2s;
    box-sizing: border-box;
}

.btn-primary {
    background: var(--primary);
    color: white;
}
.btn-primary:hover { background: var(--primary-hover); }

.btn-outline {
    border: 1px solid var(--muted);
    background: transparent;
    color: var(--muted);
}
.btn-outline:hover {
    background: var(--muted);
    color: white;
}

.btn-danger {
    background: var(--danger);
    color: white;
}
.btn-danger:hover {
    background: #b02a37;
}

.btn-success {
    background: var(--success);
    color: white;
}
.btn-success:hover {
    background: #1f8b39;
}

/* Mensagem de erro */
.erro {
    background: #ffdadb;
    color: #9b0000;
    padding: 10px;
    border-radius: 10px;
    margin-bottom: 15px;
    text-align: center;
    font-size: 14px;
}

/* Área do painel */
.painel h2 {
    margin-bottom: 30px;
}

.painel .btn {
    font-weight: 500;
}

</style>
</head>
<body>

<?php if (!$logado): ?>

    <!-- LOGIN -->
    <div class="center-box">
        <h2>Login Administrativo</h2>

        <?php if ($erro): ?>
            <div class="erro"><?= $erro ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="usuario" placeholder="Usuário" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button class="btn btn-primary">Entrar</button>
        </form>

        <button class="btn btn-outline" onclick="window.location.href='index.php'">Voltar ao Site</button>
    </div>

<?php else: ?>

    <!-- PAINEL -->
    <div class="center-box painel">
        <h2>Painel Administrativo</h2>

        <button class="btn btn-success" onclick="window.location.href='msg.php'">Mensagens de Clientes</button>
        <button class="btn btn-primary" onclick="window.location.href='importlanding.php'">Adicionar Produto</button>
        <button class="btn btn-outline" onclick="window.location.href='index.php'">Voltar ao Site</button>
        <button class="btn btn-danger" onclick="window.location.href='admin.php?logout=1'">Logout</button>
    </div>

<?php endif; ?>

</body>
</html>