// ============================================================
// Z&L Cars — script.js
// ============================================================

// ── Dark Mode ──────────────────────────────────────────────
const toggle = document.getElementById('darkModeToggle');
const icon   = document.getElementById('darkIcon');
const html   = document.documentElement;

// Restaura preferência salva
const savedTheme = localStorage.getItem('zlcars-theme') || 'light';
html.setAttribute('data-theme', savedTheme);
updateIcon(savedTheme);

if (toggle) {
    toggle.addEventListener('click', () => {
        const current = html.getAttribute('data-theme');
        const next    = current === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-theme', next);
        localStorage.setItem('zlcars-theme', next);
        updateIcon(next);
    });
}

function updateIcon(theme) {
    if (!icon) return;
    icon.className = theme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-stars-fill';
}

// ── Carrinho (localStorage para demo; sessão PHP é a fonte oficial) ──
function adicionarAoCarrinho(id, nome, preco) {
    // Envia via fetch para o PHP que gerencia o $_SESSION
    fetch('../pages/carrinho.php?action=add', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${encodeURIComponent(id)}&nome=${encodeURIComponent(nome)}&preco=${encodeURIComponent(preco)}`
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            mostrarToast(`${nome} adicionado ao carrinho!`);
            // Atualiza badge
            const badge = document.querySelector('.cart-badge');
            if (badge) badge.textContent = data.quantidade;
            else {
                const cartLink = document.querySelector('a[href*="carrinho"]');
                if (cartLink) {
                    const b = document.createElement('span');
                    b.className = 'badge bg-success cart-badge';
                    b.textContent = data.quantidade;
                    cartLink.style.position = 'relative';
                    cartLink.appendChild(b);
                }
            }
        }
    })
    .catch(() => mostrarToast('Erro ao adicionar ao carrinho.', 'danger'));
}

function removerDoCarrinho(index) {
    fetch('../pages/carrinho.php?action=remove', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `index=${index}`
    })
    .then(r => r.json())
    .then(data => { if (data.success) location.reload(); });
}

// ── Toast de feedback ───────────────────────────────────────
function mostrarToast(msg, tipo = 'success') {
    const container = document.getElementById('toastContainer')
        || (() => {
            const c = document.createElement('div');
            c.id = 'toastContainer';
            c.style.cssText = 'position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;display:flex;flex-direction:column;gap:.5rem;';
            document.body.appendChild(c);
            return c;
        })();

    const toast = document.createElement('div');
    toast.className = `alert alert-${tipo} shadow py-2 px-3 mb-0`;
    toast.style.cssText = 'min-width:240px;animation:slideIn .3s ease;';
    toast.innerHTML = `<i class="bi bi-check-circle me-2"></i>${msg}`;
    container.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

// ── Animação CSS para toast ─────────────────────────────────
const style = document.createElement('style');
style.textContent = `@keyframes slideIn{from{opacity:0;transform:translateX(30px)}to{opacity:1;transform:translateX(0)}}`;
document.head.appendChild(style);
