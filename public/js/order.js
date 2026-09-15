(function() {
    var meta = document.getElementById('order-data');
    var tableId = parseInt(meta.dataset.tableId);
    var tableCode = meta.dataset.tableCode;
    var csrfToken = meta.dataset.csrf;

    var cart = {};
    var allProducts = [];
    var activeCategory = 'all';
    var pendingOrderId = null;
    var pollingTimer = null;
    var readyPollingTimer = null;
    var lastCustomerName = '';
    var emojis = ['&#9749;', '&#127861;', '&#129380;', '&#127856;', '&#129361;', '&#127854;', '&#127853;', '&#127857;'];

    fetch('/api/menu')
    .then(function(r) { return r.json(); })
    .then(function(data) {
        allProducts = data;
        renderTabs(data);
        renderMenu(data);
    });

    function renderTabs(products) {
        var cats = {};
        products.forEach(function(p) {
            if (p.category) cats[p.category.id] = p.category.name;
        });
        var tabs = document.getElementById('tabs');
        tabs.innerHTML = '<div class="tab active" data-cat="all">Semua</div>';
        Object.keys(cats).forEach(function(id) {
            tabs.innerHTML += '<div class="tab" data-cat="' + id + '">' + cats[id] + '</div>';
        });
        tabs.querySelectorAll('.tab').forEach(function(tab) {
            tab.addEventListener('click', function() {
                tabs.querySelectorAll('.tab').forEach(function(t) { t.classList.remove('active'); });
                this.classList.add('active');
                activeCategory = this.dataset.cat;
                filterMenu();
            });
        });
    }

    function filterMenu() {
        var filtered = activeCategory === 'all' ? allProducts : allProducts.filter(function(p) { return p.category_id == activeCategory; });
        renderMenu(filtered);
    }

    function renderMenu(products) {
        var container = document.getElementById('menuList');
        if (products.length === 0) {
            container.innerHTML = '<div class="empty"><i class="fas fa-coffee"></i><p>Menu belum tersedia</p></div>';
            return;
        }
        container.innerHTML = '';
        products.forEach(function(p, i) {
            var qty = cart[p.id] || 0;
            var card = document.createElement('div');
            card.className = 'menu-card';
            card.innerHTML =
                '<div class="menu-left">' +
                    '<div class="menu-emoji">' + emojis[i % emojis.length] + '</div>' +
                    '<div class="menu-name">' + p.name + '</div>' +
                    '<div class="menu-cat">' + (p.category ? p.category.name : '') + '</div>' +
                    '<div class="menu-price">Rp' + new Intl.NumberFormat('id-ID').format(p.price) + '</div>' +
                '</div>' +
                '<div class="menu-right">' +
                    '<div class="qty-control">' +
                        '<button class="qty-btn" onclick="changeQty(' + p.id + ', -1)"><i class="fas fa-minus" style="font-size:10px;"></i></button>' +
                        '<span class="qty-num" id="qty-' + p.id + '">' + qty + '</span>' +
                        '<button class="qty-btn" onclick="changeQty(' + p.id + ', 1)"><i class="fas fa-plus" style="font-size:10px;"></i></button>' +
                    '</div>' +
                '</div>';
            container.appendChild(card);
        });
    }

    var MAX_QTY = 50;

    window.changeQty = function(productId, delta) {
        var newQty = (cart[productId] || 0) + delta;
        if (newQty > MAX_QTY) { newQty = MAX_QTY; alert('Maksimal 50 item per produk.'); }
        if (newQty <= 0) { delete cart[productId]; } else { cart[productId] = newQty; }
        var el = document.getElementById('qty-' + productId);
        if (el) el.textContent = cart[productId] || 0;
        updateCart();
        if (document.getElementById('cartDrawer').classList.contains('show')) renderDrawer();
    };

    window.removeItem = function(productId) {
        delete cart[productId];
        var el = document.getElementById('qty-' + productId);
        if (el) el.textContent = 0;
        updateCart();
        renderDrawer();
    };

    function updateCart() {
        var count = 0, total = 0;
        allProducts.forEach(function(p) {
            if (cart[p.id]) { count += cart[p.id]; total += p.price * cart[p.id]; }
        });
        var bar = document.getElementById('cartBar');
        if (count > 0) {
            bar.style.display = 'block';
            document.getElementById('cartCount').textContent = count + ' item';
            document.getElementById('cartTotal').textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(total);
        } else {
            bar.style.display = 'none';
        }
    }

    window.openDrawer = function() {
        renderDrawer();
        document.getElementById('drawerOverlay').classList.add('show');
        document.getElementById('cartDrawer').classList.add('show');
        document.body.style.overflow = 'hidden';
    };

    window.closeDrawer = function() {
        document.getElementById('drawerOverlay').classList.remove('show');
        document.getElementById('cartDrawer').classList.remove('show');
        document.body.style.overflow = '';
    };

    function renderDrawer() {
        var itemsHtml = '';
        var count = 0, total = 0;
        var order = [];
        allProducts.forEach(function(p) {
            if (cart[p.id]) {
                count += cart[p.id];
                var subtotal = p.price * cart[p.id];
                total += subtotal;
                order.push({ product: p, qty: cart[p.id], subtotal: subtotal });
            }
        });
        document.getElementById('drawerEmpty').style.display = count > 0 ? 'none' : 'block';
        document.getElementById('drawerFooter').style.display = count > 0 ? 'block' : 'none';
        if (count > 0) {
            order.forEach(function(o) {
                itemsHtml +=
                    '<div class="cart-item">' +
                        '<div class="cart-item-info">' +
                            '<div class="cart-item-name">' + o.product.name + '</div>' +
                            '<div class="cart-item-price">Rp' + new Intl.NumberFormat('id-ID').format(o.product.price) + ' x ' + o.qty + '</div>' +
                        '</div>' +
                        '<div class="cart-item-actions">' +
                            '<div class="qty-control" style="flex-shrink:0;">' +
                                '<button class="qty-btn" onclick="changeQty(' + o.product.id + ', -1)"><i class="fas fa-minus" style="font-size:10px;"></i></button>' +
                                '<span class="qty-num">' + o.qty + '</span>' +
                                '<button class="qty-btn" onclick="changeQty(' + o.product.id + ', 1)"><i class="fas fa-plus" style="font-size:10px;"></i></button>' +
                            '</div>' +
                            '<button class="cart-item-delete" onclick="removeItem(' + o.product.id + ')" title="Hapus"><i class="fas fa-trash-alt"></i></button>' +
                        '</div>' +
                        '<div class="cart-item-subtotal">Rp' + new Intl.NumberFormat('id-ID').format(o.subtotal) + '</div>' +
                    '</div>';
            });
            document.getElementById('drawerTotal').textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(total);
        }
        document.getElementById('drawerItems').innerHTML = itemsHtml;
    }

    window.clearCart = function() {
        cart = {};
        updateCart();
        renderDrawer();
        renderMenu(activeCategory === 'all' ? allProducts : allProducts.filter(function(p) { return p.category_id == activeCategory; }));
    };

    function getCustomerName() {
        var nameInput = document.getElementById('customerName');
        if (nameInput && nameInput.value.trim()) lastCustomerName = nameInput.value.trim();
        return lastCustomerName;
    }

    window.submitOrder = function() {
        var items = [];
        Object.keys(cart).forEach(function(pid) { items.push({ product_id: parseInt(pid), quantity: cart[pid] }); });
        if (items.length === 0) return;
        var nameInput = document.getElementById('customerName');
        var cname = nameInput ? nameInput.value.trim() : '';
        if (!cname) { alert('Mohon isi nama Anda.'); openDrawer(); nameInput.focus(); return; }
        lastCustomerName = cname;

        var btns = [document.getElementById('cartBtn'), document.getElementById('drawerCheckoutBtn')];
        btns.forEach(function(btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...'; });

        fetch('/midtrans/create', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ table_id: tableId, customer_name: cname, items: items })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            btns.forEach(function(btn) { btn.disabled = false; btn.innerHTML = '<i class="fas fa-shopping-bag"></i> Pesan Sekarang'; });
            document.getElementById('cartBtn').innerHTML = '<i class="fas fa-shopping-bag"></i> Keranjang';
            if (data.token && data.order_id) {
                pendingOrderId = data.order_id;
                updateStatus('pending');
                snap.pay(data.token, {
                    onSuccess: function() {
                        fetch('/midtrans/payment-status', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: JSON.stringify({ order_id: pendingOrderId }) })
                        .then(function(r) { return r.json(); })
                        .then(function() { updateStatus('lunas'); })
                        .catch(function() { updateStatus('lunas'); });
                    },
                    onPending: function() { updateStatus('pending'); },
                    onError: function() { updateStatus('error'); },
                    onClose: function() { startPaymentPolling(); }
                });
            } else {
                alert(data.message || 'Terjadi kesalahan.');
            }
        })
        .catch(function() {
            btns.forEach(function(btn) { btn.disabled = false; btn.innerHTML = '<i class="fas fa-shopping-bag"></i> Pesan Sekarang'; });
            document.getElementById('cartBtn').innerHTML = '<i class="fas fa-shopping-bag"></i> Keranjang';
            alert('Gagal menghubungi server.');
        });
    };

    function updateStatus(status) {
        var overlay = document.getElementById('successOverlay');
        if (status === 'lunas') {
            document.getElementById('successTitle').textContent = 'Pembayaran Berhasil!';
            document.getElementById('successDesc').textContent = 'Pesanan Anda sedang diproses. Silakan tunggu di meja Anda.';
            document.getElementById('successIcon').innerHTML = '&#10003;';
            overlay.classList.add('show');
            stopPaymentPolling();
            startReadyPolling();
        } else if (status === 'pending') {
            document.getElementById('successTitle').textContent = 'Menunggu Pembayaran';
            document.getElementById('successDesc').textContent = 'Selesaikan pembayaran agar pesanan diproses.';
            document.getElementById('successIcon').innerHTML = '&#8987;';
            overlay.classList.add('show');
        } else {
            document.getElementById('successTitle').textContent = 'Pembayaran Gagal';
            document.getElementById('successDesc').textContent = 'Silakan coba pesan ulang.';
            document.getElementById('successIcon').innerHTML = '&#10007;';
            overlay.classList.add('show');
        }
    }

    function startPaymentPolling() {
        if (!pendingOrderId) return;
        var attempt = 0;
        pollingTimer = setInterval(function() {
            attempt++;
            fetch('/midtrans/payment-status', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: JSON.stringify({ order_id: pendingOrderId }) })
            .then(function(r) { return r.json(); })
            .then(function(data) { if (data && data.success) { updateStatus('lunas'); } else if (attempt >= 10) { updateStatus('pending'); } })
            .catch(function() { if (attempt >= 10) updateStatus('pending'); });
        }, 2000);
    }

    function stopPaymentPolling() { if (pollingTimer) { clearInterval(pollingTimer); pollingTimer = null; } }

    function startReadyPolling() {
        if (!pendingOrderId) return;
        readyPollingTimer = setInterval(function() {
            fetch('/api/order-status/' + pendingOrderId)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.status === 'selesai') { clearInterval(readyPollingTimer); readyPollingTimer = null; showReadyNotification(); }
                else if (data.status === 'cancelled') { clearInterval(readyPollingTimer); readyPollingTimer = null; }
            })
            .catch(function() {});
        }, 5000);
    }

    function stopReadyPolling() { if (readyPollingTimer) { clearInterval(readyPollingTimer); readyPollingTimer = null; } }

    function playNotifSound() {
        try {
            var ctx = new (window.AudioContext || window.webkitAudioContext)();
            [523.25, 659.25, 783.99, 1046.50].forEach(function(f, i) {
                var o = ctx.createOscillator(); var g = ctx.createGain();
                o.connect(g); g.connect(ctx.destination); o.frequency.value = f; o.type = 'sine';
                g.gain.setValueAtTime(0.3, ctx.currentTime + i * 0.15);
                g.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + i * 0.15 + 0.4);
                o.start(ctx.currentTime + i * 0.15); o.stop(ctx.currentTime + i * 0.15 + 0.4);
            });
        } catch(e) {}
    }

    function showReadyNotification() {
        playNotifSound();
        document.getElementById('readyTitle').textContent = 'Pesanan Siap!';
        document.getElementById('readyDesc').textContent = 'Pesanan Anda sudah selesai. Silakan ambil di meja Anda.';
        document.getElementById('readyNotification').classList.add('show');
    }

    window.switchView = function(view) {
        document.getElementById('menuSection').style.display = view === 'menu' ? 'block' : 'none';
        document.getElementById('historySection').style.display = view === 'history' ? 'block' : 'none';
        document.getElementById('tabs').style.display = view === 'menu' ? 'flex' : 'none';
        document.getElementById('menuViewBtn').classList.toggle('active', view === 'menu');
        document.getElementById('historyViewBtn').classList.toggle('active', view === 'history');
        if (view === 'history') loadHistory();
    }

    function loadHistory() {
        var name = getCustomerName();
        var url = '/api/order-history/' + tableId;
        if (name) url += '?customer_name=' + encodeURIComponent(name);
        var container = document.getElementById('historyList');
        container.innerHTML = '<div class="loading"><div class="spinner"></div></div>';
        fetch(url).then(function(r) { return r.json(); }).then(function(data) { renderHistory(data); })
        .catch(function() { container.innerHTML = '<div class="history-empty"><i class="fas fa-exclamation-triangle"></i><p>Gagal memuat riwayat</p></div>'; });
    }

    function renderHistory(list) {
        var container = document.getElementById('historyList');
        if (!list || list.length === 0) {
            container.innerHTML = '<div class="history-empty"><i class="fas fa-receipt"></i><p>Belum ada riwayat pesanan</p></div>';
            return;
        }
        var html = '';
        list.forEach(function(t) {
            var sLabel = '', sIcon = '';
            if (t.status === 'lunas') { sLabel = 'Lunas'; sIcon = '&#9989;'; }
            else if (t.status === 'selesai') { sLabel = 'Selesai'; sIcon = '&#9989;'; }
            else if (t.status === 'cancelled') { sLabel = 'Dibatalkan'; sIcon = '&#10060;'; }
            else { sLabel = 'Menunggu'; sIcon = '&#9203;'; }
            var items = '';
            t.items.forEach(function(it) {
                items += '<div class="history-item"><span class="history-item-name">' + it.name + '</span><span class="history-item-qty">' + it.quantity + ' x Rp' + new Intl.NumberFormat('id-ID').format(it.price) + '</span></div>';
            });
            html += '<div class="history-card">' +
                '<div class="history-header"><div><div class="history-order-id">Pesanan #' + t.id + '</div><div class="history-date">' + t.created_at + '</div></div>' +
                '<span class="history-status ' + t.status + '">' + sIcon + ' ' + sLabel + '</span></div>' +
                '<div class="history-items">' + items + '</div>' +
                '<div class="history-total"><span>Total</span><span>Rp' + new Intl.NumberFormat('id-ID').format(t.total_price) + '</span></div></div>';
        });
        container.innerHTML = html;
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('cartViewBtn').addEventListener('click', openDrawer);
        document.getElementById('cartBtn').addEventListener('click', openDrawer);
        document.getElementById('drawerOverlay').addEventListener('click', closeDrawer);
        document.getElementById('drawerCloseBtn').addEventListener('click', closeDrawer);
        document.getElementById('drawerClearBtn').addEventListener('click', clearCart);
        document.getElementById('drawerCheckoutBtn').addEventListener('click', function() { closeDrawer(); submitOrder(); });
    });
})();
