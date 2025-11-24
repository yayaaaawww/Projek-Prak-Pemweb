<?php
session_start();
include "../config/connection.php";

// CEK LOGIN
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// CEK PAKET USER BERDASARKAN TABEL PEMBAYARAN
$q = mysqli_query($conn, "
    SELECT id_paket 
    FROM pembayaran 
    WHERE id_user = '$user_id'
    ORDER BY id_bayar DESC
    LIMIT 1
");

if (!$q) {
    die("Query Error: " . mysqli_error($conn));
}

$d = mysqli_fetch_assoc($q);

// JIKA USER BELUM PERNAH BELI PAKET
if (!$d) {
    echo "<script>alert('Kamu belum membeli paket apa pun!'); window.location='../landingpage.php';</script>";
    exit();
}

// HANYA UNTUK PAKET 3 (AI & ML Expert)
if ($d['id_paket'] != 3) {
    echo "<script>alert('Akses ditolak! Paket kamu bukan Paket AI & Machine Learning Expert.'); window.location='../landingpage.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intro to Machine Learning - Paket 3 | codeBloom</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #fafafa;
            min-height: 100vh;
            padding-bottom: 50px;
        }

        .navbar {
            padding: 20px 40px;
            background: white;
            font-weight: 600;
            font-size: 24px;
            border-bottom: 1px solid #efefef;
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar .logo {
            color: #e91e63;
        }

        .back-btn {
            padding: 8px 20px;
            background: white;
            border: 1.5px solid #e0e0e0;
            border-radius: 20px;
            color: #666;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: #f5f5f5;
            border-color: #e91e63;
            color: #e91e63;
        }

        .container {
            max-width: 900px;
            margin: auto;
            padding: 60px 30px;
        }

        .class-navigation {
            display: flex;
            gap: 15px;
            margin-bottom: 40px;
            padding: 20px;
            background: white;
            border-radius: 12px;
            border: 1px solid #efefef;
            overflow-x: auto;
        }

        .class-nav-item {
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            white-space: nowrap;
            transition: all 0.3s ease;
            border: 1.5px solid transparent;
        }

        .class-nav-item.active {
            background: #7b1fa2;
            color: white;
        }

        .class-nav-item:not(.active) {
            background: #f5f5f5;
            color: #616161;
            border-color: #e0e0e0;
        }

        .class-nav-item:not(.active):hover {
            background: #f3e5f5;
            color: #7b1fa2;
            border-color: #7b1fa2;
        }

        .header-section {
            margin-bottom: 60px;
        }

        .badge {
            display: inline-block;
            background: #f3e5f5;
            color: #6a1b9a;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 42px;
            margin-bottom: 20px;
            font-weight: 700;
            color: #212121;
            letter-spacing: -0.5px;
        }

        .intro-text {
            color: #616161;
            line-height: 1.8;
            font-size: 16px;
            font-weight: 400;
            max-width: 700px;
        }

        .hero-image {
            width: 100%;
            height: 350px;
            background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);
            border-radius: 12px;
            margin: 40px 0 60px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 80px;
            position: relative;
            overflow: hidden;
        }

        .hero-image::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.3) 1px, transparent 1px);
            background-size: 30px 30px;
            animation: float 20s linear infinite;
        }

        @keyframes float {
            0% { transform: translate(0, 0); }
            100% { transform: translate(30px, 30px); }
        }

        .section-title {
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 40px 0;
            color: #212121;
            letter-spacing: -0.3px;
        }

        .materi-list {
            display: flex;
            flex-direction: column;
            gap: 50px;
        }

        .materi-item {
            padding-bottom: 30px;
            border-bottom: 1px solid #e0e0e0;
        }

        .materi-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .materi-header {
            display: grid;
            grid-template-columns: 60px 1fr auto;
            gap: 25px;
            align-items: center;
            cursor: pointer;
            padding: 20px 0;
            transition: all 0.3s ease;
        }

        .materi-header:hover {
            opacity: 0.7;
        }

        .materi-number {
            width: 60px;
            height: 60px;
            background: #f3e5f5;
            color: #7b1fa2;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 24px;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .materi-item.active .materi-number {
            background: #7b1fa2;
            color: white;
        }

        .materi-title-wrapper {
            padding-top: 5px;
        }

        .materi-title {
            font-weight: 700;
            font-size: 22px;
            color: #212121;
            margin-bottom: 8px;
        }

        .materi-subtitle {
            color: #9e9e9e;
            font-size: 14px;
            font-weight: 400;
        }

        .toggle-icon {
            width: 40px;
            height: 40px;
            background: #f5f5f5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #757575;
            font-size: 20px;
            transition: all 0.3s ease;
        }

        .materi-item.active .toggle-icon {
            transform: rotate(180deg);
            background: #f3e5f5;
            color: #7b1fa2;
        }

        .materi-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease;
            padding-left: 85px;
        }

        .materi-item.active .materi-content {
            max-height: 2000px;
            padding-top: 20px;
            padding-bottom: 20px;
        }

        .materi-intro {
            color: #616161;
            line-height: 1.7;
            margin-bottom: 25px;
            font-size: 15px;
        }

        .content-section {
            margin-bottom: 25px;
        }

        .content-section h4 {
            font-size: 16px;
            font-weight: 600;
            color: #212121;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .content-section ul {
            list-style: none;
            padding: 0;
        }

        .content-section li {
            padding-left: 20px;
            position: relative;
            color: #616161;
            line-height: 1.7;
            margin-bottom: 8px;
            font-size: 15px;
        }

        .content-section li:before {
            content: '•';
            position: absolute;
            left: 0;
            color: #7b1fa2;
            font-weight: 700;
        }

        .video-link {
            display: inline-flex;
            align-items: center;
            background: #7b1fa2;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            transition: all 0.3s ease;
            gap: 8px;
            margin-top: 15px;
        }

        .video-link:hover {
            background: #6a1b9a;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(123, 31, 162, 0.3);
        }

        .video-link::before {
            content: '▶';
            font-size: 12px;
        }

        .tips-box {
            background: #fff3e0;
            padding: 15px 20px;
            border-radius: 8px;
            border-left: 4px solid #ff9800;
            margin-top: 20px;
        }

        .tips-box strong {
            color: #e65100;
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .tips-box p {
            color: #616161;
            line-height: 1.6;
            font-size: 14px;
            margin: 0;
        }

        .footer-note {
            margin-top: 60px;
            padding: 30px;
            background: #f5f5f5;
            border-radius: 12px;
            border-left: 4px solid #7b1fa2;
        }

        .footer-note h3 {
            font-size: 18px;
            color: #212121;
            margin-bottom: 10px;
        }

        .footer-note p {
            color: #616161;
            line-height: 1.7;
            font-size: 15px;
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 15px 20px;
                font-size: 20px;
            }

            h1 {
                font-size: 32px;
            }

            .hero-image {
                height: 250px;
                font-size: 60px;
            }

            .section-title {
                font-size: 24px;
            }

            .materi-item {
                padding-bottom: 20px;
            }

            .materi-header {
                grid-template-columns: 50px 1fr auto;
                gap: 15px;
            }

            .materi-number {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }

            .materi-title {
                font-size: 18px;
            }

            .materi-content {
                padding-left: 0;
                padding-right: 0;
            }

            .content-section h4 {
                font-size: 15px;
            }

            .content-section li {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>

    <div class="navbar">
        <span class="logo">c🌸deBloom</span>
        <a href="../landingpage.php" class="back-btn">← Dashboard</a>
    </div>

    <div class="container">

        <div class="class-navigation">
            <a href="introml.php" class="class-nav-item active">
                🤖 Kelas 1: Intro to ML
            </a>
            <a href="deeplearning.php" class="class-nav-item">
                🧬 Kelas 2: Deep Learning
            </a>
            <a href="aiproject.php" class="class-nav-item">
                🚀 Kelas 3: AI Projects & Deployment
            </a>
        </div>

        <div class="header-section">
            <span class="badge">Paket 3 - Kelas 1</span>
            <h1>Intro to Machine Learning</h1>
            <p class="intro-text">
                Di kelas ini, kamu akan mempelajari fundamental Machine Learning dari nol! Mulai dari konsep dasar, 
                persiapan data, hingga membuat model prediksi pertamamu. Welcome to the world of AI! 🤖
            </p>
        </div>

        <div class="hero-image">
            🤖
        </div>

        <h2 class="section-title">Materi Pembelajaran</h2>

        <div class="materi-list">
            
            <!-- MATERI 1 -->
            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">1</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">Pengenalan Machine Learning & Jenisnya</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        Machine Learning adalah cara membuat komputer belajar dari data tanpa diprogram secara eksplisit. 
                        Kamu akan memahami perbedaan Supervised vs Unsupervised Learning dan kapan menggunakan masing-masing.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Pelajari:</h4>
                        <ul>
                            <li>Apa itu Machine Learning dan kenapa penting?</li>
                            <li>Supervised Learning: belajar dari data berlabel</li>
                            <li>Unsupervised Learning: menemukan pola tersembunyi</li>
                            <li>Reinforcement Learning: belajar dari reward</li>
                            <li>Perbedaan AI, Machine Learning, dan Deep Learning</li>
                            <li>Use cases ML di dunia nyata</li>
                            <li>Workflow ML: dari problem hingga deployment</li>
                            <li>Tools & libraries yang digunakan (Scikit-learn, TensorFlow)</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Setelah Selesai, Kamu Bisa:</h4>
                        <ul>
                            <li>Menjelaskan konsep Machine Learning dengan jelas</li>
                            <li>Membedakan jenis-jenis Machine Learning</li>
                            <li>Memilih algoritma yang tepat untuk problem tertentu</li>
                            <li>Memahami end-to-end ML workflow</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/watch?v=7eh4d6sabA0" target="_blank" class="video-link">
                        Apa Itu Machine Learning? - Indonesia Belajar
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>Jangan terburu-buru ke coding! Pahami dulu konsep fundamentalnya. ML bukan magic, tapi matematika dan logika yang powerful!</p>
                    </div>
                </div>
            </div>

            <!-- MATERI 2 -->
            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">2</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">Persiapan Data & Feature Engineering</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        "Garbage In, Garbage Out" - kualitas model ML sangat bergantung pada kualitas data! 
                        Di sini kamu akan belajar cara menyiapkan data agar model bisa belajar dengan optimal.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Pelajari:</h4>
                        <ul>
                            <li>Data cleaning: handling missing values, outliers, duplicates</li>
                            <li>Data transformation: normalization & standardization</li>
                            <li>Encoding categorical variables: Label Encoding, One-Hot Encoding</li>
                            <li>Feature scaling dan kenapa penting</li>
                            <li>Feature selection: memilih fitur yang relevan</li>
                            <li>Feature extraction: membuat fitur baru dari yang ada</li>
                            <li>Handling imbalanced data</li>
                            <li>Train-test split dan validation set</li>
                            <li>Cross-validation untuk evaluasi robust</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Setelah Selesai, Kamu Bisa:</h4>
                        <ul>
                            <li>Membersihkan dan memproses data mentah</li>
                            <li>Melakukan encoding untuk data kategorikal</li>
                            <li>Melakukan feature engineering yang efektif</li>
                            <li>Mempersiapkan data untuk training model ML</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/watch?v=0GrciaGYzV0" target="_blank" class="video-link">
                        Feature Engineering Tutorial - Krish Naik
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>80% waktu ML Engineer dihabiskan untuk data preparation! Kuasai skill ini karena data yang bersih = model yang akurat.</p>
                    </div>
                </div>
            </div>

            <!-- MATERI 3 -->
            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">3</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">Model Dasar: Regresi & Klasifikasi</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        Saatnya membuat model prediksi pertamamu! Kamu akan belajar algoritma fundamental ML 
                        yang menjadi fondasi untuk algoritma advanced lainnya.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Pelajari:</h4>
                        <ul>
                            <li><strong>Linear Regression:</strong> prediksi nilai kontinyu</li>
                            <li><strong>Logistic Regression:</strong> klasifikasi binary</li>
                            <li><strong>Decision Tree:</strong> model berbasis pohon keputusan</li>
                            <li><strong>Random Forest:</strong> ensemble dari multiple trees</li>
                            <li><strong>K-Nearest Neighbors (KNN):</strong> klasifikasi based on similarity</li>
                            <li><strong>Support Vector Machine (SVM):</strong> finding optimal boundary</li>
                            <li>Cara kerja masing-masing algoritma</li>
                            <li>Kapan menggunakan algoritma tertentu</li>
                            <li>Hyperparameter tuning basics</li>
                            <li>Implementasi dengan Scikit-learn</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Setelah Selesai, Kamu Bisa:</h4>
                        <ul>
                            <li>Membuat model regresi untuk prediksi numerik</li>
                            <li>Membuat model klasifikasi untuk kategorisasi</li>
                            <li>Memilih algoritma yang tepat untuk problem</li>
                            <li>Mengimplementasikan model dengan Python & Scikit-learn</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/watch?v=i_LwzRVP7bg" target="_blank" class="video-link">
                        Scikit-learn Crash Course - Tech With Tim
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>Jangan hanya copy-paste code! Pahami intuisi di balik setiap algoritma. Coba tweak parameter dan lihat efeknya!</p>
                    </div>
                </div>
            </div>

            <!-- MATERI 4 -->
            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">4</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">Evaluasi Model</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        Model sudah dibuat, tapi bagaimana tahu performanya bagus atau tidak? 
                        Di sini kamu akan belajar berbagai metrik untuk mengevaluasi model ML-mu.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Pelajari:</h4>
                        <ul>
                            <li><strong>Regression Metrics:</strong> MAE, MSE, RMSE, R-squared</li>
                            <li><strong>Classification Metrics:</strong> Accuracy, Precision, Recall, F1-Score</li>
                            <li><strong>Confusion Matrix:</strong> visualisasi performa klasifikasi</li>
                            <li><strong>ROC Curve & AUC:</strong> evaluasi probabilistic predictions</li>
                            <li>Overfitting vs Underfitting: masalah umum ML</li>
                            <li>Bias-Variance tradeoff</li>
                            <li>Cross-validation untuk evaluasi robust</li>
                            <li>Learning curves untuk diagnosis</li>
                            <li>Kapan menggunakan metrik tertentu</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Setelah Selesai, Kamu Bisa:</h4>
                        <ul>
                            <li>Mengevaluasi performa model dengan metrik yang tepat</li>
                            <li>Mengidentifikasi overfitting dan underfitting</li>
                            <li>Membaca confusion matrix dan ROC curve</li>
                            <li>Melakukan improvement pada model berdasarkan evaluasi</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/watch?v=wpQiEHYkBys" target="_blank" class="video-link">
                        Model Evaluation Metrics - StatQuest
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>Accuracy bukan segalanya! Untuk imbalanced data, lihat precision, recall, dan F1-score. Pilih metrik sesuai business objective!</p>
                    </div>
                </div>
            </div>

            <!-- MATERI 5 -->
            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">5</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">Mini Project: Prediksi Harga Rumah</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        Waktunya praktik! Kamu akan membuat model ML untuk memprediksi harga rumah berdasarkan 
                        berbagai fitur seperti ukuran, lokasi, jumlah kamar, dll. Real-world ML project!
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Kerjakan:</h4>
                        <ul>
                            <li><strong>Data Loading:</strong> import dataset harga rumah</li>
                            <li><strong>EDA:</strong> exploratory data analysis untuk understand data</li>
                            <li><strong>Data Cleaning:</strong> handle missing values dan outliers</li>
                            <li><strong>Feature Engineering:</strong> create new features, encoding</li>
                            <li><strong>Feature Selection:</strong> pilih fitur yang paling relevant</li>
                            <li><strong>Model Training:</strong> train multiple regression models</li>
                            <li><strong>Model Comparison:</strong> bandingkan performa berbagai model</li>
                            <li><strong>Hyperparameter Tuning:</strong> optimize model terbaik</li>
                            <li><strong>Final Evaluation:</strong> test model di unseen data</li>
                            <li><strong>Insights:</strong> fitur apa yang paling pengaruhi harga?</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Output Project:</h4>
                        <ul>
                            <li>Model ML yang bisa prediksi harga rumah dengan akurat</li>
                            <li>Jupyter notebook lengkap dengan analisis</li>
                            <li>Visualisasi hasil prediksi vs aktual</li>
                            <li>Report tentang fitur-fitur penting</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🛠️ Tools yang Digunakan:</h4>
                        <ul>
                            <li>Python & Jupyter Notebook</li>
                            <li>Pandas untuk data manipulation</li>
                            <li>Matplotlib & Seaborn untuk visualization</li>
                            <li>Scikit-learn untuk ML modeling</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/watch?v=Wqmtf9SA_kk" target="_blank" class="video-link">
                        House Price Prediction Project - Codebasics
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>Ini bukan tentang accuracy tertinggi, tapi tentang process! Dokumentasikan setiap step, decision yang kamu buat, dan kenapa. Think like a Data Scientist!</p>
                    </div>
                </div>
            </div>

        </div>

        <div class="footer-note">
            <h3>🎉 Congratulations!</h3>
              <p>
                Jangan hanya menonton video, tapi praktikkan langsung setiap materi! Coding adalah skill yang 
                hanya bisa diasah dengan banyak latihan. Jangan takut untuk bereksperimen dan membuat error, 
                karena dari sanalah kamu akan belajar paling banyak. Semangat! 🌸
            </p>
        </div>

    </div>

    <script>
        function toggleMateri(header) {
            const materiItem = header.parentElement;
            const isActive = materiItem.classList.contains('active');
            
            // Close all other items
            document.querySelectorAll('.materi-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Toggle current item
            if (!isActive) {
                materiItem.classList.add('active');
            }
        }
    </script>

</body>

</html>