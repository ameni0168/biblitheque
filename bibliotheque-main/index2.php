<?php
// Connexion à la base de données avec MySQLi
$mysqli = new mysqli("localhost", "root", "", "bibliotheque");

// Vérifier la connexion
if ($mysqli->connect_error) {
    die("Erreur de connexion : " . $mysqli->connect_error);
}

// Message d'erreur ou de succès
$message = '';

// Traitement des actions (ajout, modification, suppression)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ajouter un livre
    if (isset($_POST['ajouter_livre'])) {
        $titre = $_POST['titre'];
        $auteur = $_POST['auteur'];
        $annee = (int)$_POST['annee'];
        $genre = $_POST['genre'];
        $stmt = $mysqli->prepare("INSERT INTO Livres (titre, auteur, annee, genre) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $titre, $auteur, $annee, $genre);
        try {
            if ($stmt->execute()) {
                $message = "<div class='alert alert-success'>Livre ajouté avec succès.</div>";
            } else {
                $message = "<div class='alert alert-danger'>Erreur lors de l'ajout du livre.</div>";
            }
        } catch (mysqli_sql_exception $e) {
            $message = "<div class='alert alert-danger'>" . htmlspecialchars($e->getMessage()) . "</div>";
        }
        $stmt->close();
    }
    // Modifier un livre
    if (isset($_POST['modifier_livre'])) {
        $id_livre = $_POST['id_livre'];
        $titre = $_POST['titre'];
        $auteur = $_POST['auteur'];
        $annee = (int)$_POST['annee'];
        $genre = $_POST['genre'];
        $stmt = $mysqli->prepare("UPDATE Livres SET titre = ?, auteur = ?, annee = ?, genre = ? WHERE id_livre = ?");
        $stmt->bind_param("ssisi", $titre, $auteur, $annee, $genre, $id_livre);
        try {
            if ($stmt->execute()) {
                $message = "<div class='alert alert-success'>Livre modifié avec succès.</div>";
            } else {
                $message = "<div class='alert alert-danger'>Erreur lors de la modification du livre.</div>";
            }
        } catch (mysqli_sql_exception $e) {
            $message = "<div class='alert alert-danger'>" . htmlspecialchars($e->getMessage()) . "</div>";
        }
        $stmt->close();
    }
    // Ajouter un utilisateur
    if (isset($_POST['ajouter_utilisateur'])) {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $stmt = $mysqli->prepare("INSERT INTO Utilisateurs (nom, prenom, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nom, $prenom, $email);
        try {
            if ($stmt->execute()) {
                $message = "<div class='alert alert-success'>Utilisateur ajouté avec succès.</div>";
            } else {
                $message = "<div class='alert alert-danger'>Erreur lors de l'ajout de l'utilisateur.</div>";
            }
        } catch (mysqli_sql_exception $e) {
            $message = "<div class='alert alert-danger'>" . htmlspecialchars($e->getMessage()) . "</div>";
        }
        $stmt->close();
    }
    // Modifier un utilisateur
    if (isset($_POST['modifier_utilisateur'])) {
        $id_utilisateur = $_POST['id_utilisateur'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $stmt = $mysqli->prepare("UPDATE Utilisateurs SET nom = ?, prenom = ?, email = ? WHERE id_utilisateur = ?");
        $stmt->bind_param("sssi", $nom, $prenom, $email, $id_utilisateur);
        try {
            if ($stmt->execute()) {
                $message = "<div class='alert alert-success'>Utilisateur modifié avec succès.</div>";
            } else {
                $message = "<div class='alert alert-danger'>Erreur lors de la modification de l'utilisateur.</div>";
            }
        } catch (mysqli_sql_exception $e) {
            $message = "<div class='alert alert-danger'>" . htmlspecialchars($e->getMessage()) . "</div>";
        }
        $stmt->close();
    }
    // Ajouter un emprunt
    if (isset($_POST['ajouter_emprunt'])) {
        $id_livre = $_POST['id_livre'];
        $id_utilisateur = $_POST['id_utilisateur'];
        $date_emprunt = date('Y-m-d');
        $stmt = $mysqli->prepare("INSERT INTO Emprunts (id_livre, id_utilisateur, date_emprunt) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $id_livre, $id_utilisateur, $date_emprunt);
        try {
            if ($stmt->execute()) {
                $message = "<div class='alert alert-success'>Emprunt ajouté avec succès.</div>";
            } else {
                $message = "<div class='alert alert-danger'>Erreur lors de l'ajout de l'emprunt.</div>";
            }
        } catch (mysqli_sql_exception $e) {
            $message = "<div class='alert alert-danger'>" . htmlspecialchars($e->getMessage()) . "</div>";
        }
        $stmt->close();
    }
}

// Supprimer un livre
if (isset($_GET['supprimer_livre'])) {
    $id_livre = $_GET['supprimer_livre'];
    $stmt = $mysqli->prepare("DELETE FROM Livres WHERE id_livre = ?");
    $stmt->bind_param("i", $id_livre);
    try {
        if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>Livre supprimé avec succès.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Erreur lors de la suppression du livre.</div>";
        }
    } catch (mysqli_sql_exception $e) {
        $message = "<div class='alert alert-danger'>" . htmlspecialchars($e->getMessage()) . "</div>";
    }
    $stmt->close();
}

// Supprimer un utilisateur
if (isset($_GET['supprimer_utilisateur'])) {
    $id_utilisateur = $_GET['supprimer_utilisateur'];
    $stmt = $mysqli->prepare("DELETE FROM Utilisateurs WHERE id_utilisateur = ?");
    $stmt->bind_param("i", $id_utilisateur);
    try {
        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>Utilisateur supprimé avec succès.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Erreur lors de la suppression de l'utilisateur.</div>";
        }
    } catch (mysqli_sql_exception $e) {
        $message = "<div class='alert alert-danger'>" . htmlspecialchars($e->getMessage()) . "</div>";
    }
    $stmt->close();
}

// Supprimer un emprunt (considéré comme retour)
if (isset($_GET['supprimer_emprunt'])) {
    $id_emprunt = $_GET['supprimer_emprunt'];
    $stmt = $mysqli->prepare("DELETE FROM Emprunts WHERE id_emprunt = ?");
    $stmt->bind_param("i", $id_emprunt);
    try {
        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>Emprunt retourné avec succès.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Erreur lors du retour de l'emprunt.</div>";
        }
    } catch (mysqli_sql_exception $e) {
        $message = "<div class='alert alert-danger'>" . htmlspecialchars($e->getMessage()) . "</div>";
    }
    $stmt->close();
}

