<?php
require_once('config.php');
require_once("database.php");


$erreur = [];
if (isset($_POST['inscription'])) {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $motdepasse = trim($_POST['motdepasse']);
    $mdpconfirm = trim($_POST['conmdp']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL))     $erreurs[] = "L'email est invalid";
    if ($motdepasse !== $mdpconfirm)                    $erreurs[] = "Les mots de passe ne correspondent pas";

    if (empty($erreurs)) {
        $stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $erreurs[] = "Cet email existe déjà";
        }else {
            $hash = password_hash($motdepasse, PASSWORD_DEFAULT);
            $sql = "INSERT INTO user(nom, prenom, email, mdp) VALUES(:nom, :prenom, :email, :mdp)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nom'=>$nom,
                ':prenom'=>$prenom,
                ':email'=>$email,
                ':mdp'=>$hash
            ]);
            redirect("form/login.php");
            exit();
        }
    }else {
        // $_SESSION['erreur'] = $erreur;
    }


    // if ($motdepasse === $mdpcon) {
    //     $mdp = password_hash($motdepasse, PASSWORD_DEFAULT);
        
    //     $sql = "INSERT INTO user(nom, prenom, email, mdp) VALUES(:nom, :prenom, :email, :mdp)";
    //     $stmt = $pdo->prepare($sql);
    //     $stmt->execute([
    //         ':nom'=>$nom,
    //         ':prenom'=>$prenom,
    //         ':email'=>$email,
    //         ':mdp'=>$mdp
    //     ]);
        
    //     header("Location: login.php");
    //     echo 'Inscription réussi !';
    // }else{
    //     header("Location: sign.php?message = mdperror");
    // }
    

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="../bootstrap-5.3.8-dist/css/bootstrap.css">
    <style>
        *{
            margin: 0;
            padding: 0;
        }
        :root{
            --navy: #d68501;
            --navy-mid: #825d1d;
            --primary: #209aeb;
            --prim-second: #56a1d2b9;
            --secondary: rgba(255, 255, 255, 0.741);
            --gold: #E6B96A;
        }
        .hero{
            background: linear-gradient(145deg, var(--navy) 0%, var(--navy-mid) 60%, #a16d19 100%);
        }
        .container{
            height: 100vh;
        }
        a{
            text-decoration: none;
        }
        .side-left{
            color: white;
            position: relative;
        }
        .logo-wrap{
            color: white;
        }
        .logo{
            background-color: var(--primary);
            box-shadow: 0px 8px 28px #15496c2f;
            width: 50px;
            font-size: 1.8em;
        }
        .logo-wrap p{
            color: var(--secondary);
            text-transform: uppercase;
            letter-spacing: .18rem;
            margin-top: -0.6em;
        }
        .logo-wrap h2{
            margin-top: -0.3em;
        }
        .hero-pill{
            border: 1px solid var(--navy);
            background-color: #cc87193a;
            color: var(--navy);
            letter-spacing: .14rem;
            text-transform: uppercase;
            font-size: 0.68rem;
            font-weight: 700;
        }
        .side-left h1{
            font-family:'Playfair Display',serif;
        }
        .side-left h1 em{
            color: var(--primary);
        }
        .side-left .text{
            color: var(--secondary);
        }
        .hero-overlay::before{
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.45);
        }
        /* Slide de droite */
        .slide-right form{
            width: 550px;
            background-color: white;
            color: #2C3E50;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        }
        .slide-right .row{
            margin-bottom: 20px;
        }
        .input-field{
            position: relative;
        }
        .input-field label{
            font-weight: 700;
            margin-bottom: 5px;
        }
        /* .input-field label{
            position: absolute;
            left:15px;
            pointer-events: none;
            background-color: transparent;
            transform: translateY(0.7rem);
            transform: all 0.3s ease;
        }
        .input-field input{
            border: 2px solid #E0E0E0;
        } */

        .input-field input{
            padding: 8px 10px;
            font-size: 1rem;
            border-radius: 8px;
            border: 2px solid #E0E0E0;
            letter-spacing: 1px;
            width: 100%;
        }
        .btn-submit{
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-mid) 100%);
            color: white;
            font-weight: 700;
        }
        .btn-connect button{
            border: 2px solid #E0E0E0;
        }
        header a{
            color: #2C3E50;
        }
    </style>
