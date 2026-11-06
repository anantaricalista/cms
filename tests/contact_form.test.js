const test = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const path = require('node:path');

const script = fs.readFileSync(path.join(__dirname, '../assets/frontend/js/contact.js'), 'utf8');
const saved = {
    name: 'Ayu & Putri',
    email: 'ayu@example.test',
    phone: '0812',
    subject: 'Pertanyaan & layanan',
    message: 'Halo <admin>\nBerapa harganya?'
};

function setup(options = {}) {
    const state = { alerts: [], calls: [], shown: 0, resets: 0 };
    const button = { disabled: false };
    const tab = {
        closed: false,
        opener: {},
        document: { body: {} },
        location: { href: '' },
        close() { this.closed = true; }
    };
    const link = { hidden: true, removeAttribute() { delete this.href; } };
    const status = {};
    const details = {};
    const form = {
        action: '/contact/submit',
        dataset: {
            companyName: 'Perusahaan',
            whatsappUrl: options.noNumber ? '' : 'https://api.whatsapp.com/send?phone=628123&text='
        },
        querySelector() { return button; },
        addEventListener(event, handler) { state.submit = handler; },
        reset() { state.resets++; }
    };
    const elements = {
        contactForm: form, modalDetails: details, whatsappStatus: status,
        whatsappBtn: link, successContactModal: {}
    };
    const document = { readyState: 'complete', getElementById(id) { return elements[id]; } };
    const window = {
        open(...args) {
            state.calls.push(args);
            return options.blocked ? null : tab;
        }
    };
    const context = {
        document, window, URL,
        FormData: class { constructor(value) { this.form = value; } },
        bootstrap: { Modal: class { show() { state.shown++; } } },
        fetch: options.fetch || (() => Promise.resolve({ ok: true, json: async () => ({ success: true, data: saved }) })),
        alert: message => state.alerts.push(message),
        console: { error() {} }
    };
    vm.runInNewContext(script, context);
    state.run = () => state.submit({ preventDefault() {} });
    return { state, form, button, tab, link, status, details };
}

test('opens WhatsApp with saved data only after save succeeds', async () => {
    let finish;
    let sent;
    const env = setup({
        fetch: (url, request) => {
            sent = { url, request };
            return new Promise(resolve => { finish = resolve; });
        }
    });
    const submission = env.state.run();
    assert.equal(env.state.calls.length, 1);
    assert.equal(env.tab.location.href, '');
    assert.equal(env.tab.opener, null);
    assert.equal(env.button.disabled, true);
    await env.state.run();
    assert.equal(env.state.calls.length, 1);
    assert.equal(sent.url, '/contact/submit');
    assert.equal(sent.request.headers['X-Requested-With'], 'XMLHttpRequest');
    finish({ ok: true, json: async () => ({ success: true, data: saved }) });
    await submission;

    const url = new URL(env.tab.location.href);
    assert.equal(url.origin + url.pathname, 'https://api.whatsapp.com/send');
    assert.equal(url.searchParams.get('phone'), '628123');
    assert.equal(url.searchParams.get('text'), 'Halo Perusahaan, saya Ayu & Putri\nEmail: ayu@example.test\nTelepon: 0812\nPerihal: Pertanyaan & layanan\nPesan: Halo <admin>\nBerapa harganya?');
    assert.equal(env.link.href, url.toString());
    assert.equal(env.link.hidden, false);
    assert.match(env.details.innerHTML, /Halo &lt;admin&gt;<br>Berapa harganya\?/);
    assert.equal(env.state.resets, 1);
    assert.equal(env.state.shown, 1);
    assert.equal(env.button.disabled, false);
});

test('provides a manual link when the browser blocks the tab', async () => {
    const env = setup({ blocked: true });
    await env.state.run();
    assert.equal(env.link.hidden, false);
    assert.match(env.status.textContent, /tidak dapat dibuka otomatis/);
    assert.equal(new URL(env.link.href).searchParams.get('text').includes(saved.message), true);
});

test('does not open a tab without a configured WhatsApp number', async () => {
    const env = setup({ noNumber: true });
    await env.state.run();
    assert.equal(env.state.calls.length, 0);
    assert.equal(env.link.hidden, true);
    assert.match(env.status.textContent, /nomor WhatsApp belum dikonfigurasi/);
    assert.equal(env.state.resets, 1);
});

test('closes the reserved tab and preserves input on save failure', async () => {
    const env = setup({ fetch: async () => ({ ok: false, json: async () => ({ success: false, message: 'Gagal menyimpan pesan' }) }) });
    await env.state.run();
    assert.equal(env.tab.closed, true);
    assert.equal(env.tab.location.href, '');
    assert.equal(env.state.resets, 0);
    assert.equal(env.state.shown, 0);
    assert.match(env.state.alerts[0], /Gagal menyimpan pesan/);
    assert.equal(env.button.disabled, false);
});

test('closes the reserved tab on network failure', async () => {
    const env = setup({ fetch: async () => { throw new Error('network'); } });
    await env.state.run();
    assert.equal(env.tab.closed, true);
    assert.equal(env.state.resets, 0);
    assert.equal(env.state.shown, 0);
    assert.match(env.state.alerts[0], /Terjadi kesalahan/);
});
