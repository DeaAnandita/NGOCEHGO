// generate-bar.js
const puppeteer = require('puppeteer');
const fs = require('fs');
const path = require('path');

(async () => {
    const data = JSON.parse(process.argv[2]); // terima JSON dari PHP

    const html = `
<!DOCTYPE html>
<html>
<head>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <canvas id="barChart" width="600" height="400"></canvas>
  <script>
    const ctx = document.getElementById('barChart').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ${JSON.stringify(data.labels)},
        datasets: [{
          label: 'Jumlah Dimiliki',
          data: ${JSON.stringify(data.values)},
          backgroundColor: '#4f46e5'
        }]
      },
      options: {
        responsive: false,
        scales: {
          y: {
            beginAtZero: true,
            min: 0,
            max: ${data.max},
            ticks: {
              stepSize: 1,
              precision: 0
            }
          }
        },
        plugins: {
          legend: { display: true, position: 'top' },
          title: { display: true, text: '5 Aset Keluarga Paling Banyak Dimiliki' }
        }
      }
    });
  </script>
</body>
</html>`;

    const browser = await puppeteer.launch({ headless: true });
    const page = await browser.newPage();
    await page.setContent(html, { waitUntil: 'networkidle0' });
    await page.waitForSelector('canvas');

    const canvas = await page.$('#barChart');
    const imageBuffer = await canvas.screenshot({ omitBackground: true });

    await browser.close();

    // Output base64
    console.log('data:image/png;base64,' + imageBuffer.toString('base64'));
})();