<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Pasien - Diabetes Prediction System</title>
    
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
    <div class="max-w-4xl mx-auto py-6 px-4">
        <div class="flex items-center mb-6">
            <a href="{{ url('/patients') }}" class="text-blue-600 hover:text-blue-800 mr-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="text-2xl font-bold text-gray-900">Detail Pasien</h2>
        </div>

        <!-- Loading State -->
        <div id="loadingState" class="text-center py-8">
            <i class="fas fa-spinner fa-spin text-2xl text-blue-600"></i>
            <p class="mt-2 text-gray-600">Memuat data pasien...</p>
        </div>

        <!-- Patient Details -->
        <div id="patientDetails" class="hidden">
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="bg-blue-600 text-white p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-xl font-bold" id="patientName"></h3>
                            <p class="text-blue-100" id="patientId"></p>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="editPatient()" class="bg-blue-500 hover:bg-blue-400 text-white px-4 py-2 rounded-lg transition">
                                <i class="fas fa-edit mr-2"></i>Edit
                            </button>
                            <button onclick="deletePatient()" class="bg-red-500 hover:bg-red-400 text-white px-4 py-2 rounded-lg transition">
                                <i class="fas fa-trash mr-2"></i>Hapus
                            </button>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">NIK</label>
                                <p class="mt-1 text-gray-900" id="patientNik"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Tanggal Lahir</label>
                                <p class="mt-1 text-gray-900" id="patientDob"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Umur</label>
                                <p class="mt-1 text-gray-900" id="patientAge"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Jenis Kelamin</label>
                                <p class="mt-1">
                                    <span id="patientGenderBadge" class="px-2 py-1 text-xs rounded-full"></span>
                                </p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">BMI</label>
                                <p class="mt-1 text-gray-900" id="patientBmi"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Glukosa Darah</label>
                                <p class="mt-1 text-gray-900" id="patientBloodGlucose"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Tanggal Dibuat</label>
                                <p class="mt-1 text-gray-900" id="patientCreatedAt"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Terakhir Diupdate</label>
                                <p class="mt-1 text-gray-900" id="patientUpdatedAt"></p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t">
                        <label class="block text-sm font-medium text-gray-500 mb-2">Alamat</label>
                        <p class="text-gray-900" id="patientAddress"></p>
                    </div>
                </div>
            </div>

            <!-- Predictions Section -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Riwayat Prediksi</h3>
                <div id="predictionsList" class="space-y-3">
                    <!-- Will be populated by JavaScript -->
                </div>
                <div id="noPredictions" class="hidden text-center py-8">
                    <i class="fas fa-chart-line text-4xl text-gray-400 mb-4"></i>
                    <h4 class="text-lg font-medium text-gray-900">Belum ada prediksi</h4>
                    <p class="text-gray-600 mt-2">Pasien ini belum memiliki riwayat prediksi diabetes.</p>
                    <a href="{{ url('/predict') }}" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-calculator mr-2"></i>Buat Prediksi
                    </a>
                </div>
            </div>
        </div>

        <!-- Error State -->
        <div id="errorState" class="hidden text-center py-8">
            <i class="fas fa-exclamation-triangle text-4xl text-red-500 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900">Gagal memuat data</h3>
            <p class="text-gray-600 mt-2">Terjadi kesalahan saat memuat data pasien.</p>
            <button onclick="loadPatient()" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-redo mr-2"></i>Coba Lagi
            </button>
        </div>
    </div>

    <script>
        const patientId = {{ $id }};
        let patientData = null;

        async function loadPatient() {
            try {
                showLoading();
                hideError();
                hidePatientDetails();

                const response = await fetch(`/api/patients/${patientId}`);
                const result = await response.json();

                if (response.ok) {
                    patientData = result.data;
                    displayPatientDetails();
                    loadPredictions();
                } else {
                    throw new Error(result.message || 'Gagal memuat data pasien');
                }
            } catch (error) {
                console.error('Error loading patient:', error);
                showError();
            }
        }

        function displayPatientDetails() {
            const patient = patientData;
            
            document.getElementById('patientName').textContent = patient.name;
            document.getElementById('patientId').textContent = `ID: ${patient.id}`;
            document.getElementById('patientNik').textContent = patient.nik;
            document.getElementById('patientDob').textContent = new Date(patient.dob).toLocaleDateString('id-ID');
            document.getElementById('patientAge').textContent = `${patient.age} tahun`;
            document.getElementById('patientBmi').textContent = patient.bmi ? `${patient.bmi}` : '-';
            document.getElementById('patientBloodGlucose').textContent = patient.blood_glucose ? `${patient.blood_glucose} mg/dL` : '-';
            document.getElementById('patientAddress').textContent = patient.address;
            document.getElementById('patientCreatedAt').textContent = new Date(patient.created_at).toLocaleString('id-ID');
            document.getElementById('patientUpdatedAt').textContent = new Date(patient.updated_at).toLocaleString('id-ID');

            // Gender badge
            const genderBadge = document.getElementById('patientGenderBadge');
            if (patient.gender === 'L') {
                genderBadge.textContent = 'Laki-laki';
                genderBadge.className = 'px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800';
            } else {
                genderBadge.textContent = 'Perempuan';
                genderBadge.className = 'px-2 py-1 text-xs rounded-full bg-pink-100 text-pink-800';
            }

            hideLoading();
            showPatientDetails();
        }

        async function loadPredictions() {
            try {
                const response = await fetch(`/api/patients/${patientId}/predictions`);
                const result = await response.json();

                const predictionsList = document.getElementById('predictionsList');
                const noPredictions = document.getElementById('noPredictions');

                if (response.ok && result.data && result.data.length > 0) {
                    predictionsList.innerHTML = result.data.map(pred => `
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                            <div>
                                <p class="font-medium">Prediksi ID: ${pred.prediction_id}</p>
                                <p class="text-sm text-gray-600">${new Date(pred.created_at).toLocaleString('id-ID')}</p>
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
                    noPredictions.classList.add('hidden');
                } else {
                    predictionsList.innerHTML = '';
                    noPredictions.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error loading predictions:', error);
                document.getElementById('noPredictions').classList.remove('hidden');
            }
        }

        function editPatient() {
            // Redirect to edit page or show edit modal
            alert('Fitur edit akan segera tersedia');
        }

        async function deletePatient() {
            if (!confirm('Apakah Anda yakin ingin menghapus pasien ini?')) {
                return;
            }

            try {
                const response = await fetch(`/api/patients/${patientId}`, {
                    method: 'DELETE'
                });

                if (response.ok) {
                    alert('Pasien berhasil dihapus!');
                    window.location.href = '/patients';
                } else {
                    throw new Error('Gagal menghapus pasien');
                }
            } catch (error) {
                console.error('Error deleting patient:', error);
                alert('Gagal menghapus pasien');
            }
        }

        function showLoading() {
            document.getElementById('loadingState').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('loadingState').classList.add('hidden');
        }

        function showPatientDetails() {
            document.getElementById('patientDetails').classList.remove('hidden');
        }

        function hidePatientDetails() {
            document.getElementById('patientDetails').classList.add('hidden');
        }

        function showError() {
            document.getElementById('errorState').classList.remove('hidden');
        }

        function hideError() {
            document.getElementById('errorState').classList.add('hidden');
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', loadPatient);
    </script>
</body>
</html>
