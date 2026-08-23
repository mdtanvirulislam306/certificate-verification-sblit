import QRCode from 'qrcode';

function roundRect(ctx, x, y, width, height, radius) {
    const r = Math.min(radius, width / 2, height / 2);
    ctx.beginPath();
    ctx.moveTo(x + r, y);
    ctx.arcTo(x + width, y, x + width, y + height, r);
    ctx.arcTo(x + width, y + height, x, y + height, r);
    ctx.arcTo(x, y + height, x, y, r);
    ctx.arcTo(x, y, x + width, y, r);
    ctx.closePath();
}

function loadImage(src) {
    return new Promise((resolve, reject) => {
        const img = new Image();
        img.crossOrigin = 'anonymous';
        img.onload = () => resolve(img);
        img.onerror = reject;
        img.src = src;
    });
}

/**
 * Generate a branded QR (verify link + center logo) and download as PNG.
 */
export async function downloadBrandedQr({
    url,
    filename = 'certificate-qr.png',
    logoUrl = '/images/logo.jpg',
    size = 720,
}) {
    const padding = Math.round(size * 0.08);
    const qrSize = size - padding * 2;
    const radius = Math.round(size * 0.06);

    const qrCanvas = document.createElement('canvas');
    await QRCode.toCanvas(qrCanvas, url, {
        errorCorrectionLevel: 'H',
        margin: 1,
        width: qrSize,
        color: {
            dark: '#111111',
            light: '#FFFFFF',
        },
    });

    const canvas = document.createElement('canvas');
    canvas.width = size;
    canvas.height = size;
    const ctx = canvas.getContext('2d');

    // Rounded white card background
    ctx.clearRect(0, 0, size, size);
    roundRect(ctx, 0, 0, size, size, radius);
    ctx.fillStyle = '#FFFFFF';
    ctx.fill();
    ctx.clip();

    // Soft border
    ctx.strokeStyle = '#E5E7EB';
    ctx.lineWidth = 2;
    roundRect(ctx, 1, 1, size - 2, size - 2, radius);
    ctx.stroke();

    // QR pattern
    ctx.drawImage(qrCanvas, padding, padding, qrSize, qrSize);

    // Center logo plate
    const plate = Math.round(size * 0.22);
    const plateX = (size - plate) / 2;
    const plateY = (size - plate) / 2;
    const plateRadius = Math.round(plate * 0.18);

    ctx.fillStyle = '#FFFFFF';
    roundRect(ctx, plateX, plateY, plate, plate, plateRadius);
    ctx.fill();
    ctx.strokeStyle = '#E5E7EB';
    ctx.lineWidth = 2;
    roundRect(ctx, plateX, plateY, plate, plate, plateRadius);
    ctx.stroke();

    try {
        const logo = await loadImage(logoUrl);
        const logoPad = Math.round(plate * 0.12);
        const logoSize = plate - logoPad * 2;
        ctx.drawImage(
            logo,
            plateX + logoPad,
            plateY + logoPad,
            logoSize,
            logoSize,
        );
    } catch {
        // Logo optional — QR still downloads
    }

    const blob = await new Promise((resolve) =>
        canvas.toBlob(resolve, 'image/png'),
    );

    const objectUrl = URL.createObjectURL(blob);
    const anchor = document.createElement('a');
    anchor.href = objectUrl;
    anchor.download = filename;
    document.body.appendChild(anchor);
    anchor.click();
    anchor.remove();
    URL.revokeObjectURL(objectUrl);
}