</head>
<body>
    <header class="bg-white d-flex justify-content-between p-3">
        <a href="#" class="fw-bold fs-4">RéserVols</a>
        <a href="<?= SITE_URL?>" class="fs-5 fw-bold"><i class="fas fa-arrow-left me-2" style="font-size: 1.2rem;"></i>Retour à l'accueil</a>
    </header>
    <div class="d-flex row">
        <div class="col-md container hero-overlay side-left d-flex justify-content-center align-items-center hero w-100" style="background-image: url(../images/arrplanut.png); background-position: right; background-size: cover;">
            <div class="z-2">
                <a href=""></a>
                <a href="#" class=" logo-wrap d-flex align-items-center text-center flex-column">
                    <div class="logo d-flex justify-content-center align-items-center rounded-3">✈️</div>
                    <h4>Réservols</h4>
                    <p>Réservation de vol</p>
                </a>
                <div class="fw-bolder hero-pill rounded-pill d-flex justify-content-center align-items-center py-1 w-75">
                    Inscription gratuite
                </div>
                <h1 class="fw-bold">Rejoignez<br/> <em>notre <br/> communauté</em><br/></h1>
                <p class="text">Créez votre compte et réserver plusieurs vols</p>

                <ul>
                    <li class="mb-2 fs-5 fw-bold">Accès à tous les vols disponibles</li>
                    <li class="mb-2 fs-5 fw-bold">Gérez vos réservations</li>
                    <li class="mb-2 fs-5 fw-bold">Avis et recommandation personnalisé</li>
                    <li class="mb-2 fs-5 fw-bold">Réductions et offres exclusives</li>
                </ul>
            </div>
        </div>

        <div class="col-md slide-right w-100 bg-light p-3 d-flex justify-content-center align-items-center">
            <form method="post" class="rounded-3 p-4">
                <h2 class="text-center fw-bold"><i class="fas fa-user-plus"></i> S'inscrire</h2>
                <?php if (!empty($erreurs)) {
                    foreach ($erreurs as $err) {
                        
                 ?>
                <div class="alert alert-danger rounded-4 mb-3">
                    <strong>Erreur !</strong> <?= htmlspecialchars($err) ?>
                </div>
                <?php }}?>
                <div class="row">
                    <div class="col-sm-6 input-field">
                        <label for="name">Nom</label>
                        <input class="w-100 form-control" name="nom" type="text" placeholder="Nom" required>
                    </div>
                    <div class="col-sm-6 input-field">
                        <label for="name">Nom</label>
                        <input class="w-100 form-control" type="text" name="prenom" placeholder="Prénom" required>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field">
                        <label for="email">Email</label>
                        <input class="w-100 form-control" type="email" name="email" placeholder="Email" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 input-field">
                        <label for="name">Mot de passe</label>
                        <input class="w-100 form-control" name="motdepasse" type="password" placeholder="Mot de passe" required>
                    </div>
                    <div class="col-sm-6 input-field">
                        <label for="name">Confirmer</label>
                        <input class="w-100 form-control" type="password" name="conmdp" placeholder="Confirmer mot de passe" required>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="">
                        <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                        <label class="ms-2">
                            J'accepte les <a href="#" class="link-auth" target="_blank">conditions</a> et <a href="#" class="link-auth" target="_blank">politique</a>
                        </label>
                    </div>
                </div>
                <button type="submit" name="inscription" class="btn btn-submit w-100 fs-5"><i class="fas fa-arrow-right"></i> Soumettre</button>
                
                <div class="d-flex gap-2 mt-3">
                    <hr class="flex-grow-1">
                    <small class="mt-1">ou</small>
                    <hr class="flex-grow-1">
                </div>
                <div class="mt-3 d-flex justify-content-center gap-3 btn-connect">
                    <div>
                        <button class="btn px-4"><i class="fab fa-google"></i> Google</button>
                    </div>
                    <div>
                        <button class="btn"><i class="fab fa-facebook"></i> Facebook</button>
                    </div>
                </div>
                <div class="mt-3">
                    <hr>
                </div>
                <p class="text-center">Déjà inscrire ? <a href="login.php">Se connecter</a></p>
            </form>
        </div>
    </div>
</body>
</html>