
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= SITE_URL ?>/bootstrap-5.3.8-dist/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
    <?php if($pagestyle==true){?><link rel="stylesheet" href="pro.css"><?php }else{}?>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>ReserVols ✈️ | Voyages & Planification</title>
    <style>
        *{
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }
         :root {
            --dark: #0f3b4c;
            --primary-mid: #fafeff;
            --light: #f4f7fc;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --radius: 8px;
        }
        body{
            background: var(--light);
            color: #1a2c3e;
        }
        header{
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            right: 0;
            transition: background 0.25s;
        }
        header.hero-mode{
            background: transparent;
        }
        header.solid-mode{
            background: white;
        }
        header.hero-mode.scrolled{
            background: rgba(254, 254, 254, 0.93); 
            backdrop-filter: blur(15px);
            box-shadow: 0 2px 40px rgba(0,0,0,.25); 
        }
        .logo h2{
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #2c7da0, #1b4b6e);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }
        header a, footer a, footer .col-12 h5{
            position: relative;
        }
        header ul li a::before, footer ul li a::before, footer .col-12 h5::before{
            content: '';
            position: absolute;
            left: 0;
            width: 0;
            height: 2px;
            background-color: #2b7ca0;
            transition: 0.3s ease-in-out;
        }
        header .link-active{
            color: #2b7ca0;
        }
        header .link-active::before{
            width: 100%;
        }
        header ul li a::before{
            bottom: 3px;
        }
        header li a:hover, footer li a:hover{
            color: #2b7ca0 !important;
        }
        header li a:hover::before, footer li a:hover::before{
            width: 100%;
        }
        footer {
            background: var(--dark);
            color: var(--white);
            padding: 4rem 0 2rem;
        }
        footer .reseau-link i{
            transition: 0.3s;
            transform: scale(0.90);
        }
        footer .reseau-link i:hover{
            color: #2cbff8;
            transform: scale(1.2);
        }

        footer ul li a::before{
            bottom: -4px;
        }
        
        footer .col-12 h5{
            width: auto;
        }
        footer .col-12 h5::before{
            width: 15%;
            bottom: -3px;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
</head>
<body>
    <header class="p-3 <?= $heroPage ? 'hero-mode' : 'solid-mode'?>" id="navbar">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand fs-3 logo" href="<?= SITE_URL ?>/index.php"><h2>ReservVols ✈️</h2></a>

            <!-- Hamburger menu button (mobile) -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" 
                    aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation content -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="ms-5 navbar-nav mb-2 mb-lg-0 fs-5">
                    <!-- <li class="nav-item fw-bold"><a class="nav-link <?php // if ($_SERVER['SCRIPT_NAME'] === 'rservVols/index.php'):?> link-active <?php // endif ?> px-0 me-3" href="index.php">Accueil</a></li>
                    <li class="nav-item fw-bold"><a class="nav-link <?php // if ($_SERVER['SCRIPT_NAME'] === 'rservVols/index.php'):?> link-active <?php // endif ?> px-0 me-3" href="index.php#destinations">Destinations</a></li>
                    <li class="nav-item fw-bold"><a class="nav-link <?php // if ($_SERVER['SCRIPT_NAME'] === 'rservVols/index.php'):?> link-active <?php // endif ?> px-0 me-3" href="index.php#planning">Planification</a></li>
                    <li class="nav-item fw-bold"><a class="nav-link <?php // if ($_SERVER['SCRIPT_NAME'] === 'rservVols/index.php'):?> link-active <?php // endif ?> px-0 me-3" href="index.php#avis">Avis</a></li>
                    <li class="nav-item fw-bold"><a class="nav-link <?php // if ($_SERVER['SCRIPT_NAME'] === 'rservVols/index.php'):?> link-active <?php // endif ?> px-0 me-3" href="index.php#contact">Contact</a></li> -->
                    <?=  nav_item('Accueil', 'index.php', '', '', $activepage ?? ' ') ?>
                    <?=  nav_item('Destinations', 'index.php#destinations') ?>
                    <?=  nav_item('Planification', 'index.php#planning') ?>
                    <?=  nav_item('Vols', 'vol.php?donnee=default', '', '', $activepage ?? ' ') ?>
                    <?=  nav_item('Avis', 'index.php#avis') ?>
                    <?=  nav_item('Contact', 'index.php#contact') ?>
                </ul>

                <!-- User Section -->
                <div class="ms-auto">
                    <?php if (isset($_SESSION['user_id'])) { ?>
                        <div class="d-flex align-items-center gap-3">
                            <span class="fw-bold d-none d-lg-inline text-black">
                                <?php echo htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']); ?>
                            </span>
                            <a href="<?= SITE_URL ?>/profil/dashboard.php?id=<?= $_SESSION['user_id'] ?>" class="btn btn-outline-primary rounded-pill">Mon profil</a>
                        </div>
                    <?php } else { ?>
                        <div class="d-flex gap-2">
                            <a href="form/login.php" class="btn btn-outline-primary rounded-pill">Connexion</a>
                            <a href="form/sign.php" class="btn btn-primary rounded-pill">Inscription</a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </nav>
</header>
    <!-- <script src="bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script> -->
