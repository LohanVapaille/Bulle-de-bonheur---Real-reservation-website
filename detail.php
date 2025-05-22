<?php
require 'config.php';

if (isset($_GET['id'])) {
    $id_creneau = $_GET['id'];
    // Tu peux maintenant utiliser $id_creneau comme tu veux
}

    // Récupération des joueurs ajoutés par les users
    $stmt = $pdo->query("SELECT * FROM crenaux WHERE id_crenaux = " . $id_creneau );
    $detail = $stmt->fetch(PDO::FETCH_ASSOC);

    $dateBrute = $detail['jour'];

    // Créer un objet DateTime
    $dateObj = new DateTime($dateBrute);

    // Formater la date en français
    $formatter = new IntlDateFormatter(
        'fr_FR',
        IntlDateFormatter::FULL,
        IntlDateFormatter::NONE,
        'Europe/Paris',
        IntlDateFormatter::GREGORIAN,
        'EEEE dd MMMM yyyy'
    );

    // Appliquer le formatage
    $dateFormatee = $formatter->format($dateObj);

    // Mettre la première lettre de chaque mot en majuscule
    $dateFormatee = mb_convert_case($dateFormatee, MB_CASE_TITLE, "UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données envoyées par le formulaire
    $id_creneau = (int)$_POST['id_creneau'];
    $nom = trim($_POST['nom']);
    $tel = trim($_POST['tel']);
    $text = trim($_POST['text']);

    // Simple validation (tu peux l'améliorer)
    if ($id_creneau > 0 && $nom !== '' && $tel !== '' && $text !== '') {

        // Préparation et exécution de la requête d'insertion
        $stmt = $pdo->prepare("INSERT INTO demandes (id_crenau, nom, tel, text) VALUES (?, ?, ?, ?)");
        $success = $stmt->execute([$id_creneau, $nom, $tel, $text]);

        if ($success) {
            echo "<script>
        document.addEventListener('DOMContentLoaded', function () {
            const popup = document.getElementById('popup-success');
            if (popup) {
                popup.classList.remove('hidden');
            }
        });
    </script>";
        } else {
            echo "<p>Erreur lors de l'enregistrement.</p>";
        }

    } else {
        echo "<p>Veuillez remplir tous les champs correctement.</p>";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserver | Bulle de Bonheur</title>
    <link rel="stylesheet" href="always/header.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DynaPuff:wght@400..700&family=Titan+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/styles_crenaux.css">

    <style>
        .section{
            display:flex;
            flex-direction:column;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
        }

        h1{
    text-align:center;}

        .submit{
            background-color:#c7f8b0;
        }
    </style>
</head>

<body>
<?php include 'always/header.html'; ?>
<div class='success hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4' id="popup-success">
    <strong class="font-bold">Succès !</strong>
    <span class="block sm:inline">Votre demande a bien été enregistrée. Attention : Une demande n'est pas synonyme de réservation. Attendez une confirmation de notre part pour pouvoir ensuite passer à l'étape de paiment.</span>
</div>

    <main class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="section bg-white rounded-xl p-8 form-shadow">
            <h1 style='margin:auto; margin-bottom:15px'class="text-3xl font-bold text-green-800 mb-2 header-font">Plage horaire du <?php echo $dateFormatee?></h1>
            
            <div class="mb-8">
                <div class="flex items-center mb-4">
                    <div class="bg-green-100 p-3 rounded-full mr-3">
                        <i class="fas fa-calendar-day text-green-600 text-xl"></i>
                    </div>
                    <p class="text-gray-700">
                        Une plage horaire est disponible de <?php echo $detail['heure_debut']?>h à <?php echo $detail['heure_fin']?>h. 
                        Sachez que vous pouvez réserver pour une durée maximum de 4 heures d'affilée.
                    </p>
                </div>
                
                <div class="flex items-center mb-4">
                    <div class="bg-green-100 p-3 rounded-full mr-3">
                        <i class="fas fa-info-circle text-green-600 text-xl"></i>
                    </div>
                    <p class="text-gray-700">
                        Si vous souhaitez réserver un créneau sur cette plage horaire, remplissez le formulaire et nous vous répondrons dans les plus brefs délais.
                    </p>
                </div>
                <div class="flex items-center">
                    <div class="bg-red-100 p-3 rounded-full mr-3">
                        <i class="fas fa-warning text-red-600 text-xl"></i>
                    </div>
                    <p class="text-gray-700">
                        Veuillez respecter les <a href="#">conditions générales d'utilisation</a>. En cas de non-respect, l'accès au site vous sera restreint (politesse, respect, pas de spam,...)
                    </p>
                </div>
            </div>

            <form method="post" action="" class="space-y-6">
                <input type="hidden" name="id_creneau" value="<?= htmlspecialchars($id_creneau) ?>">
                
                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Prénom & Nom</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input type="text" id="nom" name="nom" required 
                            class="pl-10 w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-green-500 input-focus transition duration-200"
                            placeholder="Paul Dupont">
                    </div>
                </div>
                
                <div>
                    <label for="tel" class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-phone text-gray-400"></i>
                        </div>
                        <input type="number" id="tel" name="tel" required 
                            class="pl-10 w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-green-500 input-focus transition duration-200"
                            placeholder="06 10 10 10 10">
                    </div>
                </div>
                
                <div>
                    <label for="text" class="block text-sm font-medium text-gray-700 mb-1">En respectant la plage horaire donnée, veuillez préciser le créneau souhaité (4 heures max)</label>
                    <div class="relative">
                        <div class="absolute top-3 left-3">
                            <i class="fas fa-comment-dots text-gray-400"></i>
                        </div>
                        <textarea id="text" name="text" required rows="4"
                            class="pl-10 w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-green-500 input-focus transition duration-200"
                            placeholder="Votre message..."></textarea>
                    </div>
                </div>
                
                <div class="pt-2">
                    <button type="submit" 
                            class="submit w-full gradient-bg font-bold py-3 px-4 rounded-lg hover:opacity-90 transition duration-200 flex items-center justify-center">
                        <i class="fas fa-paper-plane mr-2"></i> Envoyer la demande
                    </button>
                </div>
            </form>
        </div>
    </main>

    <?php include 'always/footer.html'; ?>
</body>