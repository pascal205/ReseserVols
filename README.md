# ✈️ ReserVols (Simulation)

> **Application web de réservation et de gestion de vols**

ReserVols est une application web permettant aux utilisateurs de rechercher des vols, consulter leurs informations, effectuer des réservations et suivre le processus de paiement.

Le projet a été réalisé dans le but de mettre en pratique le développement web avec **PHP, MySQL, HTML, CSS, JavaScript et Bootstrap**.

---

## 📌 Fonctionnalités

### ✈️ Gestion des vols

* Consultation des vols disponibles
* Recherche d'un vol
* Consultation des détails d'un vol
* Affichage des informations de départ et d'arrivée
* Affichage des horaires
* Affichage du prix et des informations de la compagnie

### 👤 Gestion des utilisateurs

* Création et gestion du profil
* Gestion des informations utilisateur
* Accès aux fonctionnalités liées aux réservations

### 🎫 Réservations

* Sélection d'un vol
* Réservation d'un vol
* Confirmation de réservation
* Gestion du processus de réservation

### 💳 Paiement

* Interface de paiement
* Traitement du paiement
* Page de confirmation

---

## 🛠️ Technologies utilisées

| Technologie            | Utilisation                                   |
| ---------------------- | --------------------------------------------- |
| 🐘 **PHP**             | Backend et logique serveur                    |
| 🗄️ **MySQL**          | Gestion de la base de données                 |
| 🔌 **PDO**             | Connexion et requêtes vers la base de données |
| 🌐 **HTML5**           | Structure des pages                           |
| 🎨 **CSS3**            | Mise en forme                                 |
| ⚡ **JavaScript**       | Interactions dynamiques                       |
| 🧩 **Bootstrap 5.3.8** | Interface responsive                          |
| 🔧 **Git / GitHub**    | Gestion du code source                        |

---

## 📂 Structure du projet

```text
ReseserVols/
│
├── admin/                    # Interface d'administration
│
├── bootstrap-5.3.8-dist/    # Fichiers Bootstrap
│
├── form/                    # Formulaires et configuration
│
├── images/                  # Images du projet
│
├── profil/                  # Gestion du profil utilisateur
│
├── confirmation.php         # Confirmation de réservation
├── detail.php               # Détails d'un vol
├── footer.php               # Pied de page
├── get_airports.php         # Récupération des aéroports
├── hash.php                 # Gestion du hash
├── header.php               # En-tête
├── index.php                # Page d'accueil
├── menu.php                 # Menu de navigation
├── newsletter.php           # Newsletter
├── paiement.php             # Page de paiement
├── planning.php             # Planification d'un trajet
├── reservation.php          # Réservation
├── traitement_paiement.php  # Traitement du paiement
├── vol.php                  # Gestion/affichage des vols
│
├── script.js                # JavaScript
├── styl.css                 # Styles principaux
├── pro.css                  # Styles supplémentaires
│
├── test.html                # Page de test
└── README.md                # Documentation du projet
```

Cette structure correspond aux principaux fichiers et dossiers actuellement présents dans le dépôt.

---

## ⚙️ Installation

### 1. Cloner le projet

```bash
git clone https://github.com/pascal205/ReseserVols.git
```

Puis accéder au dossier :

```bash
cd ReseserVols
```

### 2. Installer un serveur local

Le projet nécessite un environnement permettant d'exécuter **PHP et MySQL**.

Vous pouvez utiliser par exemple :

* XAMPP
* WAMP
* Laragon
* ou un serveur PHP équivalent

### 3. Configurer la base de données

Créez une base de données MySQL pour le projet.

Configurez ensuite les informations de connexion dans les fichiers de configuration présents dans le dossier :

```text
form/
```

Les informations de connexion peuvent généralement être configurées avec :

```text
Hôte
Nom de la base de données
Utilisateur
Mot de passe
```

> ⚠️ Ne publiez jamais vos mots de passe ou autres informations sensibles dans un dépôt GitHub public.

### 4. Lancer le projet

