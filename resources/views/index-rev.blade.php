<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tujuan Pembelajaran - Modul Sound Horeg</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome CDN untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Custom CSS untuk efek tambahan */
        .objective-card {
            transition: all 0.3s ease-in-out;
        }
        .objective-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        .progress-bar {
            background-color: #e5e7eb;
            height: 8px;
            border-radius: 4px;
            overflow: hidden;
        }
        .progress-fill {
            background: linear-gradient(to right, #3b82f6, #8b5cf6);
            height: 100%;
            transition: width 0.5s ease;
        }
        .skill-badge {
            background: linear-gradient(to right, #3b82f6, #8b5cf6);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen py-8">
  <div class="module-header bg-gradient-to-r from-blue-900 to-purple-800 text-white p-8 rounded-b-3xl shadow-xl relative overflow-hidden">
    <div class="absolute inset-0 z-0 opacity-20" style="background-image: url('[Link Gambar Ilustrasi Konflik Sound Horeg/Isu Umum]'); background-size: cover; background-position: center;"></div>
    <div class="relative z-10 max-w-4xl mx-auto text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-3 leading-tight">Sound Horeg: Ekspresi Budaya, Polusi Suara, atau Misteri Fisika?</h1>
        <p class="text-lg md:text-xl font-light opacity-90 mb-6">Setiap acara seru selalu ada Sound Horeg yang bikin suasana pecah! Tapi, pernahkah kamu berpikir, di balik bass yang menggelegar, ada sains yang bekerja, dan isu sosial yang menunggu jawaban? Mari kita selidiki misteri Sound Horeg dan buktikan apakah ia benar-benar 'hiburan' atau 'ancaman'...</p>
        <div class="module-quick-stats grid grid-cols-1 sm:grid-cols-3 gap-4 mt-8">
            <div class="stat-card bg-white/20 backdrop-blur-sm rounded-lg p-3 flex flex-col items-center">
                <i class="fas fa-clock text-yellow-300 text-2xl mb-1"></i>
                <span class="text-sm">Durasi</span>
                <span class="font-bold text-lg">2 Minggu</span>
            </div>
            <div class="stat-card bg-white/20 backdrop-blur-sm rounded-lg p-3 flex flex-col items-center">
                <i class="fas fa-brain text-green-300 text-2xl mb-1"></i>
                <span class="text-sm">Tingkat Kesulitan</span>
                <span class="font-bold text-lg">Menengah</span>
            </div>
            <div class="stat-card bg-white/20 backdrop-blur-sm rounded-lg p-3 flex flex-col items-center">
                <i class="fas fa-flask text-red-300 text-2xl mb-1"></i>
                <span class="text-sm">Fisika</span>
                <span class="font-bold text-lg">Gelombang Suara</span>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto p-6">
    <div class="module-nav-progress mt-8 mb-6 flex justify-center space-x-4">
        <a href="#pengantar" class="module-step active"><i class="fas fa-info-circle mr-2"></i> Pengantar</a>
        <a href="#praktikum" class="module-step"><i class="fas fa-flask mr-2"></i> Praktikum</a>
        <a href="#debat" class="module-step"><i class="fas fa-comments mr-2"></i> Debat</a>
        <!-- dst, dengan indikator progress visual -->
    </div>

    <div id="pengantar" class="module-section bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-3xl font-bold mb-6 text-gray-800"><i class="fas fa-lightbulb text-yellow-500 mr-3"></i> Misi Detektif: Pahami Fenomena Sound Horeg!</h2>
        
        <p class="text-gray-700 mb-4 leading-relaxed">Fenomena "Sound Horeg" di Indonesia telah menjadi bagian tak terpisahkan dari ... (lanjutan deskripsi dari modul)</p>

        <h3 class="text-2xl font-semibold mb-4 text-gray-700"><i class="fas fa-play-circle text-red-500 mr-2"></i> Video Pengantar Isu</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="material-card bg-gray-50 border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                <img src="https://www.youtube.com/watch?v=tA4XJ0ngU9E" alt="Video Sound Horeg" class="w-full h-40 object-cover">
                <div class="p-4">
                    <h4 class="font-bold text-lg mb-1">Detik-detik Sound Horeg Mengguncang: Sebuah Liputan Viral</h4>
                    <p class="text-sm text-gray-600">Lihat langsung kemeriahan (dan kontroversi) dari event Sound Horeg.</p>
                    <a href="https://www.youtube.com/watch?v=tA4XJ0ngU9E" target="_blank" class="btn btn-sm btn-red mt-3"><i class="fas fa-external-link-alt mr-1"></i> Tonton Sekarang</a>
                </div>
            </div>
            <div class="material-card bg-gray-50 border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                <img src="https://www.youtube.com/watch?v=tA4XJ0ngU9E" alt="Video Fisika Suara" class="w-full h-40 object-cover">
                <div class="p-4">
                    <h4 class="font-bold text-lg mb-1">Misteri Desibel & Frekuensi: Sains di Balik Suara</h4>
                    <p class="text-sm text-gray-600">Pahami konsep fisika dasar yang membuat Sound Horeg begitu khas.</p>
                    <a href="https://www.youtube.com/watch?v=tA4XJ0ngU9E" target="_blank" class="btn btn-sm btn-blue mt-3"><i class="fas fa-external-link-alt mr-1"></i> Tonton Sekarang</a>
                </div>
            </div>
        </div>

        <h3 class="text-2xl font-semibold mb-4 text-gray-700"><i class="fas fa-newspaper text-blue-500 mr-2"></i> Artikel Pro & Kontra</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="material-card bg-gray-50 border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                <img src="[Thumbnail Artikel 1]" alt="Artikel Dampak Kebisingan" class="w-full h-32 object-cover">
                <div class="p-4">
                    <h4 class="font-bold text-md mb-1">Dampak Kesehatan Akibat Kebisingan: Apa Kata WHO?</h4>
                    <a href="[Link Artikel 1]" target="_blank" class="btn btn-sm btn-outline-primary mt-2"><i class="fas fa-file-alt mr-1"></i> Baca</a>
                </div>
            </div>
            <!-- Kartu Artikel 2, Artikel 3, dll. -->
        </div>

        <h3 class="text-2xl font-semibold mb-4 text-gray-700"><i class="fas fa-edit text-green-500 mr-2"></i> Catatan Detektif: Refleksi Awal</h3>
        <p class="text-gray-700 mb-4">Misi detektif dimulai! Jawab pertanyaan di bawah untuk memulai penyelidikan Anda.</p>
        <ol class="list-decimal list-inside text-gray-700 mb-4">
            <li>Apa pengalaman Anda pribadi dengan "Sound Horeg"? Bagaimana perasaan Anda saat mendengarnya?</li>
            <li>Menurut Anda, mengapa "Sound Horeg" menjadi populer di beberapa komunitas?</li>
            <li>Di sisi lain, masalah apa saja yang mungkin timbul akibat "Sound Horeg" ini?</li>
            <li>Apa pertanyaan terbesar Anda tentang "Sound Horeg" yang ingin Anda jawab di modul ini?</li>
        </ol>
        <textarea class="w-full p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" rows="8" placeholder="Tuliskan refleksi awal Anda di sini..."></textarea>
        <button class="btn btn-success mt-4"><i class="fas fa-save mr-2"></i> Simpan Refleksi</button>
    </div>

    <!-- ... Bagian selanjutnya dari modul (Praktikum, Debat, dll.) ... -->

</div>


    <div class="container mx-auto px-4 max-w-6xl">
        <!-- Progress Bar Modul (Opsional, untuk konteks keseluruhan modul) -->
        <div class="mb-8 text-center">
            <p class="text-lg font-semibold text-gray-800 mb-2">Progres Modul Sound Horeg</p>
            <div class="progress-bar w-full rounded-full">
                <div class="progress-fill w-40" id="progressFill"></div>
            </div>
            <p class="text-sm text-gray-600 mt-1">2/5 Selesai</p>
        </div>

        <!-- Section Tujuan Pembelajaran -->
        <div class="module-section bg-gray-100 p-8 rounded-lg shadow-lg mb-8">
            <div class="text-center mb-8">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-bullseye text-red-600 mr-3"></i>Misi Pembelajaran Anda!
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Setelah menyelesaikan modul ini, Anda akan memiliki keterampilan detektif sains yang tangguh. Inilah tujuan-tujuan yang akan Anda capai:
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Kartu 1: Menjelaskan Fenomena Ilmiah -->
                <div class="objective-card bg-white p-5 rounded-xl shadow-md border-l-4 border-blue-500 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-start mb-3">
                        <i class="fas fa-lightbulb text-3xl text-blue-500 mr-4"></i>
                        <div>
                            <h3 class="font-bold text-xl text-gray-800">Menjelaskan Fenomena Ilmiah</h3>
                            <p class="text-sm text-gray-600">Anda akan mampu menguraikan karakteristik suara (kebisingan, frekuensi) dari "Sound Horeg" dan menghubungkannya dengan konsep fisika dasar.</p>
                        </div>
                    </div>
                    <span class="skill-badge">Skill: Pengetahuan Konseptual</span>
                    <button class="mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 w-full">
                        <i class="fas fa-check mr-2"></i>Saya Paham
                    </button>
                </div>

                <!-- Kartu 2: Merancang dan Melakukan Penyelidikan Ilmiah -->
                <div class="objective-card bg-white p-5 rounded-xl shadow-md border-l-4 border-green-500 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-start mb-3">
                        <i class="fas fa-flask text-3xl text-green-500 mr-4"></i>
                        <div>
                            <h3 class="font-bold text-xl text-gray-800">Merancang dan Melakukan Penyelidikan Ilmiah</h3>
                            <p class="text-sm text-gray-600">Anda akan mampu merancang dan melaksanakan eksperimen menggunakan phyphox untuk mengumpulkan data suara.</p>
                        </div>
                    </div>
                    <span class="skill-badge">Skill: Proses Ilmiah</span>
                    <button class="mt-2 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 w-full">
                        <i class="fas fa-check mr-2"></i>Saya Paham
                    </button>
                </div>

                <!-- Kartu 3: Menginterpretasi Data dan Bukti Ilmiah -->
                <div class="objective-card bg-white p-5 rounded-xl shadow-md border-l-4 border-purple-500 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-start mb-3">
                        <i class="fas fa-chart-line text-3xl text-purple-500 mr-4"></i>
                        <div>
                            <h3 class="font-bold text-xl text-gray-800">Menginterpretasi Data dan Bukti Ilmiah</h3>
                            <p class="text-sm text-gray-600">Anda akan mampu menganalisis dan menafsirkan visualisasi data phyphox dan menarik kesimpulan berbasis bukti.</p>
                        </div>
                    </div>
                    <span class="skill-badge">Skill: Analisis Data</span>
                    <button class="mt-2 px-4 py-2 bg-purple-500 text-white rounded hover:bg-purple-600 w-full">
                        <i class="fas fa-check mr-2"></i>Saya Paham
                    </button>
                </div>

                <!-- Kartu 4: Menghubungkan Sains dengan Isu Sosial -->
                <div class="objective-card bg-white p-5 rounded-xl shadow-md border-l-4 border-orange-500 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-start mb-3">
                        <i class="fas fa-handshake text-3xl text-orange-500 mr-4"></i>
                        <div>
                            <h3 class="font-bold text-xl text-gray-800">Menghubungkan Sains dengan Isu Sosial</h3>
                            <p class="text-sm text-gray-600">Anda akan mampu mengidentifikasi hubungan antara temuan ilmiah dan implikasi sosial/kesehatan "Sound Horeg".</p>
                        </div>
                    </div>
                    <span class="skill-badge">Skill: Literasi Kontekstual</span>
                    <button class="mt-2 px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600 w-full">
                        <i class="fas fa-check mr-2"></i>Saya Paham
                    </button>
                </div>

                <!-- Kartu 5: Berargumentasi Berbasis Bukti Ilmiah -->
                <div class="objective-card bg-white p-5 rounded-xl shadow-md border-l-4 border-red-500 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-start mb-3">
                        <i class="fas fa-comments text-3xl text-red-500 mr-4"></i>
                        <div>
                            <h3 class="font-bold text-xl text-gray-800">Berargumentasi Berbasis Bukti Ilmiah</h3>
                            <p class="text-sm text-gray-600">Anda akan mampu menyusun argumen yang logis dan persuasif dalam debat SSI, didukung bukti ilmiah.</p>
                        </div>
                    </div>
                    <span class="skill-badge">Skill: Argumentasi Ilmiah</span>
                    <button class="mt-2 px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 w-full">
                        <i class="fas fa-check mr-2"></i>Saya Paham
                    </button>
                </div>

                <!-- Kartu 6: Memahami Perspektif Multidisiplin -->
                <div class="objective-card bg-white p-5 rounded-xl shadow-md border-l-4 border-yellow-500 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-start mb-3">
                        <i class="fas fa-globe text-3xl text-yellow-500 mr-4"></i>
                        <div>
                            <h3 class="font-bold text-xl text-gray-800">Memahami Perspektif Multidisiplin</h3>
                            <p class="text-sm text-gray-600">Anda akan mampu mengevaluasi berbagai pandangan (ilmiah, etika, sosial) terkait "Sound Horeg" dan mengusulkan solusi.</p>
                        </div>
                    </div>
                    <span class="skill-badge">Skill: Pembuat Keputusan Reflektif</span>
                    <button class="mt-2 px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 w-full">
                        <i class="fas fa-check mr-2"></i>Saya Paham
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // JavaScript untuk Interaktivitas
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.objective-card button');
            const progressFill = document.getElementById('progressFill');
            let completedCount = 0; // Hitung kartu yang sudah "paham"

            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    const card = this.closest('.objective-card');
                    const badge = card.querySelector('.skill-badge');
                    const icon = card.querySelector('i');
                    
                    // Toggle status "paham"
                    if (card.classList.contains('completed')) {
                        card.classList.remove('completed');
                        badge.textContent = 'Skill: Pengetahuan Konseptual'; // Ganti sesuai kartu
                        icon.classList.remove('fas', 'fa-check-circle');
                        icon.classList.add('fas', 'fa-question-circle');
                        completedCount--;
                    } else {
                        card.classList.add('completed');
                        badge.textContent = 'Skill: Selesai!';
                        icon.classList.remove('fas', 'fa-question-circle');
                        icon.classList.add('fas', 'fa-check-circle');
                        completedCount++;
                    }
                    
                    // Update progress bar
                    const progressPercent = (completedCount / 6) * 100; // 6 kartu total
                    progressFill.style.width = progressPercent + '%';
                    
                    // Feedback
                    this.textContent = card.classList.contains('completed') ? 'Saya Paham' : 'Selesai!';
                });
            });
        });
    </script>
</body>
</html>
