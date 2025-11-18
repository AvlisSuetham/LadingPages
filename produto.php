<?php
// produtos.php
// Lê os dados do data.json e lista os produtos

$data_file = __DIR__ . '/data.json';

$produtos = [];
if (file_exists($data_file)) {
    $json = file_get_contents($data_file);
    $produtos = json_decode($json, true);
    if (!is_array($produtos)) {
        $produtos = [];
    }
}
?>

<?php include 'header.php'; ?>

<main class="container my-5">

    <h1 class="mb-4">Nossos Produtos</h1>

    <div class="row g-4">

        <?php if (empty($produtos)): ?>
            <p>Nenhum produto cadastrado.</p>
        <?php else: ?>
            <?php foreach ($produtos as $p): ?>
                <div class="col-md-4 col-lg-3">
                    <div class="card shadow-sm h-100">
                        <img src="<?php echo htmlspecialchars($p['imagem']); ?>" 
                             class="card-img-top" alt="Imagem do produto">

                        <div class="card-body d-flex flex-column">

                            <h5 class="card-title">
                                <?php echo htmlspecialchars($p['nome']); ?>
                            </h5>

                            <p class="card-text text-muted">
                                <?php echo htmlspecialchars($p['descricao']); ?>
                            </p>

                            <strong class="mb-3">R$ 
                                <?php echo htmlspecialchars($p['preco']); ?>
                            </strong>

                            <a href="<?php echo htmlspecialchars($p['caminho']); ?>"
                               target="_blank"
                               class="btn btn-primary mt-auto">
                                Acessar
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
</main>

<?php include 'footer.php'; ?>
