    <footer class="py-4 mt-5">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <strong>Sophira Digital</strong><br>
                <small class="text-muted">Agência de landing pages • © <span id="year"></span></small>
            </div>
            <div class="text-end small text-muted">
                <a href="#" class="text-decoration-none">Política</a> • 
                <a href="#" class="text-decoration-none">Termos</a>
            </div>
        </div>
    </footer>

    <!-- Modal de pré-visualização -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewTitle">Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body" id="previewBody" style="min-height:200px"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" onclick="simulateCta()">Simular CTA</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap + Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/home.js"></script>
</body>
</html>