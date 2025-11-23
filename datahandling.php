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
    <title>Data Handling & Python Basics - Paket 2 | codeBloom</title>
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
            background: #ff6b6b;
            color: white;
        }

        .class-nav-item:not(.active) {
            background: #f5f5f5;
            color: #616161;
            border-color: #e0e0e0;
        }

        .class-nav-item:not(.active):hover {
            background: #ffe0e0;
            color: #ff6b6b;
            border-color: #ff6b6b;
        }

        .header-section {
            margin-bottom: 60px;
        }

        .badge {
            display: inline-block;
            background: #ffe0e0;
            color: #d32f2f;
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
            background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
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
            background: #ffe0e0;
            color: #ff6b6b;
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
            background: #ff6b6b;
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
            background: #ffe0e0;
            color: #ff6b6b;
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
            color: #ff6b6b;
            font-weight: 700;
        }

        .video-link {
            display: inline-flex;
            align-items: center;
            background: #ff6b6b;
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
            background: #ee5a52;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 107, 107, 0.3);
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
            border-left: 4px solid #ff6b6b;
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
            <a href="python_dasar.php" class="class-nav-item active">
                🐍 Kelas 1: Python Dasar
            </a>
            <a href="statistik.php" class="class-nav-item">
                🧮 Kelas 2: Statistik & SQL for Data
            </a>
            <a href="dataproject.php" class="class-nav-item">
                🔍 Kelas 3: Data Project & Dashboard
            </a>
        </div>

        <div class="header-section">
            <span class="badge">Paket 2 - Kelas 1</span>
            <h1>Data Handling & Python Basics</h1>
            <p class="intro-text">
                Selamat datang di dunia Data Science! Di kelas ini, kamu akan mempelajari fondasi Python untuk 
                analisis data. Python adalah bahasa pemrograman paling populer untuk Data Science karena mudah 
                dipelajari dan punya library yang powerful untuk mengolah data.
            </p>
        </div>

        <div class="hero-image">
            📈
        </div>

        <h2 class="section-title">Materi Pembelajaran</h2>

        <div class="materi-list">
            
            <!-- MATERI 1 -->
            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">1</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">Pengenalan Data Science</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        Data Science adalah salah satu karir paling promising di era digital! Kamu akan memahami 
                        apa itu data science, apa yang dilakukan data scientist, dan bagaimana workflow analisis 
                        data dari awal hingga menghasilkan insight yang valuable.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Pelajari:</h4>
                        <ul>
                            <li>Apa itu Data Science dan kenapa penting</li>
                            <li>Perbedaan Data Analyst vs Data Scientist vs Data Engineer</li>
                            <li>Workflow data science: dari problem definition hingga deployment</li>
                            <li>Tools dan teknologi yang digunakan dalam data science</li>
                            <li>Industri dan use cases data science</li>
                            <li>Skills yang dibutuhkan untuk menjadi data professional</li>
                            <li>Career path dan opportunities di bidang data</li>
                            <li>Ethics dalam data science</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Setelah Selesai, Kamu Bisa:</h4>
                        <ul>
                            <li>Menjelaskan apa itu data science dengan percaya diri</li>
                            <li>Memahami peran dan tanggung jawab data professional</li>
                            <li>Mengerti workflow end-to-end data analysis</li>
                            <li>Menentukan path karir yang sesuai di bidang data</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/watch?v=ua-CiDNNj30" target="_blank" class="video-link">
                        Pengenalan Data Science - Indonesia Belajar
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>Jangan buru-buru! Pahami konsep big picture dulu sebelum deep dive ke technical skills. Understanding "why" is as important as "how"!</p>
                    </div>
                </div>
            </div>

            <!-- MATERI 2 -->
            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">2</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">Dasar Python untuk Analisis Data</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        Python adalah bahasa pemrograman yang wajib dikuasai untuk data science! Syntax-nya mudah 
                        dibaca seperti bahasa Inggris, dan punya library super lengkap untuk analisis data. 
                        Di sini kamu akan belajar fundamental Python dari nol.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Pelajari:</h4>
                        <ul>
                            <li>Install Python dan setup environment (Anaconda/Jupyter)</li>
                            <li>Variabel dan tipe data (int, float, string, boolean)</li>
                            <li>Operators: arithmetic, comparison, logical</li>
                            <li>String manipulation dan formatting</li>
                            <li>Data structures: Lists, Tuples, Dictionaries, Sets</li>
                            <li>Control flow: if-else, loops (for, while)</li>
                            <li>Functions dan parameters</li>
                            <li>List comprehension untuk efisiensi</li>
                            <li>File handling: read/write files</li>
                            <li>Error handling dengan try-except</li>
                            <li>Import modules dan libraries</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Setelah Selesai, Kamu Bisa:</h4>
                        <ul>
                            <li>Menulis program Python dasar dengan confidence</li>
                            <li>Manipulasi data menggunakan Python data structures</li>
                            <li>Membuat functions untuk code reusability</li>
                            <li>Read dan write files untuk data processing</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/playlist?list=PLZS-MHyEIRo59lUBwU-XHH7Ymmb04ffOY" target="_blank" class="video-link">
                        Python Dasar untuk Pemula - Programmer Zaman Now
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>Practice, practice, practice! Jangan cuma nonton tutorial. Code along dan coba modifikasi contoh-contohnya. Install Jupyter Notebook untuk coding!</p>
                    </div>
                </div>
            </div>

            <!-- MATERI 3 -->
            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">3</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">Manipulasi Data dengan Pandas</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        Pandas adalah library Python paling powerful untuk data manipulation! Dengan Pandas, 
                        kamu bisa load, clean, transform, dan analyze data dengan mudah. Ini adalah tool wajib 
                        untuk setiap Data Analyst dan Data Scientist.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Pelajari:</h4>
                        <ul>
                            <li>Series dan DataFrame: struktur data utama Pandas</li>
                            <li>Load data dari berbagai format (CSV, Excel, JSON)</li>
                            <li>Inspeksi data: head(), tail(), info(), describe()</li>
                            <li>Selecting dan indexing: loc, iloc, boolean indexing</li>
                            <li>Filtering data dengan conditional statements</li>
                            <li>Sorting: sort_values(), sort_index()</li>
                            <li>Handling missing data: dropna(), fillna()</li>
                            <li>Data aggregation: groupby() dan aggregate functions</li>
                            <li>Merging dan joining DataFrames</li>
                            <li>Pivot tables untuk data summarization</li>
                            <li>Apply custom functions</li>
                            <li>Export data ke berbagai format</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Setelah Selesai, Kamu Bisa:</h4>
                        <ul>
                            <li>Load dan explore dataset dengan Pandas</li>
                            <li>Clean dan transform data untuk analysis</li>
                            <li>Perform complex data manipulations</li>
                            <li>Aggregate dan summarize data effectively</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/playlist?list=PLjbMhCDe7RhN_cCnHo_KMRAb7g3XrLWES" target="_blank" class="video-link">
                        Belajar Pandas untuk Pemula - Indonesia Belajar
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>Download sample datasets dari Kaggle atau UCI Machine Learning Repository. Practice dengan real data untuk better understanding!</p>
                    </div>
                </div>
            </div>

            <!-- MATERI 4 -->
            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">4</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">Numerical Computation dengan NumPy</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        NumPy adalah fundamental package untuk scientific computing dengan Python. Library ini 
                        sangat efisien untuk operasi matematis pada array dan matrix, dan menjadi fondasi untuk 
                        library data science lainnya seperti Pandas, Matplotlib, dan scikit-learn.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Pelajari:</h4>
                        <ul>
                            <li>NumPy arrays vs Python lists: performa dan efisiensi</li>
                            <li>Creating arrays: zeros, ones, arange, linspace</li>
                            <li>Array properties: shape, size, dtype</li>
                            <li>Array indexing dan slicing</li>
                            <li>Reshape dan transpose arrays</li>
                            <li>Mathematical operations: add, subtract, multiply, divide</li>
                            <li>Universal functions (ufuncs)</li>
                            <li>Statistical operations: mean, median, std, variance</li>
                            <li>Array aggregations: sum, min, max</li>
                            <li>Broadcasting untuk efficient computations</li>
                            <li>Boolean masking dan filtering</li>
                            <li>Random number generation</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Setelah Selesai, Kamu Bisa:</h4>
                        <ul>
                            <li>Perform fast numerical computations dengan NumPy</li>
                            <li>Handle large datasets efficiently</li>
                            <li>Melakukan operasi matematika dan statistik kompleks</li>
                            <li>Understand the foundation untuk advanced data science libraries</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/watch?v=QUT1VHiLmmI" target="_blank" class="video-link">
                        Belajar NumPy Dasar - FreeCodeCamp
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>NumPy mungkin terasa abstract di awal. Focus on understanding arrays dan vectorization. Practice dengan mathematical operations!</p>
                    </div>
                </div>
            </div>

            <!-- MATERI 5 -->
            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">5</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">Mini Project: Analisis Data Penjualan Sederhana</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        Saatnya apply semua yang sudah kamu pelajari! Project ini akan mengajarkan kamu 
                        melakukan analisis data penjualan real menggunakan Pandas dan NumPy. Ini adalah 
                        project hands-on pertama yang akan menjadi fondasi untuk project-project selanjutnya.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Analisis:</h4>
                        <ul>
                            <li><strong>Load Dataset:</strong> Import sales data dari CSV file</li>
                            <li><strong>Data Cleaning:</strong> Handle missing values dan data inconsistencies</li>
                            <li><strong>Sales Summary:</strong> Total sales, average order value, revenue</li>
                            <li><strong>Time Analysis:</strong> Sales trend per bulan/quarter</li>
                            <li><strong>Product Analysis:</strong> Best-selling products dan categories</li>
                            <li><strong>Customer Analysis:</strong> Top customers dan buying patterns</li>
                            <li><strong>Regional Analysis:</strong> Sales performance by region</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Questions yang Akan Dijawab:</h4>
                        <ul>
                            <li>Berapa total revenue dan jumlah transaksi?</li>
                            <li>Produk apa yang paling laku dan menghasilkan revenue tertinggi?</li>
                            <li>Bagaimana trend penjualan dari bulan ke bulan?</li>
                            <li>Region mana yang paling profitable?</li>
                            <li>Siapa top 10 customers berdasarkan spending?</li>
                            <li>Apa insight dan recommendation yang bisa diberikan?</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🛠️ Skills yang Dilatih:</h4>
                        <ul>
                            <li>Data loading dan inspection</li>
                            <li>Data cleaning dan preprocessing</li>
                            <li>Exploratory Data Analysis (EDA)</li>
                            <li>GroupBy operations untuk aggregation</li>
                            <li>Calculations dan statistical summaries</li>
                            <li>Basic insights extraction</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/watch?v=eMOA1pPVUc4" target="_blank" class="video-link">
                        Pandas Project: Analisis Data Penjualan
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>Jangan hanya copy-paste code! Pahami setiap step dan coba explore data lebih dalam. Add your own questions dan find the answers!</p>
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