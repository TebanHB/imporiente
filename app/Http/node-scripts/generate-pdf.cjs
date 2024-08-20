const puppeteer = require('puppeteer');
const fs = require('fs');
const path = require('path');

// Obtener los argumentos de la línea de comandos
const args = process.argv.slice(2);
const ventaId = args[0];
const pdfFilePath = args[1];

(async () => {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();
    
    // Cargar el contenido HTML
    const htmlContent = fs.readFileSync(path.resolve(__dirname, 'vista.html'), 'utf8');
    await page.setContent(htmlContent, { waitUntil: 'domcontentloaded' });

    // Establecer el estilo CSS
    const cssContent = fs.readFileSync(path.resolve(__dirname, 'invoice.css'), 'utf8');
    await page.addStyleTag({ content: cssContent });

    // Generar el PDF
    await page.pdf({
        path: pdfFilePath,
        format: 'A4',
        printBackground: true
    });

    await browser.close();
})();