// Recherche
$livres = [];
$utilisateurs = [];
$emprunts = [];
$recherche_livre = isset($_GET['recherche_livre']) ? $_GET['recherche_livre'] : '';
$recherche_utilisateur = isset($_GET['recherche_utilisateur']) ? $_GET['recherche_utilisateur'] : '';

if ($recherche_livre) {
    $recherche_livre = "%$recherche_livre%";
    $stmt = $mysqli->prepare("SELECT * FROM Livres WHERE titre LIKE ? OR auteur LIKE ?");
    $stmt->bind_param("ss", $recherche_livre, $recherche_livre);
    try {
        $stmt->execute();
        $result = $stmt->get_result();
        $livres = $result->fetch_all(MYSQLI_ASSOC);
    } catch (mysqli_sql_exception $e) {
        $message = "<div class='alert alert-danger'>" . htmlspecialchars($e->getMessage()) . "</div>";
    }
    $stmt->close();
} else {
    $result = $mysqli->query("SELECT * FROM Livres");
    $livres = $result->fetch_all(MYSQLI_ASSOC);
}

if ($recherche_utilisateur) {
    $recherche_utilisateur = "%$recherche_utilisateur%";
    $stmt = $mysqli->prepare("SELECT * FROM Utilisateurs WHERE nom LIKE ?");
    $stmt->bind_param("s", $recherche_utilisateur);
    try {
        $stmt->execute();
        $result = $stmt->get_result();
        $utilisateurs = $result->fetch_all(MYSQLI_ASSOC);
    } catch (mysqli_sql_exception $e) {
        $message = "<div class='alert alert-danger'>" . htmlspecialchars($e->getMessage()) . "</div>";
    }
    $stmt->close();
} else {
    $result = $mysqli->query("SELECT * FROM Utilisateurs");
    $utilisateurs = $result->fetch_all(MYSQLI_ASSOC);
}

