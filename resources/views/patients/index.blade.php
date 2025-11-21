<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen Pasien - Diabetes Prediction System</title>
    
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
                    <a href="{{ url('/patients') }}" class="hover:text-blue-200 font-semibold">Pasien</a>
                    <a href="{{ url('/predict') }}" class="hover:text-blue-200">Prediksi</a>
                    <a href="{{ url('/models') }}" class="hover:text-blue-200">Model</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 px-4">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Manajemen Pasien</h2>
            <button onclick="showAddPatientModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-user-plus mr-2"></i>Tambah Pasien
            </button>
        </div>

        <!-- Search and Filter -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" id="searchInput" placeholder="Cari pasien..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <select id="genderFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Gender</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Patients Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Umur</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">BMI</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Glukosa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="patientsTableBody" class="bg-white divide-y divide-gray-200">
                        <!-- Will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Loading State -->
        <div id="loadingState" class="text-center py-8">
            <i class="fas fa-spinner fa-spin text-2xl text-blue-600"></i>
            <p class="mt-2 text-gray-600">Memuat data pasien...</p>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="hidden text-center py-12">
            <i class="fas fa-users text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900">Belum ada pasien</h3>
            <p class="text-gray-600 mt-2">Mulai dengan menambahkan pasien pertama Anda.</p>
            <button onclick="showAddPatientModal()" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-user-plus mr-2"></i>Tambah Pasien
            </button>
        </div>
    </div>

    <!-- Add Patient Modal -->
    <div id="addPatientModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="flex justify-between items-center p-6 border-b">
                <h3 class="text-lg font-semibold">Tambah Pasien Baru</h3>
                <button onclick="hideAddPatientModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="addPatientForm" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                    <input type="text" name="name" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Umur</label>
                    <input type="number" name="age" required min="1" max="120"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                    <select name="gender" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Gender</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">BMI</label>
                    <input type="number" name="bmi" step="0.1" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Glukosa Darah</label>
                    <input type="number" name="blood_glucose" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </form>
            <div class="flex justify-end space-x-3 p-6 border-t">
                <button type="button" onclick="hideAddPatientModal()" 
                        class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="button" onclick="addPatient()" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Simpan
                </button>
            </div>
        </div>
    </div>

    <script>
        let patients = [];

        // Fetch patients data
        async function fetchPatients() {
            try {
                showLoading();
                const response = await fetch('/api/patients');
                const data = await response.json();
                patients = data.data || [];
                renderPatients(patients);
            } catch (error) {
                console.error('Error fetching patients:', error);
                showError('Gagal memuat data pasien');
            }
        }

        function renderPatients(patientsToRender) {
            const tbody = document.getElementById('patientsTableBody');
            const loadingState = document.getElementById('loadingState');
            const emptyState = document.getElementById('emptyState');

            if (patientsToRender.length === 0) {
                tbody.innerHTML = '';
                loadingState.classList.add('hidden');
                emptyState.classList.remove('hidden');
                return;
            }

            loadingState.classList.add('hidden');
            emptyState.classList.add('hidden');

            tbody.innerHTML = patientsToRender.map(patient => `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${patient.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${patient.name}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${patient.age} tahun</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <span class="px-2 py-1 text-xs rounded-full ${
                            patient.gender === 'L' 
                                ? 'bg-blue-100 text-blue-800' 
                                : 'bg-pink-100 text-pink-800'
                        }">
                            ${patient.gender === 'L' ? 'Laki-laki' : 'Perempuan'}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${patient.bmi}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${patient.blood_glucose} mg/dL</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="viewPatientDetails(${patient.id})" class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="deletePatient(${patient.id})" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        function showLoading() {
            document.getElementById('loadingState').classList.remove('hidden');
            document.getElementById('emptyState').classList.add('hidden');
        }

        function showError(message) {
            alert(message);
        }

        function showAddPatientModal() {
            document.getElementById('addPatientModal').classList.remove('hidden');
        }

        function hideAddPatientModal() {
            document.getElementById('addPatientModal').classList.add('hidden');
            document.getElementById('addPatientForm').reset();
        }

        async function addPatient() {
            const form = document.getElementById('addPatientForm');
            const formData = new FormData(form);
            const data = Object.fromEntries(formData);

            try {
                const response = await fetch('/api/patients', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });

                if (response.ok) {
                    hideAddPatientModal();
                    fetchPatients(); // Refresh the list
                } else {
                    throw new Error('Gagal menambahkan pasien');
                }
            } catch (error) {
                console.error('Error adding patient:', error);
                showError('Gagal menambahkan pasien');
            }
        }

        function viewPatientDetails(patientId) {
            window.location.href = `/patients/${patientId}`;
        }

        async function deletePatient(patientId) {
            if (!confirm('Apakah Anda yakin ingin menghapus pasien ini?')) {
                return;
            }

            try {
                const response = await fetch(`/api/patients/${patientId}`, {
                    method: 'DELETE'
                });

                if (response.ok) {
                    fetchPatients(); // Refresh the list
                } else {
                    throw new Error('Gagal menghapus pasien');
                }
            } catch (error) {
                console.error('Error deleting patient:', error);
                showError('Gagal menghapus pasien');
            }
        }

        // Search and filter functionality
        function setupSearchAndFilter() {
            const searchInput = document.getElementById('searchInput');
            const genderFilter = document.getElementById('genderFilter');

            function filterPatients() {
                const searchTerm = searchInput.value.toLowerCase();
                const genderValue = genderFilter.value;

                const filtered = patients.filter(patient => {
                    const matchesSearch = patient.name.toLowerCase().includes(searchTerm) ||
                                         patient.id.toString().includes(searchTerm);
                    const matchesGender = !genderValue || patient.gender === genderValue;
                    
                    return matchesSearch && matchesGender;
                });

                renderPatients(filtered);
            }

            searchInput.addEventListener('input', filterPatients);
            genderFilter.addEventListener('change', filterPatients);
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            fetchPatients();
            setupSearchAndFilter();
        });
    </script>
</body>
</html>
