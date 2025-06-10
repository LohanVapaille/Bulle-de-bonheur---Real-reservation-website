<?php
require_once 'config.php';

// Obtenir la date d'aujourd'hui
$aujourdhui = date('Y-m-d');

$stmt = $pdo->prepare("
    DELETE FROM demandes 
    WHERE id_crenau IN (
        SELECT id_temp FROM (
            SELECT id_crenaux AS id_temp FROM crenaux WHERE jour < :aujourdhui
        ) AS temp
    )
");

$stmt->execute(['aujourdhui' => $aujourdhui]);

// 2. Supprimer les créneaux passés
$stmt = $pdo->prepare("DELETE FROM crenaux WHERE jour < :aujourdhui");
$stmt->execute(['aujourdhui' => $aujourdhui]);


$stmt = $pdo->query("SELECT * FROM crenaux ORDER BY jour, heure_debut");
$crenaux = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Les plages horaires actuellement disponibles sont renseignées sur cette page. Vous pouvez les consulter et demander un créneau selon vos envies. Envie de détente à la campagne ? Profitez d’un moment de relaxation avec jacuzzi, jardin, terrain de pétanque, badminton et tennis de table à Chauffry.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réserver | Bulle de Bonheur</title>
<?php include 'always/head.html'; ?>
    <link rel="stylesheet" href="styles/styles_crenaux.css">
    <link rel="stylesheet" href="always/styles_crenaux.css">
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body>
<?php include 'always/header.php'; ?>

  <div class="boutons">
    
    <button class="filtre day" onclick="filtrerAujourdhui(this)">Aujourd'hui</button>
    <button class="filtre week" onclick="filtrerSemaine(this)">Cette semaine</button>
    <button class="filtre mois" onclick="filtrerMois(this)">Ce mois-ci</button>
    <button class="filtre dispo" onclick="filtrerDisponibles(this)">Disponibles</button>
    <button class="filtre reset" onclick="resetFiltres(this)">Sans filtres</button>



    
  


</div>
<main>

        

<div class='crenaux'>
    <?php foreach ($crenaux as $creneau): 
    $dateBrute = $creneau['jour'];

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
?>
        <div class="onecreneau fadeInUp " data-etat="<?php echo $creneau['etat']?>" data-jour="<?= $creneau['jour'] ?>" >
            
            <div class='infos'>
                <div class='left'>
                    
                    <div>
    <?php if ($creneau['etat'] === 'dispo'): ?>
        <p class='etat'>Plage horaire disponible</p>
        
    <?php else: ?>
        <p class='etat' style='background-color:red;opacity: 0.6;'>Réservé</p>
    <?php endif; ?>
</div>

                    <p class='day'><?= htmlspecialchars($dateFormatee) ?></p>
                    <p class='horaire'> De <?= htmlspecialchars($creneau['heure_debut']) ?>h  à 
                    <?= htmlspecialchars($creneau['heure_fin']) ?>h</p>
                </div>

           </div>

    <?php if ($creneau['etat'] === 'dispo'): ?>
        <div class='good reserve'data-id="<?php echo $creneau['id_crenaux']?>">
    <a>Demander</a>
</div>
        
    <?php else: ?>
                    <div class='reserve' style='background-color:red; color:white; cursor:not-allowed; opacity: 0.6;'>
    <p>Indisponible</p>
</div>
    <?php endif; ?>
</div>
        
        
    <?php endforeach; ?>

</div>
    </main>
    <?php include 'always/footer.html'; ?>


  <script>
  
  document.querySelectorAll('.good').forEach(card => {
  card.addEventListener("click", () => {
    const idcreneau = card.dataset.id;
    window.location.href = `detail.php?id=${idcreneau}`;
  });

  card.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
      const teamId = card.dataset.id;
      window.location.href = `team.php?id=${teamId}`;
    }
  });
});

function activerBouton(clique) {
    // Réinitialise tous les boutons
    document.querySelectorAll('.filtre').forEach(btn => {
        btn.style.backgroundColor = '';
        btn.style.color = '';
    });

    // Active le bouton cliqué
    clique.style.backgroundColor = 'green';
    clique.style.color = 'white';
}


function filtrerDisponibles(btn) {
    activerBouton(btn);
    document.querySelectorAll('.onecreneau').forEach(item => {
        const etat = item.dataset.etat;
        item.style.display = (etat === 'dispo') ? '' : 'none';
    });
}


function filtrerAujourdhui(btn) {
    activerBouton(btn);
    const maintenant = new Date();
    const annee = maintenant.getFullYear();
    const mois = String(maintenant.getMonth() + 1).padStart(2, '0');
    const jour = String(maintenant.getDate()).padStart(2, '0');
    const aujourdHui = `${annee}-${mois}-${jour}`;

    document.querySelectorAll('.onecreneau').forEach(item => {
        const jourCreneau = item.dataset.jour;
        item.style.display = (jourCreneau === aujourdHui) ? '' : 'none';
    });
}



function filtrerSemaine(btn) {
    activerBouton(btn);
    const now = new Date();

    // Ajuster pour que la semaine commence le lundi
    const jourActuel = now.getDay(); // 0 (dimanche) à 6 (samedi)
    const decalageLundi = jourActuel === 0 ? -6 : 1 - jourActuel;

    const debutSemaine = new Date(now);
    debutSemaine.setDate(now.getDate() + decalageLundi);

    const finSemaine = new Date(debutSemaine);
    finSemaine.setDate(debutSemaine.getDate() + 6);

    // Formater en YYYY-MM-DD
    const formatDate = d => d.toISOString().split('T')[0];
    const debut = formatDate(debutSemaine);
    const fin = formatDate(finSemaine);

    document.querySelectorAll('.onecreneau').forEach(item => {
        const jour = item.dataset.jour;
        item.style.display = (jour >= debut && jour <= fin) ? '' : 'none';
    });
}



function filtrerMois(btn) {
    activerBouton(btn);
    const now = new Date();
    const moisActuel = now.getMonth();
    const anneeActuelle = now.getFullYear();

    document.querySelectorAll('.onecreneau').forEach(item => {
        const jour = new Date(item.dataset.jour);
        const mois = jour.getMonth();
        const annee = jour.getFullYear();
        item.style.display = (mois === moisActuel && annee === anneeActuelle) ? '' : 'none';
    });
}

function resetFiltres(btn) {
    activerBouton(btn); // Optionnel : pour garder un style actif

    // Réaffiche tous les créneaux
    document.querySelectorAll('.onecreneau').forEach(item => {
        item.style.display = '';
    });
}

document.addEventListener("DOMContentLoaded", () => {
    const boutonMois = document.querySelector(".filtre.mois");
    if (boutonMois) {
        filtrerMois(boutonMois);
    }
});





</script>  
</body>
</html>