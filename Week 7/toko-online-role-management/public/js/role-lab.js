'use strict';
// Local teaching aid: uses the same protected application endpoints.
document.getElementById('experiment').addEventListener('submit', async event => {
    event.preventDefault();
    const scenario = document.getElementById('scenario').value;
    const requests = {
        promote: ['/admin/users/USR001/role', 'PATCH', {role: 'staff'}],
        same: ['/admin/users/USR001/role', 'PATCH', {role: 'staff'}],
        demote: ['/admin/users/USR001/role', 'PATCH', {role: 'customer'}],
        self: ['/admin/users/ADM001/role', 'PATCH', {role: 'customer'}],
        other: ['/admin/users/ADM002/role', 'PATCH', {role: 'customer'}],
        invalid: ['/admin/users/USR001/role', 'PATCH', {role: 'admin'}],
        denied: ['/admin/users/USR002/role', 'PATCH', {role: 'staff'}],
        cart: ['/cart/add', 'POST', {cart: {}, product_id: 1}],
        cartnext: ['/cart/add', 'POST', {cart: {'1': 1}, product_id: 2}],
    };
    const [url, method, payload] = requests[scenario];
    const button = event.submitter;
    button.disabled = true;
    document.getElementById('http-status').textContent = 'Menunggu response';
    document.getElementById('request-output').textContent = method + ' ' + url + '\nAccept: application/json\n' + JSON.stringify(payload, null, 2);
    try {
        const response = await fetch(url, {method, headers: {'Accept': 'application/json',
            'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content},
            body: JSON.stringify(payload)});
        const data = await response.json();
        // Show only public response fields; never render debug traces or credentials.
        const safe = response.ok ? data : {message: data.message, errors: data.errors};
        document.getElementById('http-status').textContent = 'HTTP ' + response.status;
        document.getElementById('response-output').textContent = JSON.stringify(safe, null, 2);
    } catch (error) {
        document.getElementById('http-status').textContent = 'Request gagal';
        document.getElementById('response-output').textContent = error.message;
    } finally { button.disabled = false; }
});
