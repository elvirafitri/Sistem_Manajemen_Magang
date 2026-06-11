import Chart from 'chart.js/auto';

const areaEl = document.getElementById('chart-area-kehadiran');
const barEl = document.getElementById('chart-bar-absensi');

if (areaEl) {
    const labels = JSON.parse(areaEl.dataset.labels || '[]');
    const hadir = JSON.parse(areaEl.dataset.hadir || '[]');

    new Chart(areaEl, {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Kehadiran (hadir)',
                    data: hadir,
                    fill: true,
                    borderColor: 'rgb(37, 99, 235)',
                    backgroundColor: 'rgba(37, 99, 235, 0.12)',
                    tension: 0.35,
                    borderWidth: 2,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#64748b', font: { size: 11 } },
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(148, 163, 184, 0.2)' },
                    ticks: { color: '#64748b', font: { size: 11 } },
                },
            },
        },
    });
}

if (barEl) {
    const labels = JSON.parse(barEl.dataset.labels || '[]');
    const hadir = JSON.parse(barEl.dataset.hadir || '[]');
    const izinSakit = JSON.parse(barEl.dataset.izinSakit || '[]');

    new Chart(barEl, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    label: 'Hadir',
                    data: hadir,
                    backgroundColor: 'rgba(37, 99, 235, 0.85)',
                    borderRadius: 6,
                },
                {
                    label: 'Izin & sakit',
                    data: izinSakit,
                    backgroundColor: 'rgba(148, 163, 184, 0.65)',
                    borderRadius: 6,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 12, padding: 16, font: { size: 11 } },
                },
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#64748b', font: { size: 11 } },
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(148, 163, 184, 0.2)' },
                    ticks: { color: '#64748b', font: { size: 11 } },
                },
            },
        },
    });
}