// Liste des emprunts (avec jointures pour afficher titre et nom)
try {
    $result = $mysqli->query("
        SELECT e.id_emprunt, e.id_livre, e.id_utilisateur, e.date_emprunt, e.date_retour, 
               l.titre, u.nom, u.prenom
        FROM Emprunts e
        JOIN Livres l ON e.id_livre = l.id_livre
        JOIN Utilisateurs u ON e.id_utilisateur = u.id_utilisateur
    ");
    $emprunts = $result->fetch_all(MYSQLI_ASSOC);
} catch (mysqli_sql_exception $e) {
    $message = "<div class='alert alert-danger'>" . htmlspecialchars($e->getMessage()) . "</div>";
}

// Données pour modification
$livre_modif = null;
if (isset($_GET['modifier_livre'])) {
    $id_livre = $_GET['modifier_livre'];
    $stmt = $mysqli->prepare("SELECT * FROM Livres WHERE id_livre = ?");
    $stmt->bind_param("i", $id_livre);
    try {
        $stmt->execute();
        $result = $stmt->get_result();
        $livre_modif = $result->fetch_assoc();
    } catch (mysqli_sql_exception $e) {
        $message = "<div class='alert alert-danger'>" . htmlspecialchars($e->getMessage()) . "</div>";
    }
    $stmt->close();
}

$utilisateur_modif = null;
if (isset($_GET['modifier_utilisateur'])) {
    $id_utilisateur = $_GET['modifier_utilisateur'];
    $stmt = $mysqli->prepare("SELECT * FROM Utilisateurs WHERE id_utilisateur = ?");
    $stmt->bind_param("i", $id_utilisateur);
    try {
        $stmt->execute();
        $result = $stmt->get_result();
        $utilisateur_modif = $result->fetch_assoc();
    } catch (mysqli_sql_exception $e) {
        $message = "<div class='alert alert-danger'>" . htmlspecialchars($e->getMessage()) . "</div>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Bibliothèque</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html {
            scroll-behavior: smooth;
        }
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
            padding-left: 250px; /* Espace pour la sidebar */
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100%;
            background-color: #2c3e50;
            padding-top: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }
        .sidebar h3 {
            color: #fff;
            text-align: center;
            margin-bottom: 30px;
            font-size: 1.5rem;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            margin: 15px 0;
        }
        .sidebar ul li a {
            color: #fff;
            display: block;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 1.1rem;
            transition: background-color 0.3s;
        }
        .sidebar ul li a:hover {
            background-color: #34495e;
            border-radius: 5px;
        }
        .container {
            max-width: 1200px;
            margin-top: 20px;
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
        }
        h2 {
            color: #34495e;
            margin-top: 30px;
            margin-bottom: 20px;
        }
        section {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            background-color: #fff;
            padding: 20px;
        }
        .section-livre-form, .section-utilisateur-form, .section-emprunt-form {
            background-color: #f1f8ff;
        }
        .section-recherche-livre, .section-recherche-utilisateur {
            background-color: #f4f4f4;
        }
        .section-liste-livres, .section-liste-utilisateurs, .section-liste-emprunts {
            background-color: #ffffff;
        }
        .table {
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
        }
        .table th {
            background-color: #34495e;
            color: #fff;
        }
        .btn-primary {
            background-color: #2c3e50;
            border-color: #2c3e50;
        }
        .btn-primary:hover {
            background-color: #1a252f;
            border-color: #1a252f;
        }
        .btn-danger {
            background-color: #e74c3c;
            border-color: #e74c3c;
        }
        .btn-danger:hover {
            background-color: #c0392b;
            border-color: #c0392b;
        }
        .form-control:focus {
            border-color: #2c3e50;
            box-shadow: 0 0 5px rgba(44, 62, 80, 0.3);
        }
        .alert {
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar">
        <h3>Bibliothèque</h3>
        <ul>
            <li><a href="#livres">Livres</a></li>
            <li><a href="#utilisateurs">Utilisateurs</a></li>
            <li><a href="#emprunts">Emprunts</a></li>
        </ul>
    </nav>

    <div class="container">
        <h1>Gestion de Bibliothèque</h1>

        <!-- Afficher les messages -->
        <?php echo $message; ?>

        <!-- Section Livres -->
        <section id="livres">
            <!-- Formulaire pour ajouter/modifier un livre -->
            <section class="section-livre-form">
                <h2><?php echo $livre_modif ? 'Modifier un livre' : 'Ajouter un livre'; ?></h2>
                <form method="post">
                    <?php if ($livre_modif): ?>
                        <input type="hidden" name="id_livre" value="<?php echo $livre_modif['id_livre']; ?>">
                        <input type="hidden" name="modifier_livre" value="1">
                    <?php else: ?>
                        <input type="hidden" name="ajouter_livre" value="1">
                    <?php endif; ?>
                    <div class="mb-3">
                        <input type="text" name="titre" class="form-control" placeholder="Titre" value="<?php echo $livre_modif ? htmlspecialchars($livre_modif['titre']) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="auteur" class="form-control" placeholder="Auteur" value="<?php echo $livre_modif ? htmlspecialchars($livre_modif['auteur']) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <input type="number" name="annee" class="form-control" placeholder="Année" value="<?php echo $livre_modif ? $livre_modif['annee'] : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="genre" class="form-control" placeholder="Genre" value="<?php echo $livre_modif ? htmlspecialchars($livre_modif['genre']) : ''; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary"><?php echo $livre_modif ? 'Modifier' : 'Ajouter'; ?></button>
                </form>
            </section>

            <!-- Recherche livre -->
            <section class="section-recherche-livre">
                <h2>Rechercher un livre</h2>
                <form method="get" class="d-flex">
                    <input type="text" name="recherche_livre" class="form-control me-2" placeholder="Titre ou auteur" value="<?php echo htmlspecialchars($recherche_livre); ?>">
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                </form>
            </section>

            <!-- Liste des livres -->
            <h2>Liste des livres</h2>
            <section class="section-liste-livres">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Auteur</th>
                            <th>Année</th>
                            <th>Genre</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($livres as $livre): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($livre['titre']); ?></td>
                                <td><?php echo htmlspecialchars($livre['auteur']); ?></td>
                                <td><?php echo $livre['annee']; ?></td>
                                <td><?php echo htmlspecialchars($livre['genre']); ?></td>
                                <td>
                                    <a href="?modifier_livre=<?php echo $livre['id_livre']; ?>" class="btn btn-sm btn-primary">Modifier</a>
                                    <a href="?supprimer_livre=<?php echo $livre['id_livre']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression ?');">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </section>

        <!-- Section Utilisateurs -->
        <section id="utilisateurs">
            <!-- Formulaire pour ajouter/modifier un utilisateur -->
            <section class="section-utilisateur-form">
                <h2><?php echo $utilisateur_modif ? 'Modifier un utilisateur' : 'Ajouter un utilisateur'; ?></h2>
                <form method="post">
                    <?php if ($utilisateur_modif): ?>
                        <input type="hidden" name="id_utilisateur" value="<?php echo $utilisateur_modif['id_utilisateur']; ?>">
                        <input type="hidden" name="modifier_utilisateur" value="1">
                    <?php else: ?>
                        <input type="hidden" name="ajouter_utilisateur" value="1">
                    <?php endif; ?>
                    <div class="mb-3">
                        <input type="text" name="nom" class="form-control" placeholder="Nom" value="<?php echo $utilisateur_modif ? htmlspecialchars($utilisateur_modif['nom']) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="prenom" class="form-control" placeholder="Prénom" value="<?php echo $utilisateur_modif ? htmlspecialchars($utilisateur_modif['prenom']) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" value="<?php echo $utilisateur_modif ? htmlspecialchars($utilisateur_modif['email']) : ''; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary"><?php echo $utilisateur_modif ? 'Modifier' : 'Ajouter'; ?></button>
                </form>
            </section>

            <!-- Recherche utilisateur -->
            <section class="section-recherche-utilisateur">
                <h2>Rechercher un utilisateur</h2>
                <form method="get" class="d-flex">
                    <input type="text" name="recherche_utilisateur" class="form-control me-2" placeholder="Nom" value="<?php echo htmlspecialchars($recherche_utilisateur); ?>">
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                </form>
            </section>

            <!-- Liste des utilisateurs -->
            <h2>Liste des utilisateurs</h2>
            <section class="section-liste-utilisateurs">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($utilisateurs as $utilisateur): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($utilisateur['nom']); ?></td>
                                <td><?php echo htmlspecialchars($utilisateur['prenom']); ?></td>
                                <td><?php echo htmlspecialchars($utilisateur['email']); ?></td>
                                <td>
                                    <a href="?modifier_utilisateur=<?php echo $utilisateur['id_utilisateur']; ?>" class="btn btn-sm btn-primary">Modifier</a>
                                    <a href="?supprimer_utilisateur=<?php echo $utilisateur['id_utilisateur']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression ?');">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </section>

        <!-- Section Emprunts -->
        <section id="emprunts">
            <!-- Formulaire pour ajouter un emprunt -->
            <section class="section-emprunt-form">
                <h2>Ajouter un emprunt</h2>
                <form method="post">
                    <input type="hidden" name="ajouter_emprunt" value="1">
                    <div class="mb-3">
                        <select name="id_livre" class="form-control" required>
                            <option value="">Sélectionner un livre</option>
                            <?php foreach ($livres as $livre): ?>
                                <option value="<?php echo $livre['id_livre']; ?>">
                                    <?php echo htmlspecialchars($livre['titre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <select name="id_utilisateur" class="form-control" required>
                            <option value="">Sélectionner un utilisateur</option>
                            <?php foreach ($utilisateurs as $utilisateur): ?>
                                <option value="<?php echo $utilisateur['id_utilisateur']; ?>">
                                    <?php echo htmlspecialchars($utilisateur['nom'] . ' ' . $utilisateur['prenom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </form>
            </section>

            <!-- Liste des emprunts -->
            <h2>Liste des emprunts</h2>
            <section class="section-liste-emprunts">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Titre du livre</th>
                            <th>Utilisateur</th>
                            <th>Date d'emprunt</th>
                            <th>Date de retour</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($emprunts as $emprunt): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($emprunt['titre']); ?></td>
                                <td><?php echo htmlspecialchars($emprunt['nom'] . ' ' . $emprunt['prenom']); ?></td>
                                <td><?php echo $emprunt['date_emprunt']; ?></td>
                                <td><?php echo $emprunt['date_retour'] ?: 'Non retourné'; ?></td>
                                <td>
                                    <a href="?supprimer_emprunt=<?php echo $emprunt['id_emprunt']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer le retour ?');">Retour</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </section>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
// Fermer la connexion
$mysqli->close();
?>