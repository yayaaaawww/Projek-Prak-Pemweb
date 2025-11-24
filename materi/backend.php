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
    echo "<script>alert('Kamu belum membeli paket apa pun!'); window.location='landingpage.php';</script>";
    exit();
}

// HANYA UNTUK PAKET 1
if ($d['id_paket'] != 1) {
    echo "<script>alert('Akses ditolak! Paket kamu bukan Paket 1.'); window.location='landingpage.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backend & Database - Paket 1 | codeBloom</title>
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
            background: #e91e63;
            color: white;
        }

        .class-nav-item:not(.active) {
            background: #f5f5f5;
            color: #616161;
            border-color: #e0e0e0;
        }

        .class-nav-item:not(.active):hover {
            background: #fce4ec;
            color: #e91e63;
            border-color: #e91e63;
        }

        .header-section {
            margin-bottom: 60px;
        }

        .badge {
            display: inline-block;
            background: #fce4ec;
            color: #c2185b;
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
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
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
            background: #e3f2fd;
            color: #1976d2;
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
            background: #1976d2;
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
            background: #e3f2fd;
            color: #1976d2;
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
            color: #1976d2;
            font-weight: 700;
        }

        .video-link {
            display: inline-flex;
            align-items: center;
            background: #1976d2;
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
            background: #1565c0;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(25, 118, 210, 0.3);
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
            border-left: 4px solid #1976d2;
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
            <a href="webdasar.php" class="class-nav-item">
                🧩 Kelas 1: Web Dasar
            </a>
            <a href="backend_database.php" class="class-nav-item active">
                ⚙️ Kelas 2: Backend & Database
            </a>
            <a href="fullstack.php" class="class-nav-item">
                🚀 Kelas 3: Fullstack Project
            </a>
        </div>

        <div class="header-section">
            <span class="badge">Paket 1 - Kelas 2</span>
            <h1>Backend & Database</h1>
            <p class="intro-text">
                Setelah menguasai frontend, saatnya belajar backend! Di kelas ini kamu akan memahami bagaimana 
                data disimpan, diproses, dan dikirim antar server dan client. Kamu akan belajar membuat API, 
                database, dan sistem autentikasi yang powerful.
            </p>
        </div>

        <div class="hero-image">
            ⚙️
        </div>

        <h2 class="section-title">Materi Pembelajaran</h2>

        <div class="materi-list">
            
            <!-- MATERI 1 -->
            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">1</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">Pengenalan Backend Development</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        Backend adalah otak dari sebuah aplikasi web. Kamu akan memahami bagaimana server bekerja, 
                        bagaimana data dikirim dan diterima, serta peran backend dalam ekosistem web modern.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Pelajari:</h4>
                        <ul>
                            <li>Perbedaan frontend vs backend development</li>
                            <li>Konsep client-server architecture secara mendalam</li>
                            <li>Apa itu API dan bagaimana cara kerjanya</li>
                            <li>HTTP methods: GET, POST, PUT, DELETE</li>
                            <li>Status codes dan error handling</li>
                            <li>RESTful API design principles</li>
                            <li>Kenapa Python dan Flask cocok untuk backend</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Setelah Selesai, Kamu Bisa:</h4>
                        <ul>
                            <li>Menjelaskan alur kerja backend dengan percaya diri</li>
                            <li>Memahami peran backend developer dalam tim</li>
                            <li>Mengerti konsep API dan cara berkomunikasi dengan frontend</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/watch?v=cbSrsYiRamo" target="_blank" class="video-link">
                        Backend Development Explained - Sekolah Koding
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>Pahami konsep fundamental dulu sebelum mulai coding. Backend development lebih tentang logic dan problem solving!</p>
                    </div>
                </div>
            </div>

            <!-- MATERI 2 -->
            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">2</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">Belajar Flask (Python Framework)</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        Flask adalah web framework Python yang ringan dan mudah dipelajari. Perfect untuk pemula 
                        yang ingin memahami bagaimana backend bekerja tanpa kompleksitas yang berlebihan.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Pelajari:</h4>
                        <ul>
                            <li>Install dan setup Flask environment</li>
                            <li>Membuat aplikasi Flask pertama</li>
                            <li>Routing dan URL handling</li>
                            <li>Request dan Response objects</li>
                            <li>Template rendering dengan Jinja2</li>
                            <li>Static files (CSS, JS, images)</li>
                            <li>Form handling dan validation</li>
                            <li>Session management</li>
                            <li>Flask blueprints untuk modular code</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Setelah Selesai, Kamu Bisa:</h4>
                        <ul>
                            <li>Membuat web server sendiri dengan Flask</li>
                            <li>Handle HTTP requests dari client</li>
                            <li>Render dynamic HTML pages</li>
                            <li>Membangun API endpoints yang functional</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/playlist?list=PLZS-MHyEIRo6p_RwsWntxMO5QAqIHHHld" target="_blank" class="video-link">
                        Flask Tutorial Lengkap - Indonesia Belajar
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>Install Python dan Flask di laptop kamu, langsung praktik sambil nonton tutorial. Virtual environment adalah must!</p>
                    </div>
                </div>
            </div>

            <!-- MATERI 3 -->
            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">3</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">Database dan SQL Dasar</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        Database adalah jantung dari setiap aplikasi. Tanpa database, aplikasi tidak bisa menyimpan 
                        data user, transaksi, atau informasi penting lainnya. MySQL adalah salah satu database paling populer.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Pelajari:</h4>
                        <ul>
                            <li>Konsep database relational vs non-relational</li>
                            <li>Install dan setup MySQL/MariaDB</li>
                            <li>SQL basics: CREATE, SELECT, INSERT, UPDATE, DELETE</li>
                            <li>Data types dan constraints</li>
                            <li>Primary key dan foreign key</li>
                            <li>Table relationships (one-to-one, one-to-many, many-to-many)</li>
                            <li>JOIN operations untuk query kompleks</li>
                            <li>WHERE, ORDER BY, GROUP BY, HAVING</li>
                            <li>Database normalization basics</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Setelah Selesai, Kamu Bisa:</h4>
                        <ul>
                            <li>Merancang struktur database yang efisien</li>
                            <li>Menulis SQL query untuk manipulasi data</li>
                            <li>Membuat relasi antar tabel</li>
                            <li>Mengambil data dengan query yang kompleks</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/playlist?list=PLCZlgfAG0GXCe0r9emirDlfNusBPV5Nxe" target="_blank" class="video-link">
                        MySQL untuk Pemula - Sekolah Koding
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>Install phpMyAdmin atau MySQL Workbench untuk visualisasi database. Praktik dengan membuat database untuk toko online atau blog!</p>
                    </div>
                </div>
            </div>

            <!-- MATERI 4 -->
            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">4</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">Autentikasi & Login System</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        Hampir semua aplikasi modern membutuhkan sistem login. Kamu akan belajar bagaimana membuat 
                        authentication system yang secure, dari register, login, hingga session management.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Pelajari:</h4>
                        <ul>
                            <li>Password hashing dengan bcrypt/werkzeug</li>
                            <li>Membuat form register dan login</li>
                            <li>Validasi input user</li>
                            <li>Session management di Flask</li>
                            <li>Cookie handling</li>
                            <li>Protecting routes (login required)</li>
                            <li>Remember me functionality</li>
                            <li>Password reset mechanism</li>
                            <li>Security best practices (SQL injection, XSS prevention)</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Setelah Selesai, Kamu Bisa:</h4>
                        <ul>
                            <li>Membuat sistem register dan login yang aman</li>
                            <li>Handle user sessions dengan benar</li>
                            <li>Protect halaman-halaman tertentu dari akses unauthorized</li>
                            <li>Implement security best practices</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/watch?v=8aTnmsDMldY" target="_blank" class="video-link">
                        Flask Login System - Pretty Printed
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>JANGAN PERNAH simpan password dalam plain text! Selalu gunakan hashing. Test login system-mu dengan berbagai skenario error.</p>
                    </div>
                </div>
            </div>

            <!-- MATERI 5 -->
            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">5</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">Mini Project: Aplikasi CRUD</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        CRUD (Create, Read, Update, Delete) adalah operasi dasar dalam hampir semua aplikasi. 
                        Project ini akan mengajarkan kamu membuat aplikasi manajemen data yang complete dengan database.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Buat:</h4>
                        <ul>
                            <li><strong>Sistem Inventori Barang</strong> - Track produk dengan detail lengkap</li>
                            <li><strong>Create:</strong> Form untuk menambah data barang baru</li>
                            <li><strong>Read:</strong> Dashboard untuk menampilkan semua data dalam tabel</li>
                            <li><strong>Update:</strong> Edit data barang yang sudah ada</li>
                            <li><strong>Delete:</strong> Hapus data dengan konfirmasi</li>
                            <li><strong>Search:</strong> Fitur pencarian data</li>
                            <li><strong>Pagination:</strong> Handle data dalam jumlah besar</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Fitur yang Akan Diimplementasi:</h4>
                        <ul>
                            <li>Database design dengan proper relationships</li>
                            <li>Form validation (frontend & backend)</li>
                            <li>Error handling yang proper</li>
                            <li>Flash messages untuk user feedback</li>
                            <li>Responsive table design</li>
                            <li>Modal untuk delete confirmation</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🛠️ Tech Stack:</h4>
                        <ul>
                            <li>Flask (Backend framework)</li>
                            <li>MySQL (Database)</li>
                            <li>SQLAlchemy (ORM)</li>
                            <li>Bootstrap (Frontend styling)</li>
                            <li>Jinja2 (Templating)</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/watch?v=Z1RJmh_OqeA" target="_blank" class="video-link">
                        Flask CRUD Tutorial - Pretty Printed
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>Pahami alur CRUD dengan baik karena ini adalah fondasi dari hampir semua aplikasi web. Coba modifikasi project untuk kasus berbeda!</p>
                    </div>
                </div>
            </div>

        </div>

        <div class="footer-note">
            <h3>💡 Keep Going!</h3>
            <p>
                Backend development memang challenging di awal, tapi ini adalah skill yang sangat dicari di industri. 
                Praktik adalah kunci - coba buat berbagai macam API dan aplikasi CRUD dengan tema berbeda. 
                Semakin banyak kamu praktik, semakin paham alur kerjanya! 🚀
            </p>
            <p style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #e0e0e0;">
                <strong>Setelah selesai Kelas 2, lanjut ke:</strong><br>
                <a href="fullstack_project.php" style="color: #1976d2; text-decoration: none; font-weight: 600;">
                    🚀 Kelas 3: Fullstack Project →
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