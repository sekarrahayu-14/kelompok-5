(function () {
    var menu = document.querySelector('.menu-toggle');
    var sidebar = document.querySelector('.sidebar');
    if (menu && sidebar) {
        menu.addEventListener('click', function () { sidebar.classList.toggle('open'); });
    }

    window.confirmDelete = function () {
        return window.confirm('Hapus data ini? Tindakan ini tidak dapat dibatalkan.');
    };

    var cart = document.getElementById('cart');
    var addItem = document.getElementById('add-item');
    var total = document.getElementById('total');
    if (!cart || !addItem) return;

    function recalculate() {
        var sum = 0;
        cart.querySelectorAll('.cart-row').forEach(function (row) {
            var quantity = row.querySelector('[name$="[jumlah]"]');
            var price = row.querySelector('[name$="[harga]"]');
            sum += (Number(quantity.value) || 0) * (Number(price.value) || 0);
        });
        total.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(sum);
    }
    function bind(row) {
        var product = row.querySelector('select');
        var price = row.querySelector('[name$="[harga]"]');
        product.addEventListener('change', function () { price.value = product.options[product.selectedIndex].dataset.price || 0; recalculate(); });
        row.querySelectorAll('input').forEach(function (input) { input.addEventListener('input', recalculate); });
        product.dispatchEvent(new Event('change'));
    }
    bind(cart.querySelector('.cart-row'));
    addItem.addEventListener('click', function () {
        var index = cart.querySelectorAll('.cart-row').length;
        var row = cart.querySelector('.cart-row').cloneNode(true);
        row.querySelectorAll('select,input').forEach(function (field) {
            field.name = field.name.replace(/details\[0\]/, 'details[' + index + ']');
            if (field.tagName === 'INPUT') field.value = field.name.indexOf('[jumlah]') > -1 ? 1 : '';
        });
        cart.appendChild(row);
        bind(row);
    });
}());
