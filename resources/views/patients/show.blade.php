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
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ url('/patients') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Daftar Pasien
            </a>
        </div>

        <!-- Patient Info -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-start mb-4">
                <h2 class="text-2xl font-bold text-gray-900">Detail Pasien</h2>
                <div class="flex space-x-2">
                    <a href="{{ url('/predict') }}?patient_id={{ $patient->patient_id }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-calculator mr-2"></i>
                        Buat Prediksi
                    </a>
                    <button onclick="editPatient()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-edit mr-2"></i>
                        Edit
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">NIK</h3>
                    <p class="text-lg font-semibold" id="patientNik">{{ $patient->nik }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Nama</h3>
                    <p class="text-lg font-semibold" id="patientName">{{ $patient->name }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Tanggal Lahir</h3>
                    <p class="text-lg font-semibold" id="patientDob">{{ $patient->dob }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Jenis Kelamin</h3>
                    <p class="text-lg font-semibold" id="patientSex">{{ $patient->sex == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Alamat</h3>
                    <p class="text-lg font-semibold" id="patientAddress">{{ $patient->address }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">BMI</h3>
                    <p class="text-lg font-semibold" id="patientBmi">{{ $patient->bmi ?? 'Tidak ada data' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Glukosa Darah</h3>
                    <p class="text-lg font-semibold" id="patientGlucose">{{ $patient->blood_glucose ?? 'Tidak ada data' }}</p>
                </div>
            </div>
        </div>

        <!-- Patient Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-stethoscope text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Total Pemeriksaan</h3>
                        <p class="text-2xl font-semibold text-gray-900" id="totalExaminations">0</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-chart-line text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Total Prediksi</h3>
                        <p class="text-2xl font-semibold text-gray-900" id="totalPredictions">0</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <i class="fas fa-exclamation-triangle text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Prediksi Diabetes</h3>
                        <p class="text-2xl font-semibold text-gray-900" id="diabetesPredictions">0</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Prediksi Normal</h3>
                        <p class="text-2xl font-semibold text-gray-900" id="normalPredictions">0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prediction History -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold mb-4">Riwayat Prediksi</h3>
            <div id="predictionHistory" class="space-y-4">
                <!-- Will be populated by JavaScript -->
            </div>
        </div>
    </div>

    <!-- Edit Patient Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
            <h3 class="text-xl font-semibold mb-4">Edit Pasien</h3>
            <form id="editPatientForm">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">NIK</label>
                        <input type="text" id="editNik" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" id="editName" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                        <input type="date" id="editDob" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                        <select id="editSex" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2" required>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea id="editAddress" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2" rows="3" required></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">BMI</label>
                        <input type="number" step="0.1" id="editBmi" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Glukosa Darah</label>
                        <input type="number" step="0.1" id="editGlucose" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const patientId = {{ $patient->patient_id }};

        // Fetch patient stats
        async function fetchPatientStats() {
            try {
                const response = await fetch(`/api/patients/${patientId}/stats`);
                const data = await response.json();

                // Update stats
                document.getElementById('totalExaminations').textContent = data.stats.total_examinations;
                document.getElementById('totalPredictions').textContent = data.stats.total_predictions;
                document.getElementById('diabetesPredictions').textContent = data.stats.diabetes_predictions;
                document.getElementById('normalPredictions').textContent = data.stats.normal_predictions;

                // Update prediction history
                updatePredictionHistory(data.prediction_history);

            } catch (error) {
                console.error('Error fetching patient stats:', error);
            }
        }

        function updatePredictionHistory(predictions) {
            const container = document.getElementById('predictionHistory');
            if (predictions.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-center">Belum ada prediksi untuk pasien ini</p>';
                return;
            }

            container.innerHTML = predictions.map(pred => `
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="font-medium">Model: ${pred.model?.name || 'Unknown'}</p>
                            <p class="text-sm text-gray-600">${new Date(pred.created_at).toLocaleDateString('id-ID')}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-sm font-medium ${
                            pred.prediction_result === 'diabetes' || pred.predicted_label === 1
                                ? 'bg-red-100 text-red-800' 
                                : 'bg-green-100 text-green-800'
                        }">
                            ${pred.prediction_result === 'diabetes' || pred.predicted_label === 1 ? 'Diabetes' : 'Normal'}
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-600">Probability:</p>
                            <p class="font-medium">${(pred.probability * 100).toFixed(1)}%</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Accuracy Model:</p>
                            <p class="font-medium">${pred.model?.accuracy ? (pred.model.accuracy * 100).toFixed(1) + '%' : 'N/A'}</p>
                        </div>
                    </div>
                    ${pred.explanation ? `
                        <div class="mt-3">
                            <p class="text-sm text-gray-600 mb-2">Feature Importance:</p>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                ${Object.entries(pred.explanation).map(([key, value]) => `
                                    <div class="flex justify-between">
                                        <span class="capitalize">${key.replace('_', ' ')}:</span>
                                        <span class="font-medium">${value}%</span>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    ` : ''}
                </div>
            `).join('');
        }

        // Edit patient modal functions
        function editPatient() {
            document.getElementById('editNik').value = document.getElementById('patientNik').textContent;
            document.getElementById('editName').value = document.getElementById('patientName').textContent;
            document.getElementById('editDob').value = document.getElementById('patientDob').textContent;
            document.getElementById('editSex').value = document.getElementById('patientSex').textContent === 'Laki-laki' ? 'L' : 'P';
            document.getElementById('editAddress').value = document.getElementById('patientAddress').textContent;
            document.getElementById('editBmi').value = document.getElementById('patientBmi').textContent !== 'Tidak ada data' ? document.getElementById('patientBmi').textContent : '';
            document.getElementById('editGlucose').value = document.getElementById('patientGlucose').textContent !== 'Tidak ada data' ? document.getElementById('patientGlucose').textContent : '';
            
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // Handle form submission
        document.getElementById('editPatientForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = {
                nik: document.getElementById('editNik').value,
                name: document.getElementById('editName').value,
                dob: document.getElementById('editDob').value,
                sex: document.getElementById('editSex').value,
                address: document.getElementById('editAddress').value,
                bmi: document.getElementById('editBmi').value || null,
                blood_glucose: document.getElementById('editGlucose').value || null
            };

            try {
                const response = await fetch(`/api/patients/${patientId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                if (response.ok) {
                    // Update displayed data
                    document.getElementById('patientNik').textContent = formData.nik;
                    document.getElementById('patientName').textContent = formData.name;
                    document.getElementById('patientDob').textContent = formData.dob;
                    document.getElementById('patientSex').textContent = formData.sex === 'L' ? 'Laki-laki' : 'Perempuan';
                    document.getElementById('patientAddress').textContent = formData.address;
                    document.getElementById('patientBmi').textContent = formData.bmi || 'Tidak ada data';
                    document.getElementById('patientGlucose').textContent = formData.blood_glucose || 'Tidak ada data';
                    
                    closeEditModal();
                    alert('Data pasien berhasil diperbarui');
                } else {
                    alert('Gagal memperbarui data pasien');
                }
            } catch (error) {
                console.error('Error updating patient:', error);
                alert('Terjadi kesalahan saat memperbarui data pasien');
            }
        });

        // Initialize page
        document.addEventListener('DOMContentLoaded', fetchPatientStats);
    </script>
</body>
</html>
