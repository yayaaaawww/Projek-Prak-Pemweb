<?php
session_start();
include "../config/koneksi.php";

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

// HANYA UNTUK PAKET 1
if ($d['id_paket'] != 1) {
    echo "<script>alert('Akses ditolak! Paket kamu bukan Paket 1.'); window.location='../landingpage.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Dasar - Paket 1 | codeBloom</title>
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
        <a href="../landingpage.php" class="back-btn">← Dashboard</a>
    </div>

    <div class="container">

        <div class="class-navigation">
            <a href="webdasar.php" class="class-nav-item active">
                🧩 Kelas 1: Web Dasar
            </a>
            <a href="backend.php" class="class-nav-item">
                ⚙️ Kelas 2: Backend & Database
            </a>
            <a href="fullstack.php" class="class-nav-item">
                🚀 Kelas 3: Fullstack Project
            </a>
        </div>

        <div class="header-section">
            <span class="badge">Paket 1</span>
            <h1>Web Dasar</h1>
            <p class="intro-text">
                Selamat datang di kelas Web Dasar! Ini adalah langkah pertama kamu belajar dunia web development. 
                Kamu akan memahami dasar-dasar HTML, CSS, hingga JavaScript, serta cara kerja website modern. 
                Setelah menyelesaikan kelas ini, kamu akan siap melangkah ke level berikutnya.
            </p>
        </div>

        <div class="hero-image">
            💻
        </div>

        <h2 class="section-title">Materi Pembelajaran</h2>

        <div class="materi-list">
            
            <!-- MATERI 1 -->
            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">1</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">Pengenalan Web dan Internet</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        Modul ini akan membawa kamu memahami konsep fundamental dari web dan internet. 
                        Kamu akan belajar bagaimana website bekerja dari balik layar dan komponen-komponen penting yang membuatnya hidup.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Pelajari:</h4>
                        <ul>
                            <li>Sejarah dan evolusi internet serta World Wide Web</li>
                            <li>Bagaimana browser berkomunikasi dengan server (HTTP/HTTPS)</li>
                            <li>Konsep client-server architecture</li>
                            <li>Perbedaan website statis vs dinamis</li>
                            <li>Peran HTML, CSS, dan JavaScript dalam ekosistem web</li>
                            <li>Domain, hosting, dan DNS explained</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Setelah Selesai, Kamu Bisa:</h4>
                        <ul>
                            <li>Menjelaskan cara kerja internet dan web dengan percaya diri</li>
                            <li>Memahami alur request-response dalam web</li>
                            <li>Mengidentifikasi teknologi yang digunakan pada sebuah website</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/playlist?list=PLFIM0718LjIVuONHysfOK0ZtiqUWvrx4F" target="_blank" class="video-link">
                        Playlist HTML Dasar - Web Programming UNPAS
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>Sambil menonton video, coba buka developer tools di browser kamu (F12) dan eksplorasi bagaimana website bekerja secara real-time! Ada 16 video di playlist ini yang wajib kamu tonton dari awal sampai akhir.</p>
                    </div>
                </div>
            </div>

            <!-- MATERI 2 -->
            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">2</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">HTML Dasar</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        HTML (HyperText Markup Language) adalah tulang punggung dari setiap website. 
                        Di modul ini, kamu akan menguasai struktur dan elemen-elemen HTML yang menjadi fondasi web development.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Pelajari:</h4>
                        <ul>
                            <li>Struktur dasar dokumen HTML (doctype, html, head, body)</li>
                            <li>Text formatting: heading, paragraph, bold, italic, dll</li>
                            <li>Lists: ordered list, unordered list, dan nested lists</li>
                            <li>Links dan navigasi (anchor tags)</li>
                            <li>Images dan multimedia (img, video, audio)</li>
                            <li>Tables untuk data tabular</li>
                            <li>Forms dan input elements (text, email, password, checkbox, radio, dll)</li>
                            <li>Semantic HTML5 (header, nav, main, article, section, footer)</li>
                            <li>HTML attributes dan best practices</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Setelah Selesai, Kamu Bisa:</h4>
                        <ul>
                            <li>Membuat struktur halaman web yang proper dan semantic</li>
                            <li>Membangun form untuk mengumpulkan data user</li>
                            <li>Mengorganisir konten dengan heading dan section yang benar</li>
                            <li>Membuat website multi-halaman dengan navigasi</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/playlist?list=PLFIM0718LjIVuONHysfOK0ZtiqUWvrx4F" target="_blank" class="video-link">
                        Playlist HTML Dasar - Web Programming UNPAS (16 Video)
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>Langsung coding sambil nonton! Buat file .html dan coba semua tag yang diajarkan. Jangan takut salah, HTML sangat forgiving untuk pemula. Playlist ini lengkap dari pengenalan hingga membuat halaman web pertamamu!</p>
                    </div>
                </div>
            </div>

            <!-- MATERI 3 -->
            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">3</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">CSS Dasar</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        CSS (Cascading Style Sheets) adalah yang membuat website jadi cantik dan menarik. 
                        Kamu akan belajar cara styling dan membuat layout yang professional.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Pelajari:</h4>
                        <ul>
                            <li>Cara menambahkan CSS: inline, internal, dan external</li>
                            <li>CSS Selectors: element, class, id, attribute, pseudo-class</li>
                            <li>Colors: hex, rgb, rgba, hsl</li>
                            <li>Typography: font-family, size, weight, line-height, text-align</li>
                            <li>Box Model: margin, padding, border, width, height</li>
                            <li>Display properties: block, inline, inline-block, none</li>
                            <li>Positioning: static, relative, absolute, fixed, sticky</li>
                            <li>Flexbox untuk layout modern</li>
                            <li>CSS Grid basics</li>
                            <li>Responsive design dengan media queries</li>
                            <li>Transitions dan basic animations</li>
                            <li>Background properties dan gradients</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Setelah Selesai, Kamu Bisa:</h4>
                        <ul>
                            <li>Mengubah tampilan website sesuai keinginan</li>
                            <li>Membuat layout responsive yang bagus di semua device</li>
                            <li>Menggunakan Flexbox untuk alignment yang perfect</li>
                            <li>Menambahkan animasi dan transisi yang smooth</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/playlist?list=PLFIM0718LjIUBrbm6Gdh6k7ZUvPIAZm7p" target="_blank" class="video-link">
                        Playlist CSS Dasar - Web Programming UNPAS (24 Video)
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>Bermain-main dengan CSS! Ubah-ubah warna, size, dan layout sampai kamu menemukan kombinasi yang kamu suka. Chrome DevTools adalah teman terbaikmu! Playlist ini akan mengajarkan CSS dari nol sampai mahir.</p>
                    </div>
                </div>
            </div>

            <!-- MATERI 4 -->
            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">4</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">JavaScript Dasar</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        JavaScript adalah bahasa pemrograman yang membuat website jadi interaktif dan dinamis. 
                        Di sini kamu akan belajar fundamental programming dan cara memanipulasi halaman web.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Pelajari:</h4>
                        <ul>
                            <li>Variabel: var, let, const dan scope</li>
                            <li>Tipe data: string, number, boolean, array, object</li>
                            <li>Operator: arithmetic, comparison, logical</li>
                            <li>Kondisi: if-else, switch-case, ternary operator</li>
                            <li>Looping: for, while, do-while, for-of, for-in</li>
                            <li>Functions: declaration, expression, arrow function</li>
                            <li>DOM Manipulation: getElementById, querySelector, dll</li>
                            <li>Event Handling: click, hover, input, submit</li>
                            <li>Array methods: push, pop, map, filter, reduce</li>
                            <li>String methods dan manipulation</li>
                            <li>ES6+ features: template literals, destructuring, spread operator</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Setelah Selesai, Kamu Bisa:</h4>
                        <ul>
                            <li>Membuat website yang merespon aksi user</li>
                            <li>Validasi form sebelum submit</li>
                            <li>Mengubah konten halaman secara dinamis</li>
                            <li>Membuat fitur interaktif seperti slider, modal, dropdown</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/playlist?list=PLFIM0718LjIWXagluzROrA-iBY9eeUt4w" target="_blank" class="video-link">
                        Playlist JavaScript Dasar - Web Programming UNPAS (48 Video)
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>Gunakan console.log() untuk debugging! Jangan ragu untuk bereksperimen dan coba berbagai kombinasi code. Error adalah bagian dari proses belajar. Playlist ini super lengkap dengan 48 video!</p>
                    </div>
                </div>
            </div>

            <!-- MATERI 5 -->
            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">5</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">Mini Project: Website Portofolio</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        Saatnya mengaplikasikan semua yang sudah kamu pelajari! Project portofolio ini akan menjadi 
                        bukti nyata kemampuanmu dan bisa kamu gunakan untuk melamar pekerjaan atau freelance.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Buat:</h4>
                        <ul>
                            <li><strong>Hero Section:</strong> Header yang eye-catching dengan foto dan tagline</li>
                            <li><strong>About Section:</strong> Perkenalan diri, background, dan passion kamu</li>
                            <li><strong>Skills Section:</strong> Showcase teknologi dan tools yang kamu kuasai</li>
                            <li><strong>Portfolio/Projects:</strong> Gallery dari project-project yang pernah kamu buat</li>
                            <li><strong>Contact Form:</strong> Form yang functional untuk calon client menghubungi kamu</li>
                            <li><strong>Navigation Bar:</strong> Menu yang smooth scroll ke setiap section</li>
                            <li><strong>Responsive Design:</strong> Tampilan yang perfect di desktop, tablet, dan mobile</li>
                            <li><strong>Interactive Elements:</strong> Animasi smooth, hover effects, dan transitions</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Fitur yang Akan Diimplementasi:</h4>
                        <ul>
                            <li>Smooth scrolling navigation</li>
                            <li>Form validation dengan JavaScript</li>
                            <li>Image gallery dengan lightbox effect</li>
                            <li>Mobile hamburger menu</li>
                            <li>Social media links</li>
                            <li>Scroll-to-top button</li>
                            <li>Loading animations</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🛠️ Tools & Resources:</h4>
                        <ul>
                            <li>Text editor: VS Code (recommended)</li>
                            <li>Icons: Font Awesome atau Feather Icons</li>
                            <li>Images: Unsplash atau Pexels</li>
                            <li>Colors: Coolors.co untuk color palette</li>
                            <li>Fonts: Google Fonts</li>
                        </ul>
                    </div>

                    <a href="https://youtu.be/KRf0y2CNfL8" target="_blank" class="video-link">
                        Tonton Video Tutorial
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>Jangan hanya copy-paste! Pahami setiap baris code dan coba modifikasi sesuai style kamu sendiri. Portfolio yang unik akan membuat kamu stand out!</p>
                    </div>
                </div>
            </div>

        </div>

        <div class="footer-note">
            <h3>💡 Tips Belajar Efektif</h3>
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