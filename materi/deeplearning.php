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
    <title>Deep Learning - Paket 3 | codeBloom</title>
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
            background: linear-gradient(135deg, #e1f5fe 0%, #b3e5fc 100%);
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
            background: #e1f5fe;
            color: #0277bd;
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
            background: #0277bd;
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
            background: #e1f5fe;
            color: #0277bd;
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
            color: #0277bd;
            font-weight: 700;
        }

        .video-link {
            display: inline-flex;
            align-items: center;
            background: #0277bd;
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
            background: #01579b;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(2, 119, 189, 0.3);
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
            border-left: 4px solid #0277bd;
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
            <a href="introml.php" class="class-nav-item">
                🤖 Kelas 1: Intro to ML
            </a>
            <a href="deeplearning.php" class="class-nav-item active">
                🧬 Kelas 2: Deep Learning
            </a>
            <a href="aiproject.php" class="class-nav-item">
                🚀 Kelas 3: AI Projects & Deployment
            </a>
        </div>

        <div class="header-section">
            <span class="badge">Paket 3 - Kelas 2</span>
            <h1>Deep Learning</h1>
            <p class="intro-text">
                Welcome to the world of Neural Networks! Di kelas ini, kamu akan masuk lebih dalam ke teknologi di balik AI modern—mulai dari image recognition, natural language processing, hingga model yang bisa "berpikir" seperti manusia. Let's dive deep! 🧬
            </p>
        </div>

        <div class="hero-image">
            🧬
        </div>

        <h2 class="section-title">Materi Pembelajaran</h2>

        <div class="materi-list">
            
            <!-- MATERI 1 -->
            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">1</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">Pengenalan Neural Network</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        Neural Network adalah jantung dari Deep Learning! Terinspirasi dari cara kerja otak manusia, 
                        kamu akan memahami bagaimana komputer bisa "belajar" melalui jaringan neuron buatan yang saling terhubung.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Pelajari:</h4>
                        <ul>
                            <li>Konsep dasar neuron dan cara kerjanya</li>
                            <li>Struktur Neural Network: input layer, hidden layer, output layer</li>
                            <li>Activation functions: Sigmoid, ReLU, Tanh, Softmax</li>
                            <li>Forward propagation: bagaimana data mengalir di network</li>
                            <li>Backpropagation: cara neural network belajar dari error</li>
                            <li>Gradient descent dan optimization</li>
                            <li>Loss functions untuk training</li>
                            <li>Implementasi Neural Network dengan TensorFlow/Keras</li>
                            <li>Visualisasi cara kerja neural network</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Setelah Selesai, Kamu Bisa:</h4>
                        <ul>
                            <li>Menjelaskan konsep Neural Network dengan jelas</li>
                            <li>Memahami matematika di balik backpropagation</li>
                            <li>Membuat simple neural network dari scratch</li>
                            <li>Mengimplementasikan NN dengan library modern</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/watch?v=aircAruvnKk" target="_blank" class="video-link">
                        Neural Networks Explained - 3Blue1Brown
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>Visualisasi sangat penting! Gunakan tools seperti TensorFlow Playground untuk "melihat" bagaimana neural network belajar secara real-time. Ini akan membantumu memahami konsep abstrak dengan lebih baik.</p>
                    </div>
                </div>
            </div>

            <!-- MATERI 2 -->
            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">2</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">Convolutional Neural Network (CNN)</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        CNN adalah arsitektur neural network yang powerful untuk computer vision! Dari face recognition 
                        hingga self-driving cars, CNN adalah teknologi di baliknya. Let's learn how machines "see"!
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Pelajari:</h4>
                        <ul>
                            <li>Kenapa CNN cocok untuk image data?</li>
                            <li>Convolutional layers: mendeteksi patterns di gambar</li>
                            <li>Filters/kernels: cara CNN "melihat" features</li>
                            <li>Pooling layers: reducing dimensionality</li>
                            <li>Fully connected layers untuk classification</li>
                            <li>Arsitektur CNN populer: LeNet, VGG, ResNet, Inception</li>
                            <li>Transfer learning: menggunakan pre-trained models</li>
                            <li>Data augmentation untuk improve performa</li>
                            <li>Implementasi CNN dengan TensorFlow/Keras</li>
                            <li>Applications: image classification, object detection, face recognition</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Setelah Selesai, Kamu Bisa:</h4>
                        <ul>
                            <li>Membangun CNN untuk image classification</li>
                            <li>Menggunakan transfer learning untuk quick results</li>
                            <li>Memahami cara CNN extract features dari gambar</li>
                            <li>Menerapkan data augmentation untuk better generalization</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/watch?v=HGwBXDKFk9I" target="_blank" class="video-link">
                        CNN Tutorial - Sentdex
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>Mulai dengan dataset kecil seperti MNIST atau CIFAR-10! Jangan langsung tackle dataset besar. Pahami dulu bagaimana setiap layer bekerja dengan visualize feature maps di setiap convolutional layer.</p>
                    </div>
                </div>
            </div>

            <!-- MATERI 3 -->
            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">3</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">Recurrent Neural Network (RNN)</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        RNN adalah neural network untuk sequential data! Dari chatbots hingga machine translation, 
                        RNN memungkinkan AI memahami konteks dan urutan—seperti cara kita memahami kalimat.
                    </p>
                    
                    <div class="content-section">
                        <h4>📚 Yang Akan Kamu Pelajari:</h4>
                        <ul>
                            <li>Konsep sequential data dan kenapa penting</li>
                            <li>Vanilla RNN: basic recurrent architecture</li>
                            <li>Problem vanishing/exploding gradients</li>
                            <li>LSTM (Long Short-Term Memory): solving long-term dependencies</li>
                            <li>GRU (Gated Recurrent Unit): simplified LSTM</li>
                            <li>Bidirectional RNN: memproses data dari 2 arah</li>
                            <li>Text preprocessing untuk NLP tasks</li>
                            <li>Word embeddings: Word2Vec, GloVe</li>
                            <li>Applications: sentiment analysis, text generation, translation</li>
                            <li>Introduction to Transformers (next-gen of RNN)</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Setelah Selesai, Kamu Bisa:</h4>
                        <ul>
                            <li>Membangun RNN/LSTM untuk text classification</li>
                            <li>Melakukan sentiment analysis pada reviews</li>
                            <li>Membuat simple text generator</li>
                            <li>Memahami cara AI process natural language</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/watch?v=LHXXI4-IEns" target="_blank" class="video-link">
                        RNN & LSTM Explained - StatQuest
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>RNN lebih tricky dari CNN! Start dengan simple tasks seperti name generation atau sentiment analysis. Gunakan pre-trained word embeddings untuk better results. Dan ingat: LSTM > Vanilla RNN untuk most cases!</p>
                    </div>
                </div>
            </div>

            <!-- MATERI 4 -->
            <div class="materi-item">
                <div class="materi-header" onclick="toggleMateri(this)">
                    <div class="materi-number">4</div>
                    <div class="materi-title-wrapper">
                        <div class="materi-title">Mini Project: Image Classification atau Sentiment Analysis</div>
                        <div class="materi-subtitle">Klik untuk lihat detail materi</div>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="materi-content">
                    <p class="materi-intro">
                        Time to build something amazing! Pilih salah satu project: Image Classification dengan CNN 
                        atau Sentiment Analysis dengan RNN/LSTM. Real Deep Learning in action! 🚀
                    </p>
                    
                    <div class="content-section">
                        <h4>🖼️ Project Option A: Image Classification</h4>
                        <ul>
                            <li><strong>Dataset:</strong> Fashion-MNIST atau Custom Dataset (misal: Cats vs Dogs)</li>
                            <li><strong>Goal:</strong> Klasifikasi gambar dengan akurasi > 90%</li>
                            <li><strong>Steps:</strong></li>
                            <li>Load dan explore dataset</li>
                            <li>Data preprocessing & augmentation</li>
                            <li>Build CNN architecture dari scratch</li>
                            <li>Training dengan callbacks (EarlyStopping, ModelCheckpoint)</li>
                            <li>Visualize training history</li>
                            <li>Evaluate pada test set</li>
                            <li>Try transfer learning (VGG16/ResNet) untuk comparison</li>
                            <li>Deploy model untuk predict new images</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>💬 Project Option B: Sentiment Analysis</h4>
                        <ul>
                            <li><strong>Dataset:</strong> Movie Reviews (IMDB) atau Twitter Sentiment</li>
                            <li><strong>Goal:</strong> Klasifikasi sentimen positif/negatif dari teks</li>
                            <li><strong>Steps:</strong></li>
                            <li>Load dan explore text dataset</li>
                            <li>Text cleaning & preprocessing</li>
                            <li>Tokenization & padding sequences</li>
                            <li>Build LSTM/GRU model</li>
                            <li>Add embedding layer (atau gunakan pre-trained)</li>
                            <li>Training dengan validation split</li>
                            <li>Evaluate performa (accuracy, F1-score)</li>
                            <li>Test pada custom text</li>
                            <li>Visualize word embeddings (optional)</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h4>🎯 Output Project:</h4>
                        <ul>
                            <li>Working Deep Learning model dengan good performance</li>
                            <li>Jupyter notebook lengkap dengan dokumentasi</li>
                            <li>Visualisasi: training curves, confusion matrix, predictions</li>
                            <li>Model yang bisa predict data baru</li>
                            <li>Report tentang arsitektur dan hasil experiment</li>
                        </ul>
                    </div>

                    <a href="https://www.youtube.com/watch?v=jztwpsIzEGc" target="_blank" class="video-link">
                        Image Classification Project - Codebasics
                    </a>

                    <div class="tips-box">
                        <strong>💡 Tips Belajar:</strong>
                        <p>Don't expect perfect results di first try! Deep Learning is all about experimentation. Try different architectures, hyperparameters, dan techniques. Document everything—apa yang works dan apa yang tidak!</p>
                    </div>
                </div>
            </div>

        </div>

        <div class="footer-note">
            <h3>🎉 Amazing Progress!</h3>
            <p>
                Kamu sudah masuk ke dunia Deep Learning—teknologi yang power ChatGPT, self-driving cars, 
                dan banyak AI modern lainnya! Keep experimenting, keep learning, dan jangan takut untuk 
                try crazy ideas. That's how breakthroughs happen! 🌸
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