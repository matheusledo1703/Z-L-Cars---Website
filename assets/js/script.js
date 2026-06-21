// ============================================================
// Z&L Cars — script.js
// ============================================================

// Caminho absoluto para o fetch funcionar independente da página atual
const BASE = (() => {
    const base = window.ZLCARS_BASE || './';
    // Se rodar de dentro de pages/, sobe um nível para a raiz
    if (base === '' || base === './') return '/';
    // Converte '../' em caminho absoluto baseado no location atual
    const a = document.createElement('a');
    a.href = base;
    return a.href;
})();

// ── Dark Mode ──────────────────────────────────────────────
(function initDarkMode() {
    const html   = document.documentElement;
    const toggle = document.getElementById('darkModeToggle');
    const icon   = document.getElementById('darkIcon');

    function updateIcon(theme) {
        if (!icon) return;
        icon.className = theme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-stars-fill';
    }

    const savedTheme = localStorage.getItem('zlcars-theme') || 'light';
    html.setAttribute('data-theme', savedTheme);
    updateIcon(savedTheme);

    if (toggle) {
        toggle.addEventListener('click', () => {
            const current = html.getAttribute('data-theme') || 'light';
            const next    = current === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', next);
            localStorage.setItem('zlcars-theme', next);
            updateIcon(next);
        });
    }
})();

// ── Carrinho ───────────────────────────────────────────────
function parseVeiculo(dados) {
    if (typeof dados === 'object' && dados !== null) {
        return dados;
    }
    if (typeof dados === 'string') {
        return JSON.parse(dados);
    }
    throw new Error('Dados do veículo inválidos');
}

function nomeVeiculoJs(veiculo) {
    return veiculo.nome || [veiculo.marca, veiculo.modelo].filter(Boolean).join(' ').trim() || 'Veículo';
}

function adicionarAoCarrinho(dados) {
    let veiculo;

    try {
        veiculo = parseVeiculo(dados);
    } catch (e) {
        mostrarToast('Erro ao ler dados do veículo.', 'danger');
        return;
    }

    const nome = nomeVeiculoJs(veiculo);
    const body = new URLSearchParams({
        id:     veiculo.id     ?? 0,
        nome:   nome,
        marca:  veiculo.marca  ?? '',
        modelo: veiculo.modelo ?? '',
        ano:    veiculo.ano    ?? 0,
        cor:       veiculo.cor       ?? '',
        descricao: veiculo.descricao ?? '',
        valor:     veiculo.valor     ?? 0,
        foto:      veiculo.foto      ?? '',
    });

    fetch(BASE + 'pages/carrinho.php?action=add', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: body.toString()
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            mostrarToast(`${nome} adicionado ao carrinho!`);
            const badge = document.querySelector('.cart-badge');
            if (badge) {
                badge.textContent = data.quantidade;
            } else {
                const cartLink = document.querySelector('a[href*="carrinho"]');
                if (cartLink) {
                    const b = document.createElement('span');
                    b.className = 'badge bg-success cart-badge';
                    b.textContent = data.quantidade;
                    cartLink.style.position = 'relative';
                    cartLink.appendChild(b);
                }
            }
        } else {
            mostrarToast(data.mensagem || 'Não foi possível adicionar ao carrinho.', 'danger');
        }
    })
    .catch(() => mostrarToast('Erro ao adicionar ao carrinho.', 'danger'));
}

document.querySelectorAll('.btn-add-carrinho').forEach(btn => {
    btn.addEventListener('click', () => {
        adicionarAoCarrinho(btn.dataset.veiculo);
    });
});

function removerDoCarrinho(index) {
    fetch(BASE + 'pages/carrinho.php?action=remove', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `index=${index}`
    })
    .then(r => r.json())
    .then(data => { if (data.success) location.reload(); });
}

// ── Conta: filtrar cidades pelo estado ─────────────────────
const selEstado = document.getElementById('id_estado');
const selCidade = document.getElementById('id_cidade');

if (selEstado && selCidade && window.CIDADES_POR_ESTADO) {
    const cidadeSalva = selCidade.dataset.selected || '';

    function filtrarCidades() {
        const idEstado = selEstado.value;
        const cidades  = window.CIDADES_POR_ESTADO[idEstado] || [];

        selCidade.innerHTML = '<option value="">Selecione...</option>';
        cidades.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c.id;
            opt.textContent = c.nome;
            if (String(c.id) === String(cidadeSalva)) {
                opt.selected = true;
            }
            selCidade.appendChild(opt);
        });
    }

    selEstado.addEventListener('change', filtrarCidades);
    filtrarCidades();
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

const style = document.createElement('style');
style.textContent = `@keyframes slideIn{from{opacity:0;transform:translateX(30px)}to{opacity:1;transform:translateX(0)}}`;
document.head.appendChild(style);
