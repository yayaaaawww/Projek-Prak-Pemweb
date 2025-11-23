<?php
session_start();
include "./config/koneksi.php";

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
    echo "<script>alert('Kamu belum membeli paket apa pun!'); window.location='dashboard.php';</script>";
    exit();
}

// HANYA UNTUK PAKET 2 (Data Analyst)
if ($d['id_paket'] != 2) {
    echo "<script>alert('Akses ditolak! Paket kamu bukan Paket Data Analyst.'); window.location='dashboard.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik & SQL for Data - Paket 2 | codeBloom</title>
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
            background: #7c4dff;
            color: white;
        }

        .class-nav-item:not(.active) {
            background: #f5f5f5;
            color: #616161;
            border-color: #e0e0e0;
        }

        .class-nav-item:not(.active):hover {
            background: #ede7f6;
            color: #7c4dff;
            border-color: #7c4dff;
        }

        .header-section {
            margin-bottom: 60px;
        }

        .badge {
            display: inline-block;
            background: #ede7f6;
            color: #5e35b1;
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
            background: linear-gradient(135deg, #ede7f6 0%, #d1c4e9 100%);
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
            background: #ede7f6;
            color: #7c4dff;
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
            background: #7c4dff;
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
            background: #ede7f6;
            color: #7c4dff;
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
            color: #7c4dff;
            font-weight: 700;
        }

        .video-link {
            display: inline-flex;
            align-items: center;
            background: #7c4dff;
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
            background: #6a3de8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(124, 77, 255, 0.3);
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
            border-left: 4px solid #7c4dff;
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
        <a href="dashboard.php" class="back-btn">← Dashboard</a>
    </div>

    <div class="container">

        <div class="class-navigation">
            <a href="datahandling.php" class="class-nav-item">
                🐍 Kelas 1: Python Dasar
            </a>
            <a href="statistik.php" class="class-nav-item active">
                🧮 Kelas 2: Statistik & SQL for Data
            </a>
            <a href="dataproject.php" class="class-nav-item">
                🔍 Kelas 3: Data Project & Dashboard
            </a>
        </div>

        <div class="header-section">
            <span class="badge">Paket 2 - Kelas 2</span>
            <h1>Statistik & SQL for Data</h1>
            <p class="intro-text">
                Di kelas ini, kamu akan mempelajari fondasi penting untuk menjadi Data Analyst: statistik untuk 
                memahami data secara kuantitatif, dan SQL untuk mengambil data dari database. Kombinasi ini adalah 
                kunci untuk analisis data yang powerful!
            </p>
        </div>

        <div class="hero-image">
            🧮
        </div>

        <h2 class="section-title">Materi Pembelajaran</h2>

        <div class="materi-list">
            
            <!-- MATERI 1 -->
            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">1</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">Statistik Deskriptif & Inferensial Dasar</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        Statistik adalah bahasa data. Kamu akan belajar konsep-konsep statistik dasar yang akan 
                        membantumu memahami pola, tren, dan insight dari data yang kamu analisis.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Pelajari:</h4>
                        <ul>
                            <li>Statistik deskriptif: mean, median, modus</li>
                            <li>Ukuran penyebaran: range, variance, standar deviasi</li>
                            <li>Distribusi data dan histogram</li>
                            <li>Percentile dan quartile</li>
                            <li>Korelasi dan hubungan antar variabel</li>
                            <li>Regresi linear sederhana</li>
                            <li>Probabilitas dasar</li>
                            <li>Normal distribution</li>
                            <li>Hypothesis testing dasar</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Setelah Selesai, Kamu Bisa:</h4>
                        <ul>
                            <li>Menghitung dan menginterpretasi statistik deskriptif</li>
                            <li>Memahami distribusi data</li>
                            <li>Mengidentifikasi korelasi antar variabel</li>
                            <li>Melakukan analisis regresi sederhana</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/playlist?list=PLjbMhCDe7RhPwHLYa3D5Bt_MdWXaZIJwI" target="_blank" class="video-link">
                        Belajar Statistik Dasar - Indonesia Belajar
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>Jangan hanya hapal rumus! Pahami konsepnya dan kapan menggunakan metrik yang tepat. Praktik dengan dataset real!</p>
                    </div>
                </div>
            </div>

            <!-- MATERI 2 -->
            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">2</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">Dasar SQL untuk Data Analyst</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        SQL adalah skill wajib buat Data Analyst! Hampir semua data perusahaan disimpan di database, 
                        dan SQL adalah cara kamu untuk mengambil, mengolah, dan menganalisis data tersebut.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Pelajari:</h4>
                        <ul>
                            <li>SELECT statement untuk query data</li>
                            <li>WHERE clause untuk filtering</li>
                            <li>ORDER BY untuk sorting data</li>
                            <li>LIMIT untuk membatasi hasil</li>
                            <li>Aggregate functions: COUNT, SUM, AVG, MAX, MIN</li>
                            <li>GROUP BY untuk analisis per kategori</li>
                            <li>HAVING untuk filter hasil agregasi</li>
                            <li>JOIN (INNER, LEFT, RIGHT, FULL) untuk gabung tabel</li>
                            <li>Subquery dan nested queries</li>
                            <li>CASE WHEN untuk conditional logic</li>
                            <li>Date functions untuk analisis temporal</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Setelah Selesai, Kamu Bisa:</h4>
                        <ul>
                            <li>Menulis query SQL untuk mengambil data</li>
                            <li>Melakukan agregasi dan perhitungan di SQL</li>
                            <li>Menggabungkan data dari multiple tables</li>
                            <li>Menjawab business questions dengan SQL</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/playlist?list=PLCZlgfAG0GXCe0r9emirDlfNusBPV5Nxe" target="_blank" class="video-link">
                        Belajar SQL untuk Pemula - Sekolah Koding
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>Install MySQL atau PostgreSQL di laptop kamu. Latihan dengan dataset sample seperti Northwind atau Adventure Works!</p>
                    </div>
                </div>
            </div>

            <!-- MATERI 3 -->
            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">3</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">Data Exploration dengan Python</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        Exploratory Data Analysis (EDA) adalah proses investigasi awal untuk memahami karakteristik 
                        data, menemukan pola, anomali, dan memvalidasi asumsi sebelum analisis lebih lanjut.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Pelajari:</h4>
                        <ul>
                            <li>Load data dengan Pandas (CSV, Excel, SQL)</li>
                            <li>Inspeksi data: head(), info(), describe()</li>
                            <li>Handling missing values dan outliers</li>
                            <li>Data cleaning dan preprocessing</li>
                            <li>Analisis univariate (satu variabel)</li>
                            <li>Analisis bivariate (dua variabel)</li>
                            <li>Analisis multivariate</li>
                            <li>Visualisasi distribusi data</li>
                            <li>Correlation analysis</li>
                            <li>Feature engineering basics</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Setelah Selesai, Kamu Bisa:</h4>
                        <ul>
                            <li>Melakukan exploratory data analysis lengkap</li>
                            <li>Mengidentifikasi data quality issues</li>
                            <li>Menemukan pattern dan insight dari data</li>
                            <li>Membuat statistical summary yang bermakna</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/watch?v=xi0vhXFPegw" target="_blank" class="video-link">
                        Exploratory Data Analysis - Krish Naik
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>EDA adalah skill investigasi! Jangan buru-buru ke modeling. Pahami data-mu dulu dengan baik melalui eksplorasi mendalam.</p>
                    </div>
                </div>
            </div>

            <!-- MATERI 4 -->
            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">4</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">Mini Project: Analisis Data Karyawan & Gaji</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        Project hands-on untuk menerapkan SQL dan statistik dalam analisis HR. Kamu akan menjawab 
                        berbagai business questions menggunakan query SQL dan analisis statistik.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Analisis:</h4>
                        <ul>
                            <li><strong>Salary Analysis:</strong> Distribusi gaji per departemen dan posisi</li>
                            <li><strong>Employee Demographics:</strong> Analisis umur, gender, masa kerja</li>
                            <li><strong>Performance Metrics:</strong> Hubungan performance dengan kompensasi</li>
                            <li><strong>Turnover Analysis:</strong> Faktor-faktor yang mempengaruhi retention</li>
                            <li><strong>Promotion Patterns:</strong> Tren promosi karyawan</li>
                            <li><strong>Department Insights:</strong> Perbandingan antar departemen</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Business Questions yang Dijawab:</h4>
                        <ul>
                            <li>Berapa rata-rata gaji per departemen?</li>
                            <li>Departemen mana yang punya salary variance tertinggi?</li>
                            <li>Apakah ada gender pay gap?</li>
                            <li>Bagaimana korelasi antara masa kerja dan gaji?</li>
                            <li>Posisi apa yang paling banyak keluar masuk karyawan?</li>
                            <li>Kapan waktu terbaik untuk promosi?</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🛠️ Tools & Techniques:</h4>
                        <ul>
                            <li>SQL untuk data extraction dan aggregation</li>
                            <li>Pandas untuk data manipulation</li>
                            <li>Statistik deskriptif untuk insights</li>
                            <li>Visualization untuk komunikasi hasil</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/watch?v=7mz73uXD9DA" target="_blank" class="video-link">
                        SQL Data Analysis Project - Luke Barousse
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>Fokus pada business impact! Setiap analisis harus bisa menjawab pertanyaan bisnis yang konkret. Think like an analyst!</p>
                    </div>
                </div>
            </div>

        </div>

        <div class="footer-note">
            <h3>💡 Great Progress!</h3>
            <p>
                Dengan menguasai statistik dan SQL, kamu sudah punya fondasi kuat untuk analisis data! 
                Sekarang waktunya untuk belajar visualisasi dan dashboard agar insight-mu bisa dikomunikasikan 
                dengan efektif ke stakeholder. Keep pushing! 📊
            </p>
            <p style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #e0e0e0;">
                <strong>Setelah selesai Kelas 2, lanjut ke:</strong><br>
                <a href="data_dashboard.php" style="color: #7c4dff; text-decoration: none; font-weight: 600;">
                    🔍 Kelas 3: Data Project & Dashboard →
                </a>
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