<?php
include __DIR__ . '/header.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contactsDir = __DIR__ . '/contacts';
    if (!file_exists($contactsDir)) {
        mkdir($contactsDir, 0777, true);
    }

    $nome = htmlspecialchars(trim($_POST['nome'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $celular = htmlspecialchars(trim($_POST['celular'] ?? ''));
    $mensagem = htmlspecialchars(trim($_POST['mensagem'] ?? ''));

    if ($nome && $email && $celular && $mensagem) {
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
    <title>Receba sua Landing Page – Sophira Digital</title>
    <link rel="stylesheet" href="/styles.css">

    <style>
        .hero {
            text-align: center;
            padding: 60px 20px;
            background: linear-gradient(135deg, #0d0d0d, #1a1a1a);
            color: #fff;
        }

        .hero h1 {
            font-size: 2.8rem;
            margin-bottom: 15px;
        }

        .hero p {
            font-size: 1.2rem;
            opacity: .8;
            max-width: 700px;
            margin: 10px auto 30px;
        }

        .hero .cta {
            background: #14b8a6;
            padding: 14px 26px;
            border-radius: 8px;
            font-size: 1.1rem;
            color: #000;
            display: inline-block;
            font-weight: bold;
        }

        .contact-page {
            display: flex;
            justify-content: center;
            padding: 40px 10px;
        }

        .contact-card {
            background: #fff;
            width: 100%;
            max-width: 520px;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 5px 20px #00000015;
        }

        .contact-title {
            text-align: center;
            margin-bottom: 10px;
        }

        .contact-sub {
            text-align: center;
            margin-bottom: 25px;
            color: #555;
        }

        .contact-form label {
            margin-top: 10px;
            display: block;
            font-weight: 600;
        }

        .contact-form input, .contact-form textarea {
            width: 100%;
            padding: 10px;
            margin-top: 4px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }

        .actions {
            text-align: center;
            margin-top: 20px;
        }

        .actions button {
            background: #14b8a6;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 1.1rem;
            cursor: pointer;
            font-weight: bold;
        }

        .alert {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .alert.success {
            background: #d1fae5;
            color: #065f46;
        }

        .alert.error {
            background: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>

<body>

    <!-- HERO – Chamada Principal -->
    <section class="hero">
        <h1>Aumente Suas Vendas com uma Landing Page Profissional</h1>
        <p>Preencha o formulário e receba um orçamento personalizado para criar uma landing page moderna, rápida e otimizada para conversão, e ainda ganhe um desconto de 15% na primeira compra!</p>
        <a href="#formulario" class="cta">Quero minha landing page</a>
    </section>

    <main class="contact-page">
    <div class="contact-card" id="formulario">
        <h1 class="contact-title">Solicite seu orçamento</h1>
        <p class="contact-sub">
            Preencha seus dados abaixo e nossa equipe entrará em contato rapidamente.
        </p>

        <?php if (!empty($sucesso)): ?>
        <div class="alert success">Seu pedido foi enviado! Em breve entraremos em contato. 🚀</div>
        <?php elseif (!empty($erro)): ?>
        <div class="alert error"><?= $erro ?></div>
        <?php endif; ?>

        <form action="" method="POST" class="contact-form" novalidate>
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
            <button type="submit">Receber orçamento</button>
        </div>
        </form>
    </div>
    </main>

<?php include __DIR__ . '/footer.php'; ?>
</body>
</html>