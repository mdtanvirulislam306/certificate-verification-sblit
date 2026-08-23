import html2canvas from 'html2canvas';

async function captureElement(element) {
    return html2canvas(element, {
        scale: 2,
        useCORS: true,
        allowTaint: true,
        backgroundColor: '#fbfaf6',
        logging: false,
    });
}

export async function downloadCertificateImage(element, filename = 'certificate.png') {
    const canvas = await captureElement(element);
    const link = document.createElement('a');
    link.href = canvas.toDataURL('image/png');
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    link.remove();
}

/**
 * Build a simple single-page landscape PDF embedding a JPEG image.
 */
function canvasToPdfBlob(canvas) {
    const jpeg = canvas.toDataURL('image/jpeg', 0.92);
    const jpegData = atob(jpeg.split(',')[1]);
    const jpegBytes = new Uint8Array(jpegData.length);
    for (let i = 0; i < jpegData.length; i += 1) {
        jpegBytes[i] = jpegData.charCodeAt(i);
    }

    // A4 landscape in points (1pt = 1/72")
    const pageW = 841.89;
    const pageH = 595.28;
    const margin = 18;
    const maxW = pageW - margin * 2;
    const maxH = pageH - margin * 2;
    const ratio = Math.min(maxW / canvas.width, maxH / canvas.height);
    const imgW = canvas.width * ratio;
    const imgH = canvas.height * ratio;
    const imgX = (pageW - imgW) / 2;
    const imgY = (pageH - imgH) / 2;

    const objects = [];
    const add = (content) => {
        objects.push(content);
        return objects.length;
    };

    const catalogId = add(null);
    const pagesId = add(null);
    const pageId = add(null);
    const contentId = add(null);
    const imageId = add(null);

    objects[catalogId - 1] = `<< /Type /Catalog /Pages ${pagesId} 0 R >>`;
    objects[pagesId - 1] = `<< /Type /Pages /Kids [${pageId} 0 R] /Count 1 >>`;
    objects[pageId - 1] =
        `<< /Type /Page /Parent ${pagesId} 0 R /MediaBox [0 0 ${pageW} ${pageH}] ` +
        `/Contents ${contentId} 0 R /Resources << /XObject << /Im1 ${imageId} 0 R >> >> >>`;

    const stream =
        `q\n${imgW.toFixed(2)} 0 0 ${imgH.toFixed(2)} ${imgX.toFixed(2)} ${imgY.toFixed(2)} cm\n/Im1 Do\nQ\n`;
    objects[contentId - 1] =
        `<< /Length ${stream.length} >>\nstream\n${stream}endstream`;

    objects[imageId - 1] =
        `<< /Type /XObject /Subtype /Image /Width ${canvas.width} /Height ${canvas.height} ` +
        `/ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode /Length ${jpegBytes.length} >>\n` +
        `stream\n`;

    // Assemble PDF binary
    let pdf = '%PDF-1.4\n';
    const offsets = [0];

    for (let i = 0; i < objects.length; i += 1) {
        offsets.push(new TextEncoder().encode(pdf).length);
        pdf += `${i + 1} 0 obj\n${objects[i]}`;
        if (i === imageId - 1) {
            // image object continues with binary then endstream
        } else {
            pdf += `\nendobj\n`;
        }
    }

    // Rebuild properly with binary image
    const encoder = new TextEncoder();
    const chunks = [];
    const pushText = (text) => chunks.push(encoder.encode(text));
    const offsetList = [0];

    pushText('%PDF-1.4\n');

    const writeObj = (id, bodyBytes) => {
        offsetList[id] = chunks.reduce((sum, c) => sum + c.length, 0);
        pushText(`${id} 0 obj\n`);
        chunks.push(bodyBytes);
        pushText(`\nendobj\n`);
    };

    writeObj(catalogId, encoder.encode(`<< /Type /Catalog /Pages ${pagesId} 0 R >>`));
    writeObj(pagesId, encoder.encode(`<< /Type /Pages /Kids [${pageId} 0 R] /Count 1 >>`));
    writeObj(
        pageId,
        encoder.encode(
            `<< /Type /Page /Parent ${pagesId} 0 R /MediaBox [0 0 ${pageW} ${pageH}] ` +
                `/Contents ${contentId} 0 R /Resources << /XObject << /Im1 ${imageId} 0 R >> >> >>`,
        ),
    );
    writeObj(
        contentId,
        encoder.encode(`<< /Length ${stream.length} >>\nstream\n${stream}endstream`),
    );

    const imageHeader = encoder.encode(
        `<< /Type /XObject /Subtype /Image /Width ${canvas.width} /Height ${canvas.height} ` +
            `/ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode /Length ${jpegBytes.length} >>\n` +
            `stream\n`,
    );
    const imageFooter = encoder.encode(`\nendstream`);
    const imageBody = new Uint8Array(imageHeader.length + jpegBytes.length + imageFooter.length);
    imageBody.set(imageHeader, 0);
    imageBody.set(jpegBytes, imageHeader.length);
    imageBody.set(imageFooter, imageHeader.length + jpegBytes.length);
    writeObj(imageId, imageBody);

    const xrefStart = chunks.reduce((sum, c) => sum + c.length, 0);
    let xref = `xref\n0 ${objects.length + 1}\n`;
    xref += '0000000000 65535 f \n';
    for (let i = 1; i <= objects.length; i += 1) {
        xref += `${String(offsetList[i]).padStart(10, '0')} 00000 n \n`;
    }
    pushText(xref);
    pushText(
        `trailer\n<< /Size ${objects.length + 1} /Root ${catalogId} 0 R >>\n` +
            `startxref\n${xrefStart}\n%%EOF`,
    );

    const total = chunks.reduce((sum, c) => sum + c.length, 0);
    const output = new Uint8Array(total);
    let offset = 0;
    chunks.forEach((chunk) => {
        output.set(chunk, offset);
        offset += chunk.length;
    });

    return new Blob([output], { type: 'application/pdf' });
}

export async function downloadCertificatePdf(element, filename = 'certificate.pdf') {
    const canvas = await captureElement(element);
    const blob = canvasToPdfBlob(canvas);
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    link.remove();
    URL.revokeObjectURL(url);
}
