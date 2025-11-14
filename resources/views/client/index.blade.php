<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

<button id="pay">Bayar Sekarang</button>

<script>
document.getElementById('pay').onclick = function () {
    fetch('/midtrans/create', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}' }
    })
    .then(res => res.json())
    .then(data => {
        snap.pay(data.token);
    });
};
</script>
