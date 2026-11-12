// Satima: UUID perangkat anjungan.
// - Tambah: pakai UUID yang sudah tersimpan di browser ini (bila ada), selain itu buat baru; isi IP & user agent.
// - Ubah anjungan yang belum punya UUID: isi UUID browser ini (IP tidak ditimpa).
// UUID baru ditandai data-baru="1" supaya hanya UUID itu yang dipasang ke localStorage + cookie saat Simpan.
const buatUuid = () => {
    if (window.crypto && typeof crypto.randomUUID === 'function') {
        return crypto.randomUUID();
    }
    const b = crypto.getRandomValues(new Uint8Array(16));
    b[6] = (b[6] & 0x0f) | 0x40;
    b[8] = (b[8] & 0x3f) | 0x80;
    const h = Array.from(b, x => x.toString(16).padStart(2, '0')).join('');
    return `${h.slice(0, 8)}-${h.slice(8, 12)}-${h.slice(12, 16)}-${h.slice(16, 20)}-${h.slice(20)}`;
};

document.addEventListener('DOMContentLoaded', () => {
    const field = document.getElementById('anjungan_id');
    if (!field || field.value) {
        return;
    }

    const tambah = field.dataset.aksi === 'Tambah';
    let uuid = null;
    try {
        uuid = localStorage.getItem('anjungan_uuid');
    } catch (e) {}

    field.value = uuid || buatUuid();
    field.dataset.baru = '1';
    if (typeof wajib === 'function') {
        wajib();
    }

    if (tambah) {
        getIp();
    }
    const ua = document.getElementById('user_agent');
    if (ua && (tambah || !ua.value)) {
        getUserAgent();
    }
});

getIp = () => {
    fetch('https://api.ipify.org?format=json')
    .then(r => r.json())
    .then(
        d => {
            const ip = document.getElementById('ip_address');
            if (ip && !ip.value) {
                ip.value = d.ip;
            }
        }
    )
    .catch(() => {});
};

getUserAgent = () => {
    const ua = navigator.userAgent;

    const isMobile = /Mobi|Android|iPhone|iPad/i.test(ua);
    const isRobot = /bot|crawl|slurp|spider|mediapartners/i.test(ua);

    let browser = 'Unknown';
    let version = 'Unknown';
    let platform = 'Unknown';

    // Platform
    if (/Windows NT/i.test(ua)) platform = 'Windows';
    else if (/Mac OS X/i.test(ua)) platform = 'macOS';
    else if (/Android/i.test(ua)) platform = 'Android';
    else if (/iPhone|iPad/i.test(ua)) platform = 'iOS';
    else if (/Linux/i.test(ua)) platform = 'Linux';

    // Browser
    const browserRegex = [
        [/Edg\/([\d.]+)/, 'Edge'],
        [/Chrome\/([\d.]+)/, 'Chrome'],
        [/Firefox\/([\d.]+)/, 'Firefox'],
        [/Safari\/([\d.]+)/, 'Safari'],
    ];

    for (const [regex, name] of browserRegex) {
        const match = ua.match(regex);
        if (match) {
            browser = name;
            version = match[1];
            break;
        }
    }

    let result = {
        browser,
        version,
        platform,
        is_mobile: isMobile,
        is_robot: isRobot,
        is_browser: !isRobot,
        user_agent_string: ua
    };

    document.getElementById('user_agent').value = JSON.stringify(result)
}