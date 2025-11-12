<?php
include __DIR__ . '/header.php'; // header na raiz

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contactsDir = __DIR__ . '/contacts';
    if (!file_exists($contactsDir)) {
        mkdir($contactsDir, 0777, true);
    }

    // Sanitização e validação
    $nome = htmlspecialchars(trim($_POST['nome'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $celular = htmlspecialchars(trim($_POST['celular'] ?? ''));
    $mensagem = htmlspecialchars(trim($_POST['mensagem'] ?? ''));

    if ($nome && $email && $celular && $mensagem) {
        // Formatação simples do celular (remove tudo que não for número)
        $celularFormatado = preg_replace('/\D/', '', $celular);

        $filename = $contactsDir . '/' . time() . '_' . preg_replace('/[^a-z0-9]/i', '_', strtolower($nome)) . '.json';
        $data = [
            'nome' => $nome,
            'email' => $email,
            'celular' => $celularFormatado,
            'mensagem' => $mensagem,
            'data_envio' => date('Y-m-d H:i:s')
        ];

        file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $sucesso = true;
    } else {
        $erro = 'Por favor, preencha todos os campos.';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contato - Sophira</title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>

    <main class="contact-page">
    <div class="contact-card">
        <h1 class="contact-title">Entre em contato</h1>
        <p class="contact-sub">
        Tem alguma dúvida, sugestão ou deseja conversar conosco? Preencha o formulário abaixo
        e retornaremos o quanto antes.
        </p>

        <?php if (!empty($sucesso)): ?>
        <div class="alert success">Mensagem enviada com sucesso! 💌</div>
        <?php elseif (!empty($erro)): ?>
        <div class="alert error"><?= $erro ?></div>
        <?php endif; ?>

        <form action="contato.php" method="POST" class="contact-form" novalidate>
        <label for="nome">Nome</label>
        <input 
            type="text" 
            name="nome" 
            id="nome" 
            maxlength="60" 
            minlength="3" 
            pattern="[A-Za-zÀ-ÖØ-öø-ÿ\s]+" 
            title="Use apenas letras e espaços." 
            required
        >

        <label for="email">E-mail</label>
        <input 
            type="email" 
            name="email" 
            id="email" 
            maxlength="100" 
            required  
        >

        <label for="celular">Celular</label>
        <input 
            type="tel" 
            name="celular" 
            id="celular" 
            placeholder="(99) 99999-9999" 
            pattern="\(?\d{2}\)?\s?\d{4,5}-?\d{4}" 
            maxlength="15" 
            required
        >

        <label for="mensagem">Mensagem</label>
        <textarea 
            name="mensagem" 
            id="mensagem" 
            rows="5" 
            maxlength="1000" 
            minlength="10" 
            required
        ></textarea>

        <div class="actions">
            <button type="submit">Enviar</button>
        </div>
        </form>
    </div>
    </main>


<?php include __DIR__ . '/footer.php'; ?>
</body>
</html>