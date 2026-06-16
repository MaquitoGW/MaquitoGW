@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="admin-page">
        <div class="admin-header">
            <div>
                <span class="admin-eyebrow">Analytics</span>
                <h1 class="admin-title">Dashboard</h1>
                <p class="admin-subtitle">Visitas do portfólio, página de links e dados principais do painel.</p>
            </div>
        </div>

        <div class="dashboard-grid analytics">
            <div class="kpi-card">
                <div class="kpi-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="kpi-data">
                    <p class="kpi-title">Visitas ao site</p>
                    <p class="kpi-value" id="site-visits">0</p>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon">
                    <i class="fas fa-link"></i>
                </div>
                <div class="kpi-data">
                    <p class="kpi-title">Visitas aos links</p>
                    <p class="kpi-value" id="link-visits">0</p>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon">
                    <i class="fas fa-code-branch"></i>
                </div>
                <div class="kpi-data">
                    <p class="kpi-title">Projetos</p>
                    <p class="kpi-value" id="total-projects">0</p>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="kpi-data">
                    <p class="kpi-title">Mensagens</p>
                    <p class="kpi-value" id="total-messages">0</p>
                </div>
            </div>

            <div class="chart-card chart-daily">
                <h3>Visitas nas últimas 24h</h3>
                <canvas id="dailyVisitsChart"></canvas>
            </div>

            <div class="chart-card chart-weekly">
                <h3>Visitas da semana</h3>
                <canvas id="weeklyVisitsChart"></canvas>
            </div>

            <div class="chart-card chart-monthly">
                <h3>Visitas por mês</h3>
                <canvas id="monthlyVisitsChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        window.dashboardData = @json($dashboardData);
    </script>
    <script src="/js/dashboard.js"></script>
@endsection
