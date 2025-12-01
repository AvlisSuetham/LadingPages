<?php
// importlanding.php
// Recebe upload de um arquivo .zip com um site inteiro, extrai para /sites/<slug>-<ts>
// Procura por arquivos de metadados (data.json, produto.json, produtos.json) dentro do ZIP
// Mescla registros em ./data.json (na raiz)

// ---------- Configurações ----------
$sitesDir = __DIR__ . '/sites';
$dataFile = __DIR__ . '/data.json';
@set_time_limit(120);

// garante existência
if (!is_dir($sitesDir)) @mkdir($sitesDir, 0755, true);
if (!file_exists($dataFile)) file_put_contents($dataFile, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

function esc($s){ return htmlspecialchars($s ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function slugify($text){
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return $text ?: 'site';
}

// segurança simples: bloqueia uploads grandes (ajuste conforme necessário)
$maxUploadBytes = 50 * 1024 * 1024; // 50MB

$uploadError = null;
$importLog = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_FILES['zipfile']) || $_FILES['zipfile']['error'] !== UPLOAD_ERR_OK) {
        $uploadError = 'Erro no upload do arquivo.';
    } else {
        $file = $_FILES['zipfile'];
        if ($file['size'] > $maxUploadBytes) {
            $uploadError = 'Arquivo muito grande. Limite: ' . ($maxUploadBytes/1024/1024) . ' MB';
        } else {
            $origName = $file['name'];
            $tmpPath = $file['tmp_name'];
            $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
            if ($ext !== 'zip') {
                $uploadError = 'Envie um arquivo .zip válido.';
            } else {
                $base = pathinfo($origName, PATHINFO_FILENAME);
                $safeBase = slugify($base) . '-' . time();
                $targetDir = $sitesDir . '/' . $safeBase;

                if (!mkdir($targetDir, 0755, true) && !is_dir($targetDir)) {
                    $uploadError = 'Falha ao criar pasta de destino.';
                } else {
                    $zip = new ZipArchive();
                    if ($zip->open($tmpPath) === true) {
                        // verifica entradas para evitar path traversal
                        for ($i = 0; $i < $zip->numFiles; $i++) {
                            $stat = $zip->statIndex($i);
                            $entry = $stat['name'];
                            if (strpos($entry, '..') !== false || strpos($entry, ':') !== false) {
                                $uploadError = 'ZIP contém caminhos inválidos.';
                                break;
                            }
                        }

                        if (!$uploadError) {
                            if (!$zip->extractTo($targetDir)) {
                                $uploadError = 'Falha ao extrair o ZIP.';
                            }
                        }

                        $zip->close();

                        if (!$uploadError) {
                            // procura arquivos de metadados dentro do targetDir
                            $foundDataFiles = [];
                            $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($targetDir, FilesystemIterator::SKIP_DOTS));
                            foreach ($it as $f) {
                                if ($f->isFile()) {
                                    $fn = strtolower($f->getFilename());
                                    if (in_array($fn, ['data.json', 'produto.json', 'produtos.json'])) {
                                        $foundDataFiles[] = $f->getPathname();
                                    }
                                }
                            }

                            // lê JSON com segurança
                            $readJson = function($path){
                                $txt = @file_get_contents($path);
                                $j = @json_decode($txt, true);
                                return is_array($j) ? $j : null;
                            };

                            $toMerge = [];

                            if (!empty($foundDataFiles)) {
                                foreach ($foundDataFiles as $df) {
                                    $j = $readJson($df);
                                    if ($j !== null) {
                                        // se for objeto associativo -> item único
                                        if (array_keys($j) !== range(0, count($j)-1)) {
                                            $toMerge[] = $j;
                                        } else {
                                            // array -> múltiplos produtos
                                            foreach ($j as $e) if (is_array($e)) $toMerge[] = $e;
                                        }
                                    }
                                }
                            }

                            // se não achar metadados, cria um registro padrão
                            if (empty($toMerge)) {
                                // tenta achar primeira imagem como thumbnail
                                $thumb = null;
                                foreach ($it as $f) {
                                    if ($f->isFile()) {
                                        $e = strtolower($f->getExtension());
                                        if (in_array($e, ['png','jpg','jpeg','webp','gif'])) {
                                            $full = $f->getPathname();
                                            // cria caminho relativo para uso front (sites/<dir>/path)
                                            $rel = 'sites/' . basename($targetDir) . '/' . str_replace('\\', '/', substr($full, strlen(__DIR__) + 1));
                                            $thumb = $rel;
                                            break;
                                        }
                                    }
                                }

                                $toMerge[] = [
                                    'id' => uniqid('p_'),
                                    'nome' => $base,
                                    'descricao' => 'Landing page importada: ' . $base,
                                    'preco' => '',
                                    'caminho' => 'sites/' . basename($targetDir) . '/index.html',
                                    'imagem' => $thumb,
                                    'meta' => ['imported_at' => date('c'), 'source_file' => $origName]
                                ];
                            } else {
                                // normaliza entradas (garante id e caminho relativo quando necessário)
                                foreach ($toMerge as &$entry) {
                                    if (!isset($entry['id']) || !trim($entry['id'])) $entry['id'] = uniqid('p_');
                                    if (!isset($entry['caminho']) || !trim($entry['caminho'])) {
                                        $entry['caminho'] = 'sites/' . basename($targetDir) . '/index.html';
                                    } else {
                                        // se for caminho relativo (não iniciar com http(s) nem 'sites/'), prefixa com sites/<dir>/
                                        if (!preg_match('#^https?://#', $entry['caminho']) && strpos($entry['caminho'], 'sites/') !== 0) {
                                            $entry['caminho'] = 'sites/' . basename($targetDir) . '/' . ltrim($entry['caminho'], "./");
                                        }
                                    }

                                    if (!isset($entry['imagem']) || !trim($entry['imagem'])) {
                                        // tenta achar imagem dentro do site
                                        foreach ($it as $f2) {
                                            if ($f2->isFile()) {
                                                $ext = strtolower($f2->getExtension());
                                                if (in_array($ext, ['png','jpg','jpeg','webp','gif'])) {
                                                    $full = $f2->getPathname();
                                                    $rel = 'sites/' . basename($targetDir) . '/' . str_replace('\\', '/', substr($full, strlen(__DIR__) + 1));
                                                    $entry['imagem'] = $rel;
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }
                                unset($entry);
                            }

                            // mescla toMerge em data.json
                            $existing = json_decode(file_get_contents($dataFile), true) ?: [];
                            // index existing por id e por caminho para evitar duplicatas
                            $index = [];
                            foreach ($existing as $e) {
                                if (isset($e['id'])) $index[$e['id']] = true;
                                if (isset($e['caminho'])) $index[$e['caminho']] = true;
                            }

                            foreach ($toMerge as $new) {
                                // se id ou caminho já existe -> atualiza (aqui simples: se existir por id/caminho, substitui)
                                $replaced = false;
                                foreach ($existing as &$ex) {
                                    if ((isset($new['id']) && isset($ex['id']) && $ex['id'] === $new['id']) ||
                                        (isset($new['caminho']) && isset($ex['caminho']) && $ex['caminho'] === $new['caminho'])) {
                                        $ex = array_merge($ex, $new);
                                        $replaced = true;
                                        break;
                                    }
                                }
                                if (!$replaced) $existing[] = $new;
                            }

                            // salva com trava
                            $fp = fopen($dataFile, 'c+');
                            if ($fp && flock($fp, LOCK_EX)) {
                                ftruncate($fp, 0);
                                fwrite($fp, json_encode($existing, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                                fflush($fp);
                                flock($fp, LOCK_UN);
                                fclose($fp);
                            }

                            $importLog[] = 'Importação concluída para: ' . basename($targetDir);
                        }
                    } else {
                        $uploadError = 'Não foi possível abrir o arquivo ZIP.';
                    }
                }
            }
        }
    }
}

// HTML do formulário
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Importar Landing Page (.zip)</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="h4">Importar .zip (site completo)</h1>
      <a class="btn btn-secondary" href="admin.php">Voltar ao painel</a>
    </div>

    <?php if ($uploadError): ?>
      <div class="alert alert-danger"><?= esc($uploadError) ?></div>
    <?php endif; ?>

    <?php if (!empty($importLog)): ?>
      <div class="alert alert-success">
        <ul>
          <?php foreach ($importLog as $l): ?>
            <li><?= esc($l) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-label">Arquivo .zip</label>
        <input type="file" name="zipfile" accept=".zip" class="form-control" required>
        <div class="form-text">Envie um arquivo .zip contendo o site (index.html). O conteúdo será extraído para a pasta /sites.</div>
      </div>

      <button class="btn btn-primary">Enviar e importar</button>
    </form>

    <hr>
    <h5>Observações técnicas</h5>
    <ul>
      <li>O script procura por <code>data.json</code>, <code>produto.json</code> ou <code>produtos.json</code> dentro do ZIP e usa esses metadados quando presentes.</li>
      <li>Se nenhum metadado for encontrado, será criado um registro padrão com <code>caminho</code> para <code>sites/&lt;dir&gt;/index.html</code>.</li>
      <li>Para atualizar um produto existente, inclua o mesmo campo <code>id</code> ou <code>caminho</code> no arquivo de metadados encontrado no ZIP.</li>
      <li>O script evita extração de caminhos com <code>..</code> (verificação simples).</li>
    </ul>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>