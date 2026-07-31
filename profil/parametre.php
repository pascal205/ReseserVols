<?php
require "require.php";
$activemenu = "parametre";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètre</title>
    <link rel="stylesheet" href="../styl.css">
</head>
<body>
    <?php require_once("../header.php"); ?>

    <div class="container py-4 mt-8">
        <div class="row g-4">
            <div class="col-lg-auto">
                <?php require "slidebar.php";?>
            </div>
            <div class="col-lg-7">
                <div class="hero-card p-4 w-100">
                    <div>
                        <h5 class="text-secondary">🌐 Langue et région</h5>
                        <select name="" class="form-control ms-3">
                            <optgroup>
                                <legend>Fançais</legend>
                            </optgroup>
                            <option value="">Fançais (France)</option>
                            <option value="">Fançais (Bénin)</option>
                            <optgroup>
                                <legend>Anglais</legend>
                            </optgroup>
                            <option value="">Anglais (Angleterre)</option>
                            <option value="">Anglais (Canada)</option>
                            <option value="">Anglais (Etats-Unis)</option>
                            <optgroup>
                                <legend>Espagnol</legend>
                            </optgroup>
                            <option value="">Espagnol (Espagne)</option>
                            <option value="">Espagnol (Guinée)</option>
                        </select>
                    </div>
                    <div class="mt-4">
                        <h5 class="text-secondary">🌙 Theme</h5>
                        <div class="form-check form-switch fs-5 ms-4">
                            <input type="checkbox" name="" id="" class="form-check-input">
                            <label for="" class="form-label">Sombre/Clair</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>