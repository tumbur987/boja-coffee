(function() {
    var meta = document.getElementById('order-data');
    var tableId = parseInt(meta.dataset.tableId);
    var tableCode = meta.dataset.tableCode;
    var csrfToken = meta.dataset.csrf;
    var clientKey = meta.dataset.clientKey;

    console.log('[INIT] Table ID:', tableId, 'Code:', tableCode);
    console.log('[INIT] Client key:', clientKey);

    var cart = {};
    var allProducts = [];
    var activeCategory = 'all';
    var btns = [];
    var pendingOrderId = null;
    var pollingTimer = null;
    var readyPollingTimer = null;
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
        if (newQty > MAX_QTY) {
            newQty = MAX_QTY;
            alert('Maksimal pemesanan adalah 50 item per produk.');
        }
        if (newQty <= 0) {
            delete cart[productId];
        } else {
            cart[productId] = newQty;
        }
        var el = document.getElementById('qty-' + productId);
        if (el) el.textContent = cart[productId] || 0;
        updateCart();
        if (document.getElementById('cartDrawer').classList.contains('show')) {
            renderDrawer();
        }
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
            if (cart[p.id]) {
                count += cart[p.id];
                total += p.price * cart[p.id];
            }
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

    window.submitOrder = function() {
        var items = [];
        Object.keys(cart).forEach(function(pid) {
            items.push({ product_id: parseInt(pid), quantity: cart[pid] });
        });
        if (items.length === 0) return;

        var customerName = document.getElementById('customerName').value.trim();
        if (!customerName) {
            alert('Mohon isi nama Anda.');
            openDrawer();
            document.getElementById('customerName').focus();
            return;
        }

        console.log('[ORDER] Submit order:', { table_id: tableId, customer_name: customerName, items: items });

        btns = [document.getElementById('cartBtn'), document.getElementById('drawerCheckoutBtn')];
        btns.forEach(function(btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        });

        fetch('/midtrans/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ table_id: tableId, customer_name: customerName, items: items })
        })
        .then(function(r) {
            console.log('[ORDER] Response HTTP status:', r.status);
            return r.json();
        })
        .then(function(data) {
            console.log('[ORDER] Response data:', data);
            resetBtns();
            if (data.token && data.order_id) {
                pendingOrderId = data.order_id;
                console.log('[ORDER] Snap token OK, order_id:', pendingOrderId);
                updateStatus('pending');
                snap.pay(data.token, {
                    onSuccess: function(result) {
                        console.log('[SNAP] onSuccess:', result);
                        fetch('/midtrans/payment-status', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({ order_id: pendingOrderId })
                        })
                        .then(function(r) { return r.json(); })
                        .then(function(res) {
                            console.log('[PAYMENT-STATUS] response:', res);
                            updateStatus('lunas');
                        })
                        .catch(function(err) {
                            console.error('[PAYMENT-STATUS] error:', err);
                            updateStatus('lunas');
                        });
                    },
                    onPending: function(result) {
                        console.log('[SNAP] onPending:', result);
                        updateStatus('pending');
                    },
                    onError: function(result) {
                        console.log('[SNAP] onError:', result);
                        updateStatus('error');
                    },
                    onClose: function() {
                        console.log('[SNAP] onClose - user tutup popup tanpa bayar');
                        startPolling();
                    }
                });
            } else {
                console.error('[ORDER] Gagal dapat token:', data);
                showError(data.message || 'Terjadi kesalahan. Silakan coba lagi.');
            }
        })
        .catch(function(err) {
            console.error('[ORDER] Fetch error:', err);
            resetBtns();
            showError('Gagal menghubungi server. Coba lagi.');
        });
    };

    function resetBtns() {
        btns.forEach(function(btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-shopping-bag"></i> Pesan Sekarang';
        });
        document.getElementById('cartBtn').innerHTML = '<i class="fas fa-shopping-bag"></i> Keranjang';
    }

    function updateStatus(status) {
        console.log('[STATUS] Update status:', status);
        if (status === 'lunas') {
            document.getElementById('successTitle').textContent = 'Pembayaran Berhasil!';
            document.getElementById('successDesc').textContent = 'Pesanan Anda sedang diproses. Silakan tunggu di meja Anda.';
            document.getElementById('successIcon').innerHTML = '&#10003;';
            document.getElementById('successOverlay').classList.add('show');
            stopPolling();
            startReadyPolling();
        } else if (status === 'pending') {
            document.getElementById('successTitle').textContent = 'Menunggu Pembayaran';
            document.getElementById('successDesc').textContent = 'Pesanan Anda diterima. Selesaikan pembayaran melalui metode yang Anda pilih agar pesanan diproses.';
            document.getElementById('successIcon').innerHTML = '&#8987;';
            document.getElementById('successOverlay').classList.add('show');
        } else {
            document.getElementById('successTitle').textContent = 'Pembayaran Gagal';
            document.getElementById('successDesc').textContent = 'Terjadi kesalahan. Silakan coba pesan ulang.';
            document.getElementById('successIcon').innerHTML = '&#10007;';
            document.getElementById('successOverlay').classList.add('show');
        }
    }

    function startPolling() {
        if (!pendingOrderId) return;
        console.log('[POLLING] Mulai polling untuk order_id:', pendingOrderId);
        var attempt = 0;
        pollingTimer = setInterval(function() {
            attempt++;
            console.log('[POLLING] Attempt #' + attempt + ' untuk order_id:', pendingOrderId);
            fetch('/midtrans/payment-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ order_id: pendingOrderId })
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                console.log('[POLLING] Response:', data);
                if (data && data.success) {
                    console.log('[POLLING] Pembayaran ditemukan! Status:', data.status);
                    updateStatus('lunas');
                } else if (attempt >= 10) {
                    console.log('[POLLING] Max attempts, status tetap pending');
                    updateStatus('pending');
                }
            })
            .catch(function(err) {
                console.error('[POLLING] Error:', err);
                if (attempt >= 10) {
                    updateStatus('pending');
                }
            });
        }, 2000);
    }

    function stopPolling() {
        if (pollingTimer) {
            clearInterval(pollingTimer);
            pollingTimer = null;
        }
    }

    function startReadyPolling() {
        if (!pendingOrderId) return;
        console.log('[READY-POLLING] Mulai cek status pesanan:', pendingOrderId);
        var attempt = 0;
        readyPollingTimer = setInterval(function() {
            attempt++;
            fetch('/api/order-status/' + pendingOrderId)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                console.log('[READY-POLLING] Status:', data.status);
                if (data.status === 'selesai') {
                    clearInterval(readyPollingTimer);
                    readyPollingTimer = null;
                    showReadyNotification();
                } else if (data.status === 'cancelled') {
                    clearInterval(readyPollingTimer);
                    readyPollingTimer = null;
                }
            })
            .catch(function(err) {
                console.error('[READY-POLLING] Error:', err);
            });
        }, 5000);
    }

    function stopReadyPolling() {
        if (readyPollingTimer) {
            clearInterval(readyPollingTimer);
            readyPollingTimer = null;
        }
    }

    function showReadyNotification() {
        document.getElementById('readyNotification').classList.add('show');
    }

    document.getElementById('readyBtn').addEventListener('click', function() {
        document.getElementById('readyNotification').classList.remove('show');
    });

    function showError(message) {
        alert(message || 'Terjadi kesalahan. Silakan coba lagi.');
    }

    window.resetOrder = function() {
        stopPolling();
        stopReadyPolling();
        pendingOrderId = null;
        cart = {};
        updateCart();
        document.getElementById('successOverlay').classList.remove('show');
        filterMenu();
    };

    document.getElementById('cartViewBtn').addEventListener('click', openDrawer);
    document.getElementById('cartBtn').addEventListener('click', openDrawer);
    document.getElementById('drawerOverlay').addEventListener('click', closeDrawer);
    document.getElementById('drawerCloseBtn').addEventListener('click', closeDrawer);
    document.getElementById('drawerClearBtn').addEventListener('click', clearCart);
    document.getElementById('drawerCheckoutBtn').addEventListener('click', function() {
        closeDrawer();
        submitOrder();
    });
    document.getElementById('successBtn').addEventListener('click', resetOrder);
})();
