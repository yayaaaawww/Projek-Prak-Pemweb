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

if ($d['id_paket'] != 3) {
    echo "<script>alert('Akses ditolak! Paket kamu bukan Paket AI & Machine Learning Expert.'); window.location='dashboard.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Projects & Deployment - Paket 3 | codeBloom</title>
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
            max-height: 2500px;
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
            background: #00695c;
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
        <a href="dashboard.php" class="back-btn">← Dashboard</a>
    </div>

    <div class="container">

        <div class="class-navigation">
            <a href="introml.php" class="class-nav-item">
                🤖 Kelas 1: Intro to ML
            </a>
            <a href="deeplearning.php" class="class-nav-item">
                🧬 Kelas 2: Deep Learning
            </a>
            <a href="aiproject.php" class="class-nav-item active">
                🚀 Kelas 3: AI Projects & Deployment
            </a>
        </div>

        <div class="header-section">
            <span class="badge">Paket 3 - Kelas 3</span>
            <h1>AI Projects & Deployment</h1>
            <p class="intro-text">
                This is it—the final boss! 🎮 Di kelas ini, kamu akan belajar cara membuat AI model menjadi 
                aplikasi web yang nyata dan deploy ke internet. From localhost to production. Let's ship it! 🚀
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
                        <div class="materi-title">Integrasi Model ke Aplikasi Web (Flask/Streamlit)</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        Model ML yang hanya ada di Jupyter Notebook tidak berguna untuk dunia nyata! 
                        Saatnya belajar cara wrap model-mu dalam web application yang bisa diakses siapa saja.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Pelajari:</h4>
                        <ul>
                            <li><strong>Flask Basics:</strong> micro web framework untuk Python</li>
                            <li>Routing dan handling HTTP requests</li>
                            <li>Loading trained ML models (pickle/joblib)</li>
                            <li>Creating REST API untuk ML predictions</li>
                            <li>Building frontend dengan HTML/CSS/JavaScript</li>
                            <li>Handling file uploads (untuk image/text input)</li>
                            <li><strong>Streamlit:</strong> rapid prototyping ML apps</li>
                            <li>Creating interactive UI components</li>
                            <li>Real-time predictions dengan user input</li>
                            <li>Visualizing model outputs</li>
                            <li>Session state management</li>
                            <li>Flask vs Streamlit: kapan pakai yang mana?</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Setelah Selesai, Kamu Bisa:</h4>
                        <ul>
                            <li>Membuat REST API untuk ML model dengan Flask</li>
                            <li>Membuat interactive web app dengan Streamlit</li>
                            <li>Menghubungkan frontend ke ML backend</li>
                            <li>Handle user input dan return predictions</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>💻 Project Example:</h4>
                        <ul>
                            <li><strong>Flask:</strong> Sentiment Analysis API—user input text, get positive/negative result</li>
                            <li><strong>Streamlit:</strong> Image Classifier—user upload image, see prediction dengan confidence scores</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/watch?v=mqhxxeeTbu0" target="_blank" class="video-link">
                        Deploy ML Model with Flask - Tech With Tim
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>Start dengan Streamlit kalau mau cepat! Flask lebih flexible tapi butuh lebih banyak code. Untuk MVP dan demo, Streamlit is your best friend. Untuk production API, go with Flask!</p>
                    </div>
                </div>
            </div>

            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">2</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">Deployment ke Cloud (Render / HuggingFace / Vercel)</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        Localhost is great, but the world needs to see your work! 🌍 
                        Belajar deploy aplikasi AI-mu ke cloud supaya bisa diakses dari mana saja, kapan saja.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Pelajari:</h4>
                        <ul>
                            <li><strong>Git & GitHub:</strong> version control untuk code management</li>
                            <li>Basic git commands: commit, push, pull</li>
                            <li>Creating GitHub repository untuk project</li>
                            <li><strong>Render:</strong> deploy Flask/Streamlit apps</li>
                            <li>Setting up requirements.txt dan dependencies</li>
                            <li>Environment variables untuk API keys</li>
                            <li>Free tier limitations dan solutions</li>
                            <li><strong>HuggingFace Spaces:</strong> hosting ML apps dengan GPU</li>
                            <li>Gradio interface untuk quick deployment</li>
                            <li>Sharing your model dengan community</li>
                            <li><strong>Vercel:</strong> deploy frontend applications</li>
                            <li>Serverless functions untuk ML inference</li>
                            <li>Custom domains dan SSL</li>
                            <li>Monitoring app performance</li>
                            <li>Debugging deployment issues</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Setelah Selesai, Kamu Bisa:</h4>
                        <ul>
                            <li>Deploy ML app ke production environment</li>
                            <li>Menggunakan Git untuk version control</li>
                            <li>Troubleshoot common deployment errors</li>
                            <li>Share aplikasi dengan link publik</li>
                            <li>Monitor dan maintain deployed apps</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>☁️ Platform Comparison:</h4>
                        <ul>
                            <li><strong>Render:</strong> Best untuk Flask apps, auto-deploy dari GitHub</li>
                            <li><strong>HuggingFace:</strong> Perfect untuk ML models, free GPU access</li>
                            <li><strong>Vercel:</strong> Great untuk frontend + serverless functions</li>
                            <li><strong>Streamlit Cloud:</strong> Native hosting untuk Streamlit apps</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/watch?v=kSZWaIqS618" target="_blank" class="video-link">
                        Deploy ML Model to Cloud - Python Engineer
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>Always test locally first! Deploy sering fail karena missing dependencies atau environment issues. Keep your requirements.txt updated dan test di virtual environment dulu sebelum deploy!</p>
                    </div>
                </div>
            </div>

            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">3</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">Final Project: Chatbot atau Image Recognition Web App</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        This is your masterpiece! 🎨 Pilih antara Chatbot atau Image Recognition app, 
                        build from scratch, dan deploy ke internet. Portfolio-ready AI project!
                    </p>
                    
                    <div class="content-section">
                        <h4>🤖 Project Option A: AI Chatbot</h4>
                        <ul>
                            <li><strong>Tech Stack:</strong> Python, Flask/Streamlit, NLP model</li>
                            <li><strong>Goal:</strong> Conversational chatbot untuk specific domain</li>
                            <li><strong>Steps:</strong></li>
                            <li>Define chatbot purpose (customer service, FAQ, assistant)</li>
                            <li>Prepare training data (intents, patterns, responses)</li>
                            <li>Build NLP model (RNN/LSTM atau pre-trained like BERT)</li>
                            <li>Train model dengan conversation data</li>
                            <li>Create chat interface dengan real-time responses</li>
                            <li>Add context awareness (remember previous messages)</li>
                            <li>Implement fallback responses</li>
                            <li>Add typing indicator dan smooth UX</li>
                            <li>Test dengan various user inputs</li>
                            <li>Deploy ke Render/HuggingFace</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🖼️ Project Option B: Image Recognition Web App</h4>
                        <ul>
                            <li><strong>Tech Stack:</strong> Python, Streamlit, CNN model</li>
                            <li><strong>Goal:</strong> Upload image, get classification/detection</li>
                            <li><strong>Steps:</strong></li>
                            <li>Choose domain (medical imaging, product recognition, etc)</li>
                            <li>Collect/find relevant dataset</li>
                            <li>Train CNN model atau use transfer learning</li>
                            <li>Optimize model untuk inference speed</li>
                            <li>Build upload interface dengan drag-and-drop</li>
                            <li>Add image preprocessing pipeline</li>
                            <li>Display predictions dengan confidence scores</li>
                            <li>Visualize activation maps (Grad-CAM)</li>
                            <li>Add batch prediction feature</li>
                            <li>Deploy dengan GPU support (HuggingFace Spaces)</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Project Requirements:</h4>
                        <ul>
                            <li>✅ Working ML/DL model dengan good accuracy</li>
                            <li>✅ Clean, responsive web interface</li>
                            <li>✅ Real-time predictions (< 3 seconds)</li>
                            <li>✅ Error handling dan user feedback</li>
                            <li>✅ Deployed dan accessible via public URL</li>
                            <li>✅ Documentation (README.md dengan usage instructions)</li>
                            <li>✅ Code di GitHub dengan proper structure</li>
                            <li>✅ Demo video atau screenshots</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>📋 Deliverables:</h4>
                        <ul>
                            <li><strong>Live App:</strong> Public URL yang bisa diakses siapa saja</li>
                            <li><strong>GitHub Repo:</strong> Complete source code dengan documentation</li>
                            <li><strong>README:</strong> Project description, setup instructions, tech stack</li>
                            <li><strong>Demo:</strong> Video/GIF showing app in action</li>
                            <li><strong>Report:</strong> Technical writeup (model architecture, dataset, results)</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/watch?v=k5pZZtsCs5c" target="_blank" class="video-link">
                        AI Web App Project - Krish Naik
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>Think like a product manager! User experience matters as much as model accuracy. Add loading indicators, clear instructions, handle errors gracefully. This project goes on your portfolio—make it shine! ✨</p>
                    </div>
                </div>
            </div>

        </div>

        <div class="footer-note">
            <h3>🏆 Congratulations, AI Engineer!</h3>
            <p>
                Kamu sudah menyelesaikan journey dari zero to AI Engineer! 🎉 Dari basic ML hingga deploy production-ready 
                AI applications. Remember: ini baru permulaan. Keep learning, keep building, dan share your knowledge 
                dengan others. The AI community needs people like you! Now go build something amazing! 🚀🌸
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