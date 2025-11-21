<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen Model - Diabetes Prediction System</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                    <a href="{{ url('/models') }}" class="hover:text-blue-200 font-semibold">Model</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 px-4">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Manajemen Model ML</h2>
            <button onclick="showAddModelModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-plus mr-2"></i>Tambah Model
            </button>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-brain text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Total Model</h3>
                        <p class="text-2xl font-semibold text-gray-900" id="totalModels">0</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Model Aktif</h3>
                        <p class="text-2xl font-semibold text-gray-900" id="activeModels">0</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-chart-line text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Akurasi Rata-rata</h3>
                        <p class="text-2xl font-semibold text-gray-900" id="avgAccuracy">0%</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                        <i class="fas fa-play text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Training Runs</h3>
                        <p class="text-2xl font-semibold text-gray-900" id="totalTrainingRuns">0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Models Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            <!-- Model cards will be populated here -->
            <div id="modelsContainer" class="space-y-6">
                <!-- Loading state -->
                <div id="loadingState" class="text-center py-8 col-span-full">
                    <i class="fas fa-spinner fa-spin text-2xl text-blue-600"></i>
                    <p class="mt-2 text-gray-600">Memuat data model...</p>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="hidden text-center py-12">
            <i class="fas fa-brain text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900">Belum ada model</h3>
            <p class="text-gray-600 mt-2">Mulai dengan menambahkan model pertama Anda.</p>
            <button onclick="showAddModelModal()" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-plus mr-2"></i>Tambah Model
            </button>
        </div>
    </div>

    <!-- Add Model Modal -->
    <div id="addModelModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="flex justify-between items-center p-6 border-b">
                <h3 class="text-lg font-semibold">Tambah Model Baru</h3>
                <button onclick="hideAddModelModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="addModelForm" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Model</label>
                    <input type="text" name="name" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Contoh: Random Forest v1.0">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Algoritma</label>
                    <select name="algorithm" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Algoritma</option>
                        <option value="random_forest">Random Forest</option>
                        <option value="logistic_regression">Logistic Regression</option>
                        <option value="svm">Support Vector Machine</option>
                        <option value="neural_network">Neural Network</option>
                        <option value="xgboost">XGBoost</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dataset Version</label>
                    <select name="dataset_version_id" id="datasetSelect" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Dataset</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Deskripsi model dan parameter..."></textarea>
                </div>
            </form>
            <div class="flex justify-end space-x-3 p-6 border-t">
                <button type="button" onclick="hideAddModelModal()" 
                        class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="button" onclick="addModel()" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Simpan
                </button>
            </div>
        </div>
    </div>

    <!-- Training Modal -->
    <div id="trainingModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="flex justify-between items-center p-6 border-b">
                <h3 class="text-lg font-semibold">Mulai Training</h3>
                <button onclick="hideTrainingModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6">
                <p class="text-gray-600 mb-4">Mulai proses training untuk model <span id="trainingModelName" class="font-semibold"></span>?</p>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                    <div class="flex">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                        <div>
                            <p class="text-sm text-yellow-800">
                                Proses training mungkin memakan waktu beberapa menit tergantung ukuran dataset.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end space-x-3 p-6 border-t">
                <button type="button" onclick="hideTrainingModal()" 
                        class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="button" onclick="startTraining()" 
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Mulai Training
                </button>
            </div>
        </div>
    </div>

    <script>
        let models = [];
        let datasets = [];
        let currentModelId = null;

        // Fetch models and datasets
        async function fetchModels() {
            try {
                showLoading();
                const [modelsRes, datasetsRes] = await Promise.all([
                    fetch('/api/models'),
                    fetch('/api/datasets')
                ]);

                models = (await modelsRes.json()).data || [];
                datasets = (await datasetsRes.json()).data || [];

                updateStats();
                renderModels();
                populateDatasetSelect();
            } catch (error) {
                console.error('Error fetching models:', error);
                showError('Gagal memuat data model');
            }
        }

        function updateStats() {
            const totalModels = models.length;
            const activeModels = models.filter(m => m.is_active).length;
            const avgAccuracy = models.length > 0 
                ? (models.reduce((sum, m) => sum + (m.accuracy || 0), 0) / models.length * 100).toFixed(1)
                : 0;
            
            const totalTrainingRuns = models.reduce((sum, m) => sum + (m.training_runs_count || 0), 0);

            document.getElementById('totalModels').textContent = totalModels;
            document.getElementById('activeModels').textContent = activeModels;
            document.getElementById('avgAccuracy').textContent = `${avgAccuracy}%`;
            document.getElementById('totalTrainingRuns').textContent = totalTrainingRuns;
        }

        function renderModels() {
            const container = document.getElementById('modelsContainer');
            const loadingState = document.getElementById('loadingState');
            const emptyState = document.getElementById('emptyState');

            if (models.length === 0) {
                loadingState.classList.add('hidden');
                emptyState.classList.remove('hidden');
                return;
            }

            loadingState.classList.add('hidden');
            emptyState.classList.add('hidden');

            container.innerHTML = models.map(model => `
                <div class="bg-white rounded-lg shadow hover:shadow-md transition">
                    <div class="p-6">
                        <!-- Header -->
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">${model.name}</h3>
                                <p class="text-sm text-gray-500">${model.algorithm}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full ${
                                model.is_active 
                                    ? 'bg-green-100 text-green-800' 
                                    : 'bg-gray-100 text-gray-800'
                            }">
                                ${model.is_active ? 'Aktif' : 'Nonaktif'}
                            </span>
                        </div>

                        <!-- Metrics -->
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-blue-600">${model.accuracy ? (model.accuracy * 100).toFixed(1) + '%' : 'N/A'}</p>
                                <p class="text-xs text-gray-500">Akurasi</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-purple-600">${model.training_runs_count || 0}</p>
                                <p class="text-xs text-gray-500">Training</p>
                            </div>
                        </div>

                        <!-- Description -->
                        <p class="text-sm text-gray-600 mb-4 line-clamp-2">${model.description || 'Tidak ada deskripsi'}</p>

                        <!-- Actions -->
                        <div class="flex justify-between items-center">
                            <div class="flex space-x-2">
                                <button onclick="toggleModelStatus(${model.id}, ${!model.is_active})" 
                                        class="text-sm px-3 py-1 rounded ${
                                            model.is_active 
                                                ? 'bg-red-100 text-red-700 hover:bg-red-200' 
                                                : 'bg-green-100 text-green-700 hover:bg-green-200'
                                        }">
                                    ${model.is_active ? 'Nonaktifkan' : 'Aktifkan'}
                                </button>
                                <button onclick="showTrainingModal(${model.id})" 
                                        class="text-sm px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                                    Training
                                </button>
                            </div>
                            <button onclick="deleteModel(${model.id})" 
                                    class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function populateDatasetSelect() {
            const select = document.getElementById('datasetSelect');
            select.innerHTML = '<option value="">Pilih Dataset</option>';
            
            datasets.forEach(dataset => {
                const option = document.createElement('option');
                option.value = dataset.id;
                option.textContent = `${dataset.name} (v${dataset.version})`;
                select.appendChild(option);
            });
        }

        function showLoading() {
            document.getElementById('loadingState').classList.remove('hidden');
            document.getElementById('emptyState').classList.add('hidden');
        }

        function showError(message) {
            alert(message);
        }

        function showAddModelModal() {
            document.getElementById('addModelModal').classList.remove('hidden');
        }

        function hideAddModelModal() {
            document.getElementById('addModelModal').classList.add('hidden');
            document.getElementById('addModelForm').reset();
        }

        function showTrainingModal(modelId) {
            currentModelId = modelId;
            const model = models.find(m => m.id == modelId);
            if (model) {
                document.getElementById('trainingModelName').textContent = model.name;
                document.getElementById('trainingModal').classList.remove('hidden');
            }
        }

        function hideTrainingModal() {
            document.getElementById('trainingModal').classList.add('hidden');
            currentModelId = null;
        }

        async function addModel() {
            const form = document.getElementById('addModelForm');
            const formData = new FormData(form);
            const data = Object.fromEntries(formData);

            try {
                const response = await fetch('/api/models', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });

                if (response.ok) {
                    hideAddModelModal();
                    fetchModels(); // Refresh the list
                } else {
                    throw new Error('Gagal menambahkan model');
                }
            } catch (error) {
                console.error('Error adding model:', error);
                showError('Gagal menambahkan model');
            }
        }

        async function toggleModelStatus(modelId, newStatus) {
            try {
                const response = await fetch(`/api/models/${modelId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ is_active: newStatus })
                });

                if (response.ok) {
                    fetchModels(); // Refresh the list
                } else {
                    throw new Error('Gagal mengubah status model');
                }
            } catch (error) {
                console.error('Error toggling model status:', error);
                showError('Gagal mengubah status model');
            }
        }

        async function startTraining() {
            if (!currentModelId) return;

            try {
                hideTrainingModal();
                
                const response = await fetch('/api/training/start', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ model_id: currentModelId })
                });

                if (response.ok) {
                    alert('Training berhasil dimulai!');
                    fetchModels(); // Refresh the list
                } else {
                    throw new Error('Gagal memulai training');
                }
            } catch (error) {
                console.error('Error starting training:', error);
                showError('Gagal memulai training');
            }
        }

        async function deleteModel(modelId) {
            if (!confirm('Apakah Anda yakin ingin menghapus model ini?')) {
                return;
            }

            try {
                const response = await fetch(`/api/models/${modelId}`, {
                    method: 'DELETE'
                });

                if (response.ok) {
                    fetchModels(); // Refresh the list
                } else {
                    throw new Error('Gagal menghapus model');
                }
            } catch (error) {
                console.error('Error deleting model:', error);
                showError('Gagal menghapus model');
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', fetchModels);
    </script>
</body>
</html>
