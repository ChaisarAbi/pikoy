<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Diabetes Prediction System - Dashboard</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-heartbeat text-2xl"></i>
                    <h1 class="text-xl font-bold">Diabetes Prediction System</h1>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ url('/dashboard') }}" class="hover:text-blue-200">Dashboard</a>
                    <a href="{{ url('/patients') }}" class="hover:text-blue-200">Pasien</a>
                    <a href="{{ url('/predict') }}" class="hover:text-blue-200">Prediksi</a>
                    <a href="{{ url('/models') }}" class="hover:text-blue-200">Model</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 px-4">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Total Pasien</h3>
                        <p class="text-2xl font-semibold text-gray-900" id="totalPatients">0</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-stethoscope text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Pemeriksaan</h3>
                        <p class="text-2xl font-semibold text-gray-900" id="totalExaminations">0</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-brain text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Model ML</h3>
                        <p class="text-2xl font-semibold text-gray-900" id="totalModels">0</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                        <i class="fas fa-chart-line text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Prediksi</h3>
                        <p class="text-2xl font-semibold text-gray-900" id="totalPredictions">0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Prediction Results Chart -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Hasil Prediksi Diabetes</h3>
                <canvas id="predictionChart" width="400" height="200"></canvas>
            </div>
            
            <!-- Recent Predictions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Prediksi Terbaru</h3>
                <div id="recentPredictions" class="space-y-3">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Aksi Cepat</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ url('/patients/create') }}" class="bg-blue-600 text-white p-4 rounded-lg text-center hover:bg-blue-700 transition">
                    <i class="fas fa-user-plus text-2xl mb-2"></i>
                    <p>Tambah Pasien</p>
                </a>
                <a href="{{ url('/predict') }}" class="bg-green-600 text-white p-4 rounded-lg text-center hover:bg-green-700 transition">
                    <i class="fas fa-calculator text-2xl mb-2"></i>
                    <p>Buat Prediksi</p>
                </a>
                <a href="{{ url('/models') }}" class="bg-purple-600 text-white p-4 rounded-lg text-center hover:bg-purple-700 transition">
                    <i class="fas fa-cogs text-2xl mb-2"></i>
                    <p>Kelola Model</p>
                </a>
            </div>
        </div>
    </div>

    <script>
        // Fetch dashboard data
        async function fetchDashboardData() {
            try {
                // Fetch dashboard stats
                const dashboardRes = await fetch('/api/dashboard/stats');
                const dashboardData = await dashboardRes.json();

                const stats = dashboardData.stats;
                const recentPredictions = dashboardData.recent_predictions;
                const predictionDistribution = dashboardData.prediction_distribution;

                // Update stats
                document.getElementById('totalPatients').textContent = stats.total_patients || 0;
                document.getElementById('totalExaminations').textContent = stats.total_examinations || 0;
                document.getElementById('totalModels').textContent = stats.total_models || 0;
                document.getElementById('totalPredictions').textContent = stats.total_predictions || 0;

                // Update recent predictions
                updateRecentPredictions(recentPredictions || []);

                // Update chart
                updatePredictionChart(predictionDistribution || { diabetes: 0, normal: 0 });

            } catch (error) {
                console.error('Error fetching dashboard data:', error);
                // Fallback to individual API calls
                fetchFallbackData();
            }
        }

        // Fallback method if dashboard API fails
        async function fetchFallbackData() {
            try {
                const [patientsRes, examinationsRes, modelsRes, predictionsRes] = await Promise.all([
                    fetch('/api/patients'),
                    fetch('/api/examinations'),
                    fetch('/api/models'),
                    fetch('/api/predictions')
                ]);

                const patients = await patientsRes.json();
                const examinations = await examinationsRes.json();
                const models = await modelsRes.json();
                const predictions = await predictionsRes.json();

                // Update stats
                document.getElementById('totalPatients').textContent = patients.data?.length || 0;
                document.getElementById('totalExaminations').textContent = examinations.data?.length || 0;
                document.getElementById('totalModels').textContent = models.data?.length || 0;
                document.getElementById('totalPredictions').textContent = predictions.data?.length || 0;

                // Update recent predictions
                updateRecentPredictions(predictions.data?.slice(0, 5) || []);

                // Update chart
                updatePredictionChart(predictions.data || []);

            } catch (error) {
                console.error('Error fetching fallback data:', error);
            }
        }

        function updateRecentPredictions(predictions) {
            const container = document.getElementById('recentPredictions');
            if (predictions.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-center">Belum ada prediksi</p>';
                return;
            }

            container.innerHTML = predictions.map(pred => `
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <div>
                        <p class="font-medium">Pasien ID: ${pred.patient_id}</p>
                        <p class="text-sm text-gray-600">${new Date(pred.created_at).toLocaleDateString('id-ID')}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm font-medium ${
                        pred.prediction_result === 'diabetes' 
                            ? 'bg-red-100 text-red-800' 
                            : 'bg-green-100 text-green-800'
                    }">
                        ${pred.prediction_result === 'diabetes' ? 'Diabetes' : 'Normal'}
                    </span>
                </div>
            `).join('');
        }

        function updatePredictionChart(predictionDistribution) {
            const ctx = document.getElementById('predictionChart').getContext('2d');
            
            // Destroy existing chart if it exists
            if (window.predictionChartInstance) {
                window.predictionChartInstance.destroy();
            }

            // Use the distribution data directly from API
            const diabetesCount = predictionDistribution.diabetes || 0;
            const normalCount = predictionDistribution.normal || 0;

            window.predictionChartInstance = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Diabetes', 'Normal'],
                    datasets: [{
                        data: [diabetesCount, normalCount],
                        backgroundColor: ['#ef4444', '#10b981'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', fetchDashboardData);
    </script>
</body>
</html>