Placez le projet dans le dossier correspondant à votre serveur local.

Avec XAMPP, par exemple :

```text
xampp/
└── htdocs/
    └── ReseserVols/
```

Démarrez ensuite **Apache** et **MySQL**.

Vous pourrez accéder à l'application depuis :

```text
http://localhost/ReseserVols/
```

---

## 🔄 Fonctionnement

Le parcours principal de l'application peut être représenté ainsi :

```text
             ┌──────────────┐
             │    Accueil   │
             └──────┬───────┘
                    │
                    ▼
             ┌──────────────┐
             │ Recherche de │
             │     vols     │
             └──────┬───────┘
                    │
                    ▼
             ┌──────────────┐
             │ Liste des    │
             │    vols      │
             └──────┬───────┘
                    │
                    ▼
             ┌──────────────┐
             │ Détails du   │
             │     vol      │
             └──────┬───────┘
                    │
                    ▼
             ┌──────────────┐
             │ Réservation  │
             └──────┬───────┘
                    │
                    ▼
             ┌──────────────┐
             │   Paiement   │
             └──────┬───────┘
                    │
                    ▼
             ┌──────────────┐
             │ Confirmation │
             └──────────────┘
```

---

## 🔐 Sécurité

Le projet utilise **PDO** pour communiquer avec la base de données.

Les requêtes préparées peuvent notamment être utilisées pour éviter les injections SQL.

Exemple :

```php
$stmt = $pdo->prepare(
    "SELECT * FROM aeroport WHERE ville = ?"
);

$stmt->execute([$villeDep]);
```

Pour une utilisation en production, plusieurs améliorations de sécurité pourraient être ajoutées :

* Protection CSRF
* Validation des données côté serveur
* Validation des formulaires
* Gestion renforcée des sessions
* Gestion sécurisée des erreurs
* Protection des informations sensibles

---

## 🚧 État du projet

🟡 **Projet en développement**

Le projet est actuellement fonctionnel sur plusieurs parties, mais certaines fonctionnalités peuvent encore être améliorées.

### Roadmap

* [ ] Améliorer l'interface utilisateur
* [ ] Améliorer la recherche de vols
* [ ] Ajouter davantage de filtres
* [ ] Renforcer la sécurité
* [ ] Améliorer la gestion des erreurs
* [ ] Ajouter des tests
* [ ] Améliorer le système de paiement
* [ ] Améliorer l'interface d'administration
* [ ] Optimiser le responsive design
* [ ] Ajouter une documentation technique plus détaillée

---

## 🎓 Objectif pédagogique

Ce projet constitue également un exercice pratique permettant de mettre en application plusieurs notions du développement web :

```text
HTML
  ↓
CSS / Bootstrap
  ↓
JavaScript
  ↓
PHP
  ↓
PDO
  ↓
MySQL
```

Il permet ainsi de comprendre comment les différentes couches d'une application web peuvent communiquer entre elles.

---

## 🤝 Contribution

Les suggestions et contributions sont les bienvenues.

Pour contribuer :

```bash
# Cloner le projet
git clone https://github.com/pascal205/ReseserVols.git

# Créer une nouvelle branche
git checkout -b feature/ma-fonctionnalite

# Ajouter les modifications
git add .

# Créer un commit
git commit -m "feat: ajout d'une nouvelle fonctionnalité"

# Envoyer la branche
git push origin feature/ma-fonctionnalite
```

Vous pouvez ensuite ouvrir une **Pull Request** sur GitHub.

---

## 📄 Licence

Aucune licence open source n'est actuellement définie pour ce projet.

Si le projet doit être distribué ou réutilisé par d'autres développeurs, une licence pourra être ajoutée ultérieurement.

---

## 👨‍💻 Auteur

**Pascal**

🔗 **GitHub :**
https://github.com/pascal205

🔗 **Projet :**
https://github.com/pascal205/ReseserVols

---

<p align="center">

### ✈️ ReserVols

**Simplifier la recherche et la réservation de vos vols.**

</p>
