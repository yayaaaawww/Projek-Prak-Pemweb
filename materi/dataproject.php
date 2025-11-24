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

// HANYA UNTUK PAKET 2 (Data Analyst)
if ($d['id_paket'] != 2) {
    echo "<script>alert('Akses ditolak! Paket kamu bukan Paket Data Analyst.'); window.location='../landingpage.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Project & Dashboard - Paket 2 | codeBloom</title>
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
            background: #00897b;
            color: white;
        }

        .class-nav-item:not(.active) {
            background: #f5f5f5;
            color: #616161;
            border-color: #e0e0e0;
        }

        .class-nav-item:not(.active):hover {
            background: #e0f2f1;
            color: #00897b;
            border-color: #00897b;
        }

        .header-section {
            margin-bottom: 60px;
        }

        .badge {
            display: inline-block;
            background: #e0f2f1;
            color: #00695c;
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
            background: linear-gradient(135deg, #e0f2f1 0%, #b2dfdb 100%);
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
            background: #e0f2f1;
            color: #00897b;
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
            background: #00897b;
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
            background: #e0f2f1;
            color: #00897b;
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
            color: #00897b;
            font-weight: 700;
        }

        .video-link {
            display: inline-flex;
            align-items: center;
            background: #00897b;
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
            background: #00796b;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 137, 123, 0.3);
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
            border-left: 4px solid #00897b;
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
            <a href="datahandling.php" class="class-nav-item">
                🐍 Kelas 1: Python Dasar
            </a>
            <a href="statistik.php" class="class-nav-item">
                🧮 Kelas 2: Statistik & SQL for Data
            </a>
            <a href="dataproject.php" class="class-nav-item active">
                🔍 Kelas 3: Data Project & Dashboard
            </a>
        </div>

        <div class="header-section">
            <span class="badge">Paket 2 - Kelas 3</span>
            <h1>Data Project & Dashboard</h1>
            <p class="intro-text">
                Saatnya membawa analisis data-mu ke level berikutnya! Di kelas ini, kamu akan belajar membuat 
                visualisasi yang menarik dan dashboard interaktif. Skill komunikasi data adalah yang membedakan 
                Data Analyst biasa dengan yang excellent!
            </p>
        </div>

        <div class="hero-image">
            🔍
        </div>

        <h2 class="section-title">Materi Pembelajaran</h2>

        <div class="materi-list">
            
            <!-- MATERI 1 -->
            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">1</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">Visualisasi Data dengan Matplotlib & Seaborn</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        "A picture is worth a thousand words" - terutama dalam data analysis! Kamu akan belajar 
                        membuat visualisasi yang tidak hanya cantik, tapi juga efektif dalam menyampaikan insight.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Pelajari:</h4>
                        <ul>
                            <li>Matplotlib basics: figure, axes, dan plotting</li>
                            <li>Line charts untuk trend analysis</li>
                            <li>Bar charts dan histogram untuk distribusi</li>
                            <li>Scatter plots untuk korelasi</li>
                            <li>Pie charts untuk komposisi data</li>
                            <li>Box plots untuk outlier detection</li>
                            <li>Heatmaps untuk correlation matrix</li>
                            <li>Seaborn untuk statistical visualizations</li>
                            <li>Customization: colors, labels, legends</li>
                            <li>Subplots untuk multiple visualizations</li>
                            <li>Styling dan themes</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Setelah Selesai, Kamu Bisa:</h4>
                        <ul>
                            <li>Memilih jenis chart yang tepat untuk data</li>
                            <li>Membuat visualisasi yang professional</li>
                            <li>Customize charts sesuai kebutuhan</li>
                            <li>Create visual stories dari data</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/playlist?list=PL-osiE80TeTvipOqomVEeZ1HRrcEvtZB_" target="_blank" class="video-link">
                        Data Visualization with Matplotlib - Corey Schafer
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>Perhatikan data visualization best practices! Hindari 3D charts, terlalu banyak warna, atau chart yang misleading.</p>
                    </div>
                </div>
            </div>

            <!-- MATERI 2 -->
            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">2</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">Dashboard Interaktif dengan Streamlit</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        Streamlit membuat kamu bisa create web applications untuk data science dengan mudah, tanpa 
                        perlu jago HTML/CSS/JavaScript! Perfect untuk Data Analyst yang ingin share insights secara interaktif.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Pelajari:</h4>
                        <ul>
                            <li>Install dan setup Streamlit</li>
                            <li>Basic Streamlit components: text, markdown, headers</li>
                            <li>Data display: dataframes, tables, metrics</li>
                            <li>Charts: line_chart, bar_chart, area_chart</li>
                            <li>Interactive widgets: sliders, selectbox, multiselect</li>
                            <li>File uploader untuk dynamic data</li>
                            <li>Sidebar untuk better organization</li>
                            <li>Columns dan containers untuk layout</li>
                            <li>Caching untuk performance optimization</li>
                            <li>Deployment ke Streamlit Cloud</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Setelah Selesai, Kamu Bisa:</h4>
                        <ul>
                            <li>Membuat dashboard interaktif dengan Python</li>
                            <li>Create data apps yang user-friendly</li>
                            <li>Deploy dashboard ke internet</li>
                            <li>Share insights dengan stakeholders secara efektif</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/watch?v=Klqn--Mu2pE" target="_blank" class="video-link">
                        Streamlit Dashboard Tutorial - Data Professor
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>Mulai dengan dashboard sederhana dulu. Focus on functionality, baru polish UI-nya. Streamlit sangat cepat untuk prototyping!</p>
                    </div>
                </div>
            </div>

            <!-- MATERI 3 -->
            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">3</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">Storytelling Data</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        Technical skills saja tidak cukup! Kamu perlu bisa communicate insights dengan cara yang 
                        menarik dan mudah dipahami. Data storytelling adalah seni menyampaikan insight yang actionable.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Pelajari:</h4>
                        <ul>
                            <li>Prinsip data storytelling yang efektif</li>
                            <li>Struktur narasi: context, conflict, resolution</li>
                            <li>Memahami audience dan kebutuhan mereka</li>
                            <li>Menyederhanakan insight kompleks</li>
                            <li>Visual hierarchy dan design principles</li>
                            <li>Choosing the right metrics to highlight</li>
                            <li>Creating compelling narratives dari data</li>
                            <li>Presentation skills untuk data analyst</li>
                            <li>Handling questions dan objections</li>
                            <li>Making recommendations yang actionable</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Setelah Selesai, Kamu Bisa:</h4>
                        <ul>
                            <li>Present data findings dengan percaya diri</li>
                            <li>Create compelling data stories</li>
                            <li>Influence decision-making dengan data</li>
                            <li>Communicate dengan non-technical audience</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/watch?v=8EMW7io4rSI" target="_blank" class="video-link">
                        Data Storytelling - Alex The Analyst
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>Practice makes perfect! Latihan present findings-mu ke teman atau keluarga. Get feedback dan improve communication skills-mu!</p>
                    </div>
                </div>
            </div>

            <!-- MATERI 4 -->
            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">4</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">Final Project: Dashboard Analisis E-Commerce</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        Project capstone yang menggabungkan semua skill yang sudah kamu pelajari! Kamu akan membuat 
                        end-to-end data analysis project: dari data cleaning, analysis, visualization, hingga dashboard interaktif.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Buat:</h4>
                        <ul>
                            <li><strong>Sales Performance Dashboard</strong> - Track revenue, orders, growth</li>
                            <li><strong>Customer Analytics</strong> - Segmentation, retention, lifetime value</li>
                            <li><strong>Product Performance</strong> - Best sellers, category analysis</li>
                            <li><strong>Geographic Analysis</strong> - Sales by region/city</li>
                            <li><strong>Time Series Analysis</strong> - Trends, seasonality, forecasting</li>
                            <li><strong>Cohort Analysis</strong> - Customer behavior over time</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Features Dashboard:</h4>
                        <ul>
                            <li>Interactive filters (date range, category, region)</li>
                            <li>KPI cards untuk metrics penting</li>
                            <li>Multiple visualizations yang relevan</li>
                            <li>Drill-down capabilities</li>
                            <li>Export functionality untuk reports</li>
                            <li>Mobile-responsive design</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🛠️ Tech Stack:</h4>
                        <ul>
                            <li>Python untuk data processing</li>
                            <li>Pandas untuk data manipulation</li>
                            <li>SQL untuk data extraction</li>
                            <li>Matplotlib/Seaborn untuk visualizations</li>
                            <li>Streamlit untuk dashboard</li>
                            <li>Plotly untuk interactive charts (optional)</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/watch?v=Sb0A9i6d320" target="_blank" class="video-link">
                        Streamlit Dashboard Project - Python Engineer
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>Ini adalah portfolio project! Buat se-professional mungkin. Add to GitHub, deploy ke cloud, dan showcase di LinkedIn!</p>
                    </div>
                </div>
            </div>

        </div>

        <div class="footer-note">
            <h3>🎉 Congratulations!</h3>
            <p>
                Kamu sudah menyelesaikan learning path Data Analyst! Sekarang kamu punya skill lengkap: 
                Python, statistik, SQL, visualization, dan dashboard. Yang tersisa adalah terus praktik 
                dan build portfolio projects. Remember: the best Data Analyst is one who can turn data 
                into actionable insights! 🚀
            </p>
            <p style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #e0e0e0;">
                <strong>Next Steps:</strong><br>
                ✅ Build more portfolio projects<br>
                ✅ Participate in Kaggle competitions<br>
                ✅ Apply for Data Analyst internships/jobs<br>
                ✅ Keep learning advanced topics (ML, Big Data, etc.)
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