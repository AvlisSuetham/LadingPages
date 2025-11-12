<?php
include __DIR__ . '/header.php';

$contactsDir = __DIR__ . '/contacts';
$messages = [];

// Lê e ordena as mensagens
if (is_dir($contactsDir)) {
    $files = glob($contactsDir . '/*.json');

    foreach ($files as $file) {
        $content = file_get_contents($file);
        $data = json_decode($content, true);
        if (is_array($data)) {
            $messages[] = $data;
        }
    }

    // Ordena do mais recente pro mais antigo
    usort($messages, function($a, $b) {
        return strtotime($b['data_envio']) - strtotime($a['data_envio']);
    });
}

// ===== PAGINAÇÃO =====
$porPagina = 3;
$total = count($messages);
$paginaAtual = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
$inicio = ($paginaAtual - 1) * $porPagina;
$mensagensPagina = array_slice($messages, $inicio, $porPagina);

$totalPaginas = ceil($total / $porPagina);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mensagens Recebidas - Sophira</title>
  <link rel="stylesheet" href="/styles.css">
  <style>
    .msg-container {
      max-width: 900px;
      margin: 2rem auto;
      padding: 1rem;
    }

    .msg-card {
      background: #fff;
      border: 1px solid #e2e8f0;
      border-radius: .75rem;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .msg-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .msg-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #e5e7eb;
      margin-bottom: .75rem;
      padding-bottom: .5rem;
    }

    .msg-header h2 {
      font-size: 1.1rem;
      margin: 0;
      color: #0f172a;
    }

    .msg-date {
      font-size: .9rem;
      color: #6b7280;
    }

    .msg-body p {
      margin: .5rem 0;
      line-height: 1.6;
      color: #374151;
    }

    .msg-body strong {
      color: #0f172a;
    }

    .empty {
      text-align: center;
      color: #6b7280;
      font-style: italic;
      margin-top: 3rem;
    }

    /* Navegação */
    .pagination {
      display: flex;
      justify-content: center;
      gap: 1rem;
      margin-top: 2rem;
    }

    .pagination a {
      background: linear-gradient(90deg, #0ea5e9, #7c3aed);
      color: #fff;
      text-decoration: none;
      padding: 0.5rem 1rem;
      border-radius: .5rem;
      font-weight: 600;
      transition: opacity 0.2s;
    }

    .pagination a:hover {
      opacity: 0.85;
    }

    .pagination a.disabled {
      opacity: 0.5;
      pointer-events: none;
      cursor: not-allowed;
    }
  </style>
</head>
<body>
  <main class="msg-container">
    <h1 class="text-2xl font-bold mb-6">📨 Mensagens Recebidas</h1>

    <?php if (empty($mensagensPagina)): ?>
      <p class="empty">Nenhuma mensagem recebida até o momento.</p>
    <?php else: ?>
      <?php foreach ($mensagensPagina as $msg): ?>
        <div class="msg-card">
          <div class="msg-header">
            <h2><?= htmlspecialchars($msg['nome']) ?></h2>
            <span class="msg-date"><?= htmlspecialchars($msg['data_envio']) ?></span>
          </div>

          <div class="msg-body">
            <p><strong>Email:</strong> <?= htmlspecialchars($msg['email']) ?></p>
            <p><strong>Celular:</strong> <?= htmlspecialchars($msg['celular'] ?? '—') ?></p>
            <p><strong>Mensagem:</strong><br><?= nl2br(htmlspecialchars($msg['mensagem'])) ?></p>
          </div>
        </div>
      <?php endforeach; ?>

      <?php if ($totalPaginas > 1): ?>
        <div class="pagination">
          <?php if ($paginaAtual > 1): ?>
            <a href="?p=<?= $paginaAtual - 1 ?>">← Anterior</a>
          <?php else: ?>
            <a class="disabled">← Anterior</a>
          <?php endif; ?>

          <?php if ($paginaAtual < $totalPaginas): ?>
            <a href="?p=<?= $paginaAtual + 1 ?>">Próximo →</a>
          <?php else: ?>
            <a class="disabled">Próximo →</a>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </main>

  <?php include __DIR__ . '/footer.php'; ?>
</body>
</html>