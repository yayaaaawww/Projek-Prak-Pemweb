<?php
session_start();
include "./config/koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

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

if (!$d) {
    echo "<script>alert('Kamu belum membeli paket apa pun!'); window.location='dashboard.php';</script>";
    exit();
}

if ($d['id_paket'] != 1) {
    echo "<script>alert('Akses ditolak! Paket kamu bukan Paket 1.'); window.location='dashboard.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fullstack Project - Paket 1 | codeBloom</title>
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
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
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
            background: #e8f5e9;
            color: #388e3c;
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
            background: #388e3c;
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
            background: #e8f5e9;
            color: #388e3c;
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
            color: #388e3c;
            font-weight: 700;
        }

        .video-link {
            display: inline-flex;
            align-items: center;
            background: #388e3c;
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
            background: #2e7d32;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(56, 142, 60, 0.3);
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
            border-left: 4px solid #388e3c;
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

        .congratulations-box {
            background: linear-gradient(135deg, #388e3c, #66bb6a);
            color: white;
            padding: 40px;
            border-radius: 12px;
            text-align: center;
            margin-top: 50px;
        }

        .congratulations-box h2 {
            font-size: 32px;
            margin-bottom: 15px;
        }

        .congratulations-box p {
            font-size: 16px;
            opacity: 0.95;
            line-height: 1.6;
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

            .congratulations-box h2 {
                font-size: 24px;
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
            <a href="webdasar.php" class="class-nav-item">
                🧩 Kelas 1: Web Dasar
            </a>
            <a href="backend.php" class="class-nav-item">
                ⚙️ Kelas 2: Backend & Database
            </a>
            <a href="fullstack.php" class="class-nav-item active">
                🚀 Kelas 3: Fullstack Project
            </a>
        </div>

        <div class="header-section">
            <span class="badge">Paket 1 - Kelas 3</span>
            <h1>Fullstack Project</h1>
            <p class="intro-text">
                Ini adalah kelas terakhir dan paling menantang! Kamu akan menggabungkan semua skill frontend dan 
                backend yang sudah dipelajari untuk membuat aplikasi web yang complete dan production-ready. 
                Siap jadi fullstack developer? Let's go! 🚀
            </p>
        </div>

        <div class="hero-image">
            🚀
        </div>

        <h2 class="section-title">Materi Pembelajaran</h2>

        <div class="materi-list">

            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">1</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">Integrasi Frontend & Backend</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        Saatnya menghubungkan UI yang cantik dengan backend yang powerful! Kamu akan belajar bagaimana 
                        frontend dan backend berkomunikasi untuk membuat aplikasi yang fully functional.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Pelajari:</h4>
                        <ul>
                            <li>Menghubungkan form HTML dengan Flask endpoint</li>
                            <li>Mengirim data dari frontend ke backend</li>
                            <li>Menampilkan data dari database ke halaman web</li>
                            <li>AJAX untuk update tanpa reload page</li>
                            <li>Fetch API dan Axios untuk HTTP requests</li>
                            <li>Handle loading states dan error messages</li>
                            <li>Form validation di frontend dan backend</li>
                            <li>File upload dan handling</li>
                            <li>Dynamic content rendering</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Setelah Selesai, Kamu Bisa:</h4>
                        <ul>
                            <li>Menggabungkan frontend dan backend seamlessly</li>
                            <li>Handle form submission dengan proper feedback</li>
                            <li>Create interactive web apps tanpa page reload</li>
                            <li>Debug communication issues antara client dan server</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/watch?v=Qr4QMBUPxWo" target="_blank" class="video-link">
                        Flask Frontend Integration - Tech With Tim
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>Gunakan browser DevTools Network tab untuk melihat request dan response. Ini sangat membantu untuk debugging!</p>
                    </div>
                </div>
            </div>

            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">2</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">REST API & JSON Handling</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        REST API adalah standar industri untuk komunikasi antar aplikasi. Kamu akan membuat API 
                        yang bisa digunakan oleh frontend, mobile app, atau aplikasi lain.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Pelajari:</h4>
                        <ul>
                            <li>RESTful API design principles</li>
                            <li>HTTP methods: GET, POST, PUT, DELETE, PATCH</li>
                            <li>Status codes yang proper (200, 201, 400, 404, 500)</li>
                            <li>JSON serialization dan deserialization</li>
                            <li>Creating API endpoints di Flask</li>
                            <li>Request validation dan error handling</li>
                            <li>API versioning</li>
                            <li>CORS (Cross-Origin Resource Sharing)</li>
                            <li>API documentation dengan Swagger/Postman</li>
                            <li>Rate limiting dan security</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Setelah Selesai, Kamu Bisa:</h4>
                        <ul>
                            <li>Membuat RESTful API yang well-structured</li>
                            <li>Handle JSON data dengan benar</li>
                            <li>Design API endpoints yang intuitive</li>
                            <li>Test API menggunakan Postman</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/watch?v=GMppyAPbLYk" target="_blank" class="video-link">
                        Flask REST API Tutorial - Pretty Printed
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>Install Postman untuk testing API. Biasakan dokumentasi setiap endpoint yang kamu buat - ini penting untuk collaboration!</p>
                    </div>
                </div>
            </div>

            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">3</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">Deployment ke Hosting</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        Aplikasi yang hanya jalan di localhost tidak berguna! Saatnya deploy ke internet supaya 
                        orang lain bisa akses. Kamu akan belajar cara hosting aplikasi Flask ke cloud.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Pelajari:</h4>
                        <ul>
                            <li>Persiapan aplikasi untuk production</li>
                            <li>Environment variables dan config management</li>
                            <li>Requirements.txt dan dependency management</li>
                            <li>Deploy ke Render (free hosting)</li>
                            <li>Deploy ke Railway/Vercel (alternatives)</li>
                            <li>Database hosting (PlanetScale, Supabase)</li>
                            <li>Domain custom dan DNS setup</li>
                            <li>SSL certificates untuk HTTPS</li>
                            <li>Environment: development vs production</li>
                            <li>Monitoring dan logging</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Setelah Selesai, Kamu Bisa:</h4>
                        <ul>
                            <li>Deploy aplikasi ke internet dengan percaya diri</li>
                            <li>Setup database di cloud</li>
                            <li>Troubleshoot deployment issues</li>
                            <li>Share aplikasi kamu ke portfolio dan LinkedIn!</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/watch?v=w25ea_I89iM" target="_blank" class="video-link">
                        Deploy Flask to Render - Codemy
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>Baca dokumentasi platform hosting dengan teliti. Jangan panik kalau ada error - deployment adalah skill tersendiri yang butuh latihan!</p>
                    </div>
                </div>
            </div>

            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">4</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">Final Project: E-Commerce Website</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        Ini adalah project finale yang menggabungkan SEMUA skill yang sudah kamu pelajari! 
                        Kamu akan membuat website e-commerce sederhana yang fully functional dari nol sampai deploy.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Fitur yang Akan Kamu Build:</h4>
                        <ul>
                            <li><strong>User Authentication:</strong> Register, login, logout, profile management</li>
                            <li><strong>Product Catalog:</strong> Display products dengan image, price, description</li>
                            <li><strong>Shopping Cart:</strong> Add to cart, update quantity, remove items</li>
                            <li><strong>Search & Filter:</strong> Cari produk berdasarkan nama dan kategori</li>
                            <li><strong>Admin Dashboard:</strong> Manage products (CRUD operations)</li>
                            <li><strong>Order Management:</strong> Checkout process dan order history</li>
                            <li><strong>Responsive Design:</strong> Mobile-friendly interface</li>
                            <li><strong>Image Upload:</strong> Upload product images</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🛠️ Tech Stack Lengkap:</h4>
                        <ul>
                            <li><strong>Frontend:</strong> HTML5, CSS3, JavaScript, Bootstrap</li>
                            <li><strong>Backend:</strong> Python Flask, SQLAlchemy ORM</li>
                            <li><strong>Database:</strong> MySQL/PostgreSQL</li>
                            <li><strong>Authentication:</strong> Flask-Login, Werkzeug</li>
                            <li><strong>Deployment:</strong> Render/Railway</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>📋 Development Process:</h4>
                        <ul>
                            <li>Phase 1: Database design dan models</li>
                            <li>Phase 2: Backend API endpoints</li>
                            <li>Phase 3: Frontend templates dan styling</li>
                            <li>Phase 4: Integration dan testing</li>
                            <li>Phase 5: Deployment dan final touches</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/watch?v=Qr4QMBUPxWo" target="_blank" class="video-link">
                        Flask E-Commerce Project - Traversy Media
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>Jangan terburu-buru! Build fitur satu per satu dan test sebelum lanjut. Commit code ke GitHub secara regular. Project ini adalah portfolio piece yang sangat berharga!</p>
                    </div>
                </div>
            </div>

        </div>

        <div class="congratulations-box">
            <h2>🎉 Selamat! Kamu Hampir Jadi Fullstack Developer! 🎉</h2>
            <p>
                Dengan menyelesaikan ketiga kelas ini, kamu sudah punya skill yang dibutuhkan untuk membuat 
                aplikasi web dari nol sampai production. Ini adalah achievement yang luar biasa! <br><br>
                <strong>Next steps:</strong> Build lebih banyak project, contribute ke open source, dan mulai apply pekerjaan! 
                Kamu siap! 💪✨
            </p>
        </div>

        <div class="footer-note">
            <h3>🚀 What's Next?</h3>
            <p>
                <strong>Portfolio Building:</strong> Upload semua project kamu ke GitHub dan buat portfolio website<br>
                <strong>Keep Learning:</strong> Explore framework seperti React.js, Vue.js, atau Django<br>
                <strong>Networking:</strong> Join komunitas developer, ikut meetup, dan share progress di LinkedIn<br>
                <strong>Apply Jobs:</strong> Mulai lamar posisi Junior Developer atau ambil freelance projects!
            </p>
            <p style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e0e0e0; text-align: center;">
                <strong style="font-size: 18px;">✨ Terima kasih sudah belajar di codeBloom! ✨</strong>
            </p>
        </div>

    </div>

    <script>
        function toggleMateri(header) {
            const materiItem = header.parentElement;
            const isActive = materiItem.classList.contains('active');

            document.querySelectorAll('.materi-item').forEach(item => {
                item.classList.remove('active');
            });

            if (!isActive) {
                materiItem.classList.add('active');
            }
        }
    </script>

</body>

</html>