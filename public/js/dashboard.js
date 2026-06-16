document.addEventListener('DOMContentLoaded', function () {
    const dashboardData = window.dashboardData || {
        kpis: { siteVisits: 0, linkVisits: 0, projects: 0, messages: 0 },
        visitsDaily: { labels: [], site: [], links: [] },
        visitsWeekly: { labels: [], site: [], links: [] },
        visitsMonthly: { labels: [], site: [], links: [] },
    };

    const setValue = (id, value) => {
        const element = document.getElementById(id);
        if (element) element.textContent = Number(value || 0).toLocaleString('pt-BR');
    };

    setValue('site-visits', dashboardData.kpis.siteVisits);
    setValue('link-visits', dashboardData.kpis.linkVisits);
    setValue('total-projects', dashboardData.kpis.projects);
    setValue('total-messages', dashboardData.kpis.messages);

    if (!window.Chart) return;

    Chart.defaults.color = '#8b949e';
    Chart.defaults.borderColor = '#30363d';
    Chart.defaults.font.family = 'Karla, system-ui, sans-serif';

    const options = {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: {
                labels: { boxWidth: 10, boxHeight: 10, usePointStyle: true },
            },
            tooltip: {
                backgroundColor: '#161b22',
                titleColor: '#f0f6fc',
                bodyColor: '#c9d1d9',
                borderColor: '#30363d',
                borderWidth: 1,
                padding: 12,
            },
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { precision: 0 },
                grid: { color: '#21262d' },
            },
            x: {
                grid: { display: false },
            },
        },
    };

    const makeDatasets = (series) => [
        {
            label: 'Site',
            data: series.site,
            borderColor: '#58a6ff',
            backgroundColor: 'rgba(88, 166, 255, .16)',
            borderWidth: 2,
            tension: .35,
            fill: true,
            pointRadius: 2,
            pointHoverRadius: 4,
        },
        {
            label: 'Links',
            data: series.links,
            borderColor: '#3fb950',
            backgroundColor: 'rgba(63, 185, 80, .14)',
            borderWidth: 2,
            tension: .35,
            fill: true,
            pointRadius: 2,
            pointHoverRadius: 4,
        },
    ];

    [
        ['dailyVisitsChart', dashboardData.visitsDaily],
        ['weeklyVisitsChart', dashboardData.visitsWeekly],
        ['monthlyVisitsChart', dashboardData.visitsMonthly],
    ].forEach(([id, series]) => {
        const canvas = document.getElementById(id);
        if (!canvas) return;

        new Chart(canvas.getContext('2d'), {
            type: 'line',
            data: {
                labels: series.labels,
                datasets: makeDatasets(series),
            },
            options,
        });
    });
});
