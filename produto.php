<?php include 'header.php'; ?>

<main class="container my-5">

    <!-- HERO / INTRO -->
    <section class="text-center mb-5">
        <h1 class="h2">Catálogo de Landing Pages Pré-Prontas</h1>
        <p class="text-muted">
            Escolha entre dezenas de modelos profissionais, prontos para uso e totalmente editáveis.
        </p>
    </section>

    <!-- FILTROS -->
    <section class="mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h4 class="mb-3">Modelos disponíveis</h4>

            <div class="d-flex gap-2">
                <select class="form-select" id="filterCategory" style="width: 180px;">
                    <option value="">Categoria</option>
                    <option value="curso">Cursos</option>
                    <option value="clinica">Clínicas</option>
                    <option value="saas">SaaS</option>
                    <option value="restaurante">Restaurante</option>
                    <option value="loja">Loja Virtual</option>
                </select>

                <select class="form-select" id="filterPrice" style="width: 180px;">
                    <option value="">Preço</option>
                    <option value="low">Até R$49</option>
                    <option value="mid">R$50 a R$99</option>
                    <option value="high">Acima de R$100</option>
                </select>
            </div>
        </div>
    </section>

    <!-- GRID DE PRODUTOS -->
    <section class="row g-4" id="productGrid">

        <!-- PRODUTO 1 -->
        <div class="col-md-4 product-card" data-category="curso" data-price="mid">
            <div class="card shadow-sm h-100">
                <img src="https://images.unsplash.com/photo-1522199710521-72d69614c702?auto=format&fit=crop&w=870&q=60" class="card-img-top" alt="Modelo Curso">
                <div class="card-body">
                    <h5 class="card-title">Landing Page para Curso</h5>
                    <p class="card-text small text-muted">
                        Estrutura focada em conversão, ideal para cursos e mentorias.
                    </p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <strong class="text-primary fs-5">R$ 79</strong>
                        <a href="#" class="btn btn-outline-primary btn-sm">Ver detalhes</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- PRODUTO 2 -->
        <div class="col-md-4 product-card" data-category="clinica" data-price="high">
            <div class="card shadow-sm h-100">
                <img src="https://images.unsplash.com/photo-1522199710521-72d69614c702?auto=format&fit=crop&w=870&q=60" class="card-img-top" alt="Modelo Clínica">
                <div class="card-body">
                    <h5 class="card-title">Landing Page para Clínica</h5>
                    <p class="card-text small text-muted">
                        Modelo elegante com foco em agendamento via WhatsApp.
                    </p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <strong class="text-primary fs-5">R$ 129</strong>
                        <a href="#" class="btn btn-outline-primary btn-sm">Ver detalhes</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- PRODUTO 3 -->
        <div class="col-md-4 product-card" data-category="saas" data-price="mid">
            <div class="card shadow-sm h-100">
                <img src="https://images.unsplash.com/photo-1522199710521-72d69614c702?auto=format&fit=crop&w=870&q=60" class="card-img-top" alt="Modelo SaaS">
                <div class="card-body">
                    <h5 class="card-title">Landing Page para SaaS / App</h5>
                    <p class="card-text small text-muted">
                        Layout moderno com seções de features, preços e CTA principal.
                    </p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <strong class="text-primary fs-5">R$ 89</strong>
                        <a href="#" class="btn btn-outline-primary btn-sm">Ver detalhes</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- PRODUTO 4 -->
        <div class="col-md-4 product-card" data-category="restaurante" data-price="low">
            <div class="card shadow-sm h-100">
                <img src="https://images.unsplash.com/photo-1522199710521-72d69614c702?auto=format&fit=crop&w=870&q=60" class="card-img-top" alt="Modelo Restaurante">
                <div class="card-body">
                    <h5 class="card-title">Landing Page para Restaurante</h5>
                    <p class="card-text small text-muted">
                        Ideal para cardápio digital e pedidos via link.
                    </p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <strong class="text-primary fs-5">R$ 49</strong>
                        <a href="#" class="btn btn-outline-primary btn-sm">Ver detalhes</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- PRODUTO 5 -->
        <div class="col-md-4 product-card" data-category="loja" data-price="high">
            <div class="card shadow-sm h-100">
                <img src="https://images.unsplash.com/photo-1522199710521-72d69614c702?auto=format&fit=crop&w=870&q=60" class="card-img-top" alt="Modelo Loja Virtual">
                <div class="card-body">
                    <h5 class="card-title">Landing Page para Loja Virtual</h5>
                    <p class="card-text small text-muted">
                        Modelo perfeito para coleções, produtos e promoções.
                    </p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <strong class="text-primary fs-5">R$ 119</strong>
                        <a href="#" class="btn btn-outline-primary btn-sm">Ver detalhes</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- PRODUTO 6 -->
        <div class="col-md-4 product-card" data-category="loja" data-price="high">
            <div class="card shadow-sm h-100">
                <img src="https://images.unsplash.com/photo-1522199710521-72d69614c702?auto=format&fit=crop&w=870&q=60" class="card-img-top" alt="Modelo Loja Virtual">
                <div class="card-body">
                    <h5 class="card-title">Landing Page para Loja Virtual</h5>
                    <p class="card-text small text-muted">
                        Modelo perfeito para coleções, produtos e promoções.
                    </p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <strong class="text-primary fs-5">R$ 119</strong>
                        <a href="#" class="btn btn-outline-primary btn-sm">Ver detalhes</a>
                    </div>
                </div>
            </div>
        </div>

    </section>

</main>

<script>
// Filtros simples
document.getElementById('filterCategory').addEventListener('change', filterProducts);
document.getElementById('filterPrice').addEventListener('change', filterProducts);

function filterProducts() {
    const category = document.getElementById('filterCategory').value;
    const price = document.getElementById('filterPrice').value;

    document.querySelectorAll('.product-card').forEach(card => {
        const cardCategory = card.getAttribute('data-category');
        const cardPrice = card.getAttribute('data-price');

        const matchCategory = !category || category === cardCategory;
        const matchPrice = !price || price === cardPrice;

        card.style.display = matchCategory && matchPrice ? 'block' : 'none';
    });
}
</script>

<?php include 'footer.php'; ?>
