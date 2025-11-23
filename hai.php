<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - ceBloom</title>

    <style>
        body {
            background: linear-gradient(180deg, #fee2e9, #ffeef2);
            font-family: "Poppins", sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Layout utama */
        .profile-container {
            display: flex;
            max-width: 1100px;
            margin: 40px auto;
            gap: 25px;
        }

        /* Sidebar */
        .profile-sidebar {
            width: 270px;
            background: #fbd2dc;
            border-radius: 18px;
            padding: 30px 20px;
            text-align: center;
        }

        .profile-avatar img {
            width: 120px;
            border-radius: 50%;
            border: 4px solid white;
        }

        .username {
            margin-top: 10px;
            font-weight: 600;
            font-size: 18px;
        }

        .sidebar-menu {
            margin-top: 25px;
            padding: 0;
            list-style: none;
        }

        .sidebar-menu li {
            padding: 12px;
            margin-bottom: 10px;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.2s;
            font-weight: 500;
        }

        .sidebar-menu li:hover {
            background: white;
        }

        .sidebar-menu .active {
            background: #fff;
            font-weight: 600;
        }

        /* Content */
        .profile-content {
            flex: 1;
            padding: 40px;
            background: white;
            border-radius: 18px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        }

        .profile-content h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 25px;
        }

        /* Form */
        .profile-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .profile-form input {
            width: 100%;
            padding: 12px;
            border-radius: 12px;
            border: 1px solid #ddd;
            margin-bottom: 18px;
            background: #fafafa;
            font-size: 15px;
        }

        .profile-form input:read-only {
            cursor: not-allowed;
        }

        /* Button */
        .edit-btn {
            background: #0068ff;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            cursor: pointer;
            font-weight: 600;
            margin-top: 10px;
            transition: 0.2s;
        }

        .edit-btn:hover {
            background: #0051cc;
        }
    </style>

</head>
<body>

<div class="profile-container">

    <!-- Sidebar -->
    <aside class="profile-sidebar">
        <div class="profile-avatar">
            <img src="https://i.ibb.co/2SxnDSd/user-pink.png" alt="Avatar">
            <h3 class="username">azzahfk</h3>
        </div>

        <ul class="sidebar-menu">
            <li class="active">Profil</li>
            <li>Kelas</li>
            <li>Sertifikat</li>
        </ul>
    </aside>

    <!-- Content -->
    <main class="profile-content">
        <h2>Profile</h2>

        <form class="profile-form">
            <label>Nama</label>
            <input type="text" value="azzahfk" readonly>

            <label>Email</label>
            <input type="email" value="123240168@student.upnyk.ac.id" readonly>

            <label>Password</label>
            <input type="password" value="*************" readonly>

            <button type="button" class="edit-btn">Edit Profil</button>
        </form>
    </main>

</div>

</body>
</html>
