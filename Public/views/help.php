<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="/components/nav_home/nav_home.css">
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <title>Document</title>
     <style>
        iframe {
            width: 100%;
            height: 100vh;
            border: none;
        }
    </style>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background-color: #fff;
        }

        /* Add top padding so it doesn't go behind the navbar */
        .container {
            padding-top: 100px; /* Adjust this based on your navbar height */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        h1 {
            font-size: 28px;
            margin-bottom: 40px;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
            max-width: 1000px;
            width: 100%;
            padding: 0 20px;
        }

        .card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 25px;
            text-align: center;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 12px;
            text-decoration: none;
            color: #333;
            transition: all 0.2s ease-in-out;
        }

        .card:hover {
            background-color:rgb(247, 220, 231);
            transform: scale(1.03);
            border-color: rgb(247, 220, 231);
        }

        .card-icon {
            font-size: 36px;
            margin-bottom: 12px;
        }

        .card-title {
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>
     <header>
        <div id="nav-placeholder"></div>
    </header>
    <!--<iframe src="/../assets/manual/user_manualpdf.pdf"></iframe>-->
    <div class="container">
    <h1>Temat e rekomanduara</h1>

    <div class="card-grid">
        <a class="card" href="/../assets/manual/menaxhimiProfilit.pdf" target="_blank">
            <div class="card-icon">👤</div>
            <div class="card-title">Menaxhimi i profilit</div>
        </a>
        <a class="card" href="/../assets/manual/PostimidhePunesimi.pdf" target="_blank">
            <div class="card-icon">📄</div>
            <div class="card-title">Postimet dhe punësimi</div>
        </a>
        <a class="card" href="/../assets/manual/KerkimidheLidhja.pdf" target="_blank">
            <div class="card-icon">🔍</div>
            <div class="card-title">Kërkimi dhe lidhja</div>
        </a>
        <a class="card" href="/../assets/manual/KomentetdheVleresimi.pdf" target="_blank">
            <div class="card-icon">⭐</div>
            <div class="card-title">Vlerësimet dhe komentet</div>
        </a>
        <a class="card" href="/../assets/manual/user_manualpdf.pdf" target="_blank">
            <div class="card-icon">📘</div>
            <div class="card-title">Manuali Komplet</div>
        </a>

    </div>
</div>

        <script src="/components/nav_home/nav_home.js"></script>

</body>
</html>