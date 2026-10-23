'use strict';

// Hanya hidup selama halaman ini terbuka; tidak memakai penyimpanan browser.
let cart = {};
let busy = false;
const rupiah = value => 'Rp' + value.toLocaleString('id-ID');

async function post(url, payload) {
    document.getElementById('payload').textContent = JSON.stringify(payload, null, 2);
    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json', 'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify(payload),
    });
    const data = await response.json();
    document.getElementById('response').textContent = JSON.stringify(data, null, 2);
    if (!response.ok) throw new Error(data.message || 'Request gagal.');
    return data;
}

function render(data) {
    cart = Object.fromEntries(data.items.map(item => [item.id, item.quantity]));
    const container = document.getElementById('items');
    container.replaceChildren();
    if (!data.items.length) container.textContent = 'Keranjang kosong.';
    else {
        const table = document.createElement('table');
        const header = table.createTHead().insertRow();
        ['Produk', 'Harga', 'Jumlah', 'Aksi', 'Subtotal'].forEach(label => {
            const cell = document.createElement('th'); cell.textContent = label; header.append(cell);
        });
        const body = table.createTBody();
        data.items.forEach(item => {
            const row = body.insertRow();
            row.insertCell().textContent = item.name;
            row.insertCell().textContent = rupiah(item.price);
            const input = document.createElement('input');
            input.type = 'number'; input.min = '1'; input.max = '99'; input.step = '1';
            input.value = item.quantity; input.setAttribute('aria-label', 'Jumlah ' + item.name);
            input.addEventListener('change', () => {
                const quantity = Number(input.value);
                if (!Number.isInteger(quantity) || quantity < 1 || quantity > 99) {
                    input.value = item.quantity;
                    document.getElementById('error').textContent = 'Jumlah harus bilangan bulat 1–99.';
                    return;
                }
                perform(() => post('/cart/update', {cart: {...cart, [item.id]: quantity}}));
            });
            row.insertCell().append(input);
            const remove = document.createElement('button');
            remove.textContent = 'Hapus'; remove.className = 'remove'; remove.type = 'button';
            remove.addEventListener('click', () => perform(() => {
                const next = {...cart}; delete next[item.id];
                return post('/cart/update', {cart: next});
            }));
            row.insertCell().append(remove);
            row.insertCell().textContent = rupiah(item.subtotal);
        });
        container.append(table);
    }
    document.getElementById('total').textContent = 'Total: ' + rupiah(data.total);
    document.getElementById('count').textContent = data.items.reduce((sum, item) => sum + item.quantity, 0) + ' unit';
}

async function perform(action) {
    if (busy) return;
    busy = true;
    document.getElementById('error').textContent = '';
    document.querySelectorAll('button,input').forEach(element => element.disabled = true);
    try { render(await action()); }
    catch (error) { document.getElementById('error').textContent = error.message; }
    finally {
        busy = false;
        document.querySelectorAll('button,input').forEach(element => element.disabled = false);
    }
}

document.querySelectorAll('[data-add]').forEach(button => button.addEventListener('click', () =>
    perform(() => post('/cart/add', {cart: cart, product_id: Number(button.dataset.add)}))));
document.getElementById('recalculate').addEventListener('click', () => perform(() => post('/cart/update', {cart: cart})));
document.getElementById('clear').addEventListener('click', () => perform(() => post('/cart/update', {cart: {}})));
