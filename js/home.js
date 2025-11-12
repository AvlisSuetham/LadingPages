        // Templates simples pra modal
        const templates = {
            course: {
                title: 'Curso Intensivo — Exemplo de landing',
                html: `
        <div style="font-family:system-ui,Segoe UI,Roboto,Arial;padding:12px">
          <div style="background:#0ea5e9;color:white;padding:18px;border-radius:8px">
            <h3 style="margin:0">Curso Intensivo de Marketing</h3>
            <p style="margin:0">Vagas limitadas — Turma mensal</p>
          </div>
          <div style="padding:12px;background:white;border-radius:6px;margin-top:12px">
            <p style="margin:0 0 8px">Aprenda estratégias práticas para captar alunos com anúncios.</p>
            <div style="display:flex;gap:8px">
              <input placeholder="Seu nome" style="flex:1;padding:8px;border:1px solid #e6eef8;border-radius:6px">
              <input placeholder="E-mail" style="flex:1;padding:8px;border:1px solid #e6eef8;border-radius:6px">
              <button style="background:#7c3aed;color:white;border:none;padding:8px 12px;border-radius:6px">Quero matrícula</button>
            </div>
          </div>
        </div>`
            },
            clinic: {
                title: 'Clínica — Exemplo de landing',
                html: `
        <div style="padding:12px;font-family:system-ui,Segoe UI,Roboto,Arial">
          <div style="background:#10b981;color:white;padding:14px;border-radius:8px">
            <h4 style="margin:0">Clínica Sorriso+</h4>
            <small>Agende sua avaliação gratuita</small>
          </div>
          <div style="background:white;padding:12px;border-radius:6px;margin-top:12px">
            <p class="small text-muted">Primeira consulta sem custo — Preencha seus dados</p>
            <div style="display:flex;flex-direction:column;gap:8px">
              <input placeholder="Nome" style="padding:8px;border:1px solid #eef2f7;border-radius:6px">
              <input placeholder="Telefone" style="padding:8px;border:1px solid #eef2f7;border-radius:6px">
              <button style="background:#0ea5e9;color:white;border:none;padding:8px;border-radius:6px">Agendar</button>
            </div>
          </div>
        </div>`
            },
            saas: {
                title: 'SaaS — Exemplo de landing',
                html: `
        <div style="padding:12px;font-family:system-ui,Segoe UI,Roboto,Arial">
          <div style="background:#7c3aed;color:white;padding:14px;border-radius:8px">
            <h4 style="margin:0">FlowCRM — Teste grátis</h4>
            <small>Organize seu funil em minutos</small>
          </div>
          <div style="background:white;padding:12px;border-radius:6px;margin-top:12px">
            <p class="small text-muted">Teste gratuito de 14 dias — sem cartão</p>
            <div style="display:flex;gap:8px">
              <input placeholder="E-mail corporativo" style="flex:1;padding:8px;border:1px solid #eef2f7;border-radius:6px">
              <button style="background:#0ea5e9;color:white;border:none;padding:8px;border-radius:6px">Comece grátis</button>
            </div>
          </div>
        </div>`
            }
        };

        // Abrir modal com template
        document.querySelectorAll('[data-example]').forEach(btn => {
            btn.addEventListener('click', () => {
                const key = btn.getAttribute('data-example');
                const tpl = templates[key];
                document.getElementById('previewTitle').textContent = tpl.title;
                document.getElementById('previewBody').innerHTML = tpl.html;
                const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
                previewModal.show();
            });
        });

        function simulateCta() {
            alert('Simulação: ação de CTA (no projeto real, envia para backend/integrations).');
        }

        // Lead storage (localStorage) + CSV export
        const LEADS_KEY = 'sophira_leads_v1';
        const leadCountEl = document.getElementById('leadCount');
        const feedbackEl = document.getElementById('formFeedback');

        function loadLeads() {
            try {
                const raw = localStorage.getItem(LEADS_KEY);
                const arr = raw ? JSON.parse(raw) : [];
                leadCountEl.textContent = arr.length;
                return arr;
            } catch (e) { console.error(e); return []; }
        }
        function saveLead(lead) {
            const arr = loadLeads();
            arr.push(lead);
            localStorage.setItem(LEADS_KEY, JSON.stringify(arr));
            leadCountEl.textContent = arr.length;
        }
        function clearLeads() {
            if (!confirm('Remover todos os leads locais?')) return;
            localStorage.removeItem(LEADS_KEY);
            leadCountEl.textContent = 0;
            feedbackEl.innerHTML = '<div class="text-success small">Leads removidos.</div>';
        }
        function exportLeads() {
            const arr = loadLeads();
            if (!arr.length) { alert('Nenhum lead para exportar.'); return; }
            const header = ['Nome', 'Email', 'Empresa', 'Budget', 'Mensagem', 'Data'];
            const csv = [header.join(',')].concat(arr.map(l => [escapeCSV(l.name), escapeCSV(l.email), escapeCSV(l.company), escapeCSV(l.budget), escapeCSV(l.message), escapeCSV(l.date)].join(','))).join('\n');
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url; a.download = 'leads_sophira.csv';
            document.body.appendChild(a); a.click(); a.remove(); URL.revokeObjectURL(url);
        }
        function escapeCSV(v) { if (v == null) return ''; const s = String(v).replace(/"/g, '""'); return '"' + s + '"'; }

        // Form submit
        document.getElementById('leadForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const lead = {
                name: document.getElementById('name').value.trim(),
                email: document.getElementById('email').value.trim(),
                company: document.getElementById('company').value.trim(),
                budget: document.getElementById('budget').value,
                message: document.getElementById('message').value.trim(),
                date: new Date().toISOString()
            };
            if (!lead.name || !validateEmail(lead.email)) {
                feedbackEl.innerHTML = '<div class="text-danger small">Informe nome e e-mail válidos.</div>';
                return;
            }
            saveLead(lead);
            feedbackEl.innerHTML = '<div class="text-success small">Enviado — retornaremos em breve.</div>';
            this.reset();
        });

        function validateEmail(email) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email); }

        // Expose functions globally
        window.exportLeads = exportLeads;
        window.clearLeads = clearLeads;

        // init
        document.getElementById('year').textContent = new Date().getFullYear();
        loadLeads();

        // --- Carregar HEADER dinâmico ---
        document.addEventListener('DOMContentLoaded', () => {
        fetch('header.html')
            .then(response => {
            if (!response.ok) throw new Error('Erro ao carregar header: ' + response.status);
            return response.text();
            })
            .then(html => {
            const headerEl = document.getElementById('header');
            if (headerEl) headerEl.innerHTML = html;
            })
            .catch(err => console.error(err));
        });