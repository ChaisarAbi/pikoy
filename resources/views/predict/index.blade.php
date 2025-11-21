<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Prediksi Diabetes - Diabetes Prediction System</title>
    
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
                    <a href="{{ url('/predict') }}" class="hover:text-blue-200 font-semibold">Prediksi</a>
                    <a href="{{ url('/models') }}" class="hover:text-blue-200">Model</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto py-6 px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Prediksi Diabetes</h2>
            <p class="text-gray-600">Masukkan data pasien untuk memprediksi risiko diabetes</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Prediction Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Data Pasien</h3>
                    
                    <form id="predictionForm" class="space-y-4">
                        <!-- Patient Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Pasien</label>
                            <select id="patientSelect" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih pasien yang sudah terdaftar</option>
                            </select>
                            <p class="text-sm text-gray-500 mt-1">Atau masukkan data pasien baru di bawah</p>
                        </div>

                        <!-- Basic Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                                <input type="text" name="name" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="Nama pasien">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Umur</label>
                                <input type="number" name="age" min="1" max="120"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="Umur dalam tahun">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                                <select name="gender" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Pilih Gender</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">BMI</label>
                                <input type="number" name="bmi" step="0.1"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="Body Mass Index">
                            </div>
                        </div>

                        <!-- Medical Parameters -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Glukosa Darah (mg/dL)</label>
                                <input type="number" name="blood_glucose"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="Kadar glukosa darah">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tekanan Darah Sistolik</label>
                                <input type="number" name="systolic_bp"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="Tekanan darah atas">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tekanan Darah Diastolik</label>
                                <input type="number" name="diastolic_bp"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="Tekanan darah bawah">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kolesterol Total (mg/dL)</label>
                                <input type="number" name="total_cholesterol"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="Total kolesterol">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">HDL Cholesterol (mg/dL)</label>
                                <input type="number" name="hdl_cholesterol"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="Kolesterol baik">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">LDL Cholesterol (mg/dL)</label>
                                <input type="number" name="ldl_cholesterol"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="Kolesterol jahat">
                            </div>
                        </div>

                        <!-- Model Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Model Prediksi</label>
                            <select name="model_id" id="modelSelect" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih model ML</option>
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4">
                            <button type="button" onclick="makePrediction()" 
                                    class="w-full bg-green-600 text-white py-3 px-4 rounded-lg hover:bg-green-700 transition font-semibold">
                                <i class="fas fa-calculator mr-2"></i>Buat Prediksi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Result Panel -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-6">
                    <h3 class="text-lg font-semibold mb-4">Hasil Prediksi</h3>
                    
                    <div id="resultPlaceholder" class="text-center py-8">
                        <i class="fas fa-chart-line text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-600">Hasil prediksi akan muncul di sini</p>
                    </div>

                    <div id="predictionResult" class="hidden">
                        <div class="text-center mb-4">
                            <div id="resultIcon" class="text-4xl mb-2"></div>
                            <h4 id="resultTitle" class="text-xl font-bold mb-2"></h4>
                            <p id="resultDescription" class="text-gray-600"></p>
                        </div>

                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Probabilitas:</span>
                                <span id="probability" class="font-semibold"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Model:</span>
                                <span id="modelName" class="font-semibold"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Waktu:</span>
                                <span id="predictionTime" class="font-semibold"></span>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t">
                            <button onclick="savePrediction()" 
                                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-save mr-2"></i>Simpan Prediksi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentPrediction = null;
        let patients = [];
        let models = [];

        // Fetch patients and models
        async function fetchInitialData() {
            try {
                const [patientsRes, modelsRes] = await Promise.all([
                    fetch('/api/patients'),
                    fetch('/api/models')
                ]);

                patients = (await patientsRes.json()).data || [];
                models = (await modelsRes.json()).data || [];

                populatePatientSelect();
                populateModelSelect();
            } catch (error) {
                console.error('Error fetching initial data:', error);
                alert('Gagal memuat data awal');
            }
        }

        function populatePatientSelect() {
            const select = document.getElementById('patientSelect');
            select.innerHTML = '<option value="">Pilih pasien yang sudah terdaftar</option>';
            
            patients.forEach(patient => {
                // Calculate age from date of birth
                const dob = new Date(patient.dob);
                const today = new Date();
                const age = Math.floor((today - dob) / (365.25 * 24 * 60 * 60 * 1000));
                
                const option = document.createElement('option');
                option.value = patient.patient_id;
                option.textContent = `${patient.name} (${age} tahun, ${patient.sex === 'L' ? 'L' : 'P'})`;
                select.appendChild(option);
            });
        }

        function populateModelSelect() {
            const select = document.getElementById('modelSelect');
            select.innerHTML = '<option value="">Pilih model ML</option>';
            
            models.forEach(model => {
                const option = document.createElement('option');
                option.value = model.model_id;
                option.textContent = `${model.name} (${model.accuracy ? (model.accuracy * 100).toFixed(1) + '%' : 'N/A'})`;
                select.appendChild(option);
            });
        }

        // Auto-fill form when patient is selected
        document.getElementById('patientSelect').addEventListener('change', function() {
            const patientId = this.value;
            if (!patientId) return;

            const patient = patients.find(p => p.patient_id == patientId);
            if (patient) {
                // Calculate age from date of birth
                const dob = new Date(patient.dob);
                const today = new Date();
                const age = Math.floor((today - dob) / (365.25 * 24 * 60 * 60 * 1000));
                
                document.querySelector('input[name="name"]').value = patient.name;
                document.querySelector('input[name="age"]').value = age;
                document.querySelector('select[name="gender"]').value = patient.sex;
                document.querySelector('input[name="bmi"]').value = patient.bmi || '';
                document.querySelector('input[name="blood_glucose"]').value = patient.blood_glucose || '';
            }
        });

        async function makePrediction() {
            const form = document.getElementById('predictionForm');
            const formData = new FormData(form);
            const data = Object.fromEntries(formData);

            // Validate required fields
            if (!data.model_id) {
                alert('Pilih model prediksi terlebih dahulu');
                return;
            }

            if (!data.name || !data.age || !data.gender || !data.bmi || !data.blood_glucose) {
                alert('Isi semua data pasien yang diperlukan');
                return;
            }

            try {
                // Show loading state
                document.getElementById('resultPlaceholder').innerHTML = `
                    <i class="fas fa-spinner fa-spin text-2xl text-blue-600 mb-2"></i>
                    <p class="text-gray-600">Memproses prediksi...</p>
                `;

                const response = await fetch('/api/predict', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(data)
                });

                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await response.text();
                    console.error('Non-JSON response:', text);
                    throw new Error('Server mengembalikan response yang tidak valid. Silakan coba lagi.');
                }

                const result = await response.json();

                if (response.ok) {
                    currentPrediction = result;
                    displayPredictionResult(result);
                } else {
                    throw new Error(result.error || result.message || 'Gagal membuat prediksi');
                }
            } catch (error) {
                console.error('Error making prediction:', error);
                document.getElementById('resultPlaceholder').innerHTML = `
                    <i class="fas fa-exclamation-triangle text-2xl text-red-600 mb-2"></i>
                    <p class="text-gray-600">${error.message}</p>
                `;
            }
        }

        function displayPredictionResult(result) {
            const resultDiv = document.getElementById('predictionResult');
            const placeholder = document.getElementById('resultPlaceholder');

            placeholder.classList.add('hidden');
            resultDiv.classList.remove('hidden');

            const isDiabetes = result.prediction_result === 'diabetes';
            const probability = result.probability ? (result.probability * 100).toFixed(1) : 'N/A';

            // Set result icon and color
            const resultIcon = document.getElementById('resultIcon');
            const resultTitle = document.getElementById('resultTitle');
            const resultDescription = document.getElementById('resultDescription');

            if (isDiabetes) {
                resultIcon.innerHTML = '<i class="fas fa-exclamation-triangle text-red-600"></i>';
                resultTitle.textContent = 'Risiko Diabetes Tinggi';
                resultTitle.className = 'text-xl font-bold mb-2 text-red-600';
                resultDescription.textContent = 'Pasien memiliki risiko diabetes yang tinggi. Disarankan untuk berkonsultasi dengan dokter.';
            } else {
                resultIcon.innerHTML = '<i class="fas fa-check-circle text-green-600"></i>';
                resultTitle.textContent = 'Risiko Diabetes Rendah';
                resultTitle.className = 'text-xl font-bold mb-2 text-green-600';
                resultDescription.textContent = 'Pasien memiliki risiko diabetes yang rendah. Tetap jaga pola hidup sehat.';
            }

            // Set other result details
            document.getElementById('probability').textContent = `${probability}%`;
            document.getElementById('modelName').textContent = getModelName(result.model_id);
            document.getElementById('predictionTime').textContent = new Date().toLocaleTimeString('id-ID');
        }

        function getModelName(modelId) {
            const model = models.find(m => m.model_id == modelId);
            return model ? model.name : 'Unknown Model';
        }

        async function savePrediction() {
            if (!currentPrediction) {
                alert('Tidak ada prediksi untuk disimpan');
                return;
            }

            try {
                const response = await fetch('/api/predictions', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(currentPrediction)
                });

                if (response.ok) {
                    alert('Prediksi berhasil disimpan!');
                    // Reset form
                    document.getElementById('predictionForm').reset();
                    document.getElementById('predictionResult').classList.add('hidden');
                    document.getElementById('resultPlaceholder').classList.remove('hidden');
                    currentPrediction = null;
                } else {
                    throw new Error('Gagal menyimpan prediksi');
                }
            } catch (error) {
                console.error('Error saving prediction:', error);
                alert('Gagal menyimpan prediksi');
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', fetchInitialData);
    </script>
</body>
</html>
