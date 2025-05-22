<?php
require_once 'config.php';

$stmt = $pdo->query("SELECT * FROM crenaux");
$crenaux = $stmt->fetchAll(PDO::FETCH_ASSOC);


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
    <link rel="stylesheet" href="always/styles_crenaux.css">

</head>

<body>
<?php include 'always/header.html'; ?>

  <div class="boutons">
    <button class="filtre reset" onclick="resetFiltres(this)">Sans filtres</button>
    <button class="filtre dispo" onclick="filtrerDisponibles(this)">Disponibles</button>
    <button class="filtre day" onclick="filtrerAujourdhui(this)">Aujourd'hui</button>
    <button class="filtre week" onclick="filtrerSemaine(this)">Cette semaine</button>
    <button class="filtre mois" onclick="filtrerMois(this)">Ce mois-ci</button>



    
  


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
        <div class="onecreneau" data-etat="<?php echo $creneau['etat']?>" data-jour="<?= $creneau['jour'] ?>" >
            
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

            <div class='right'>
                <p> <?= htmlspecialchars($creneau['heure_debut']) ?>h</p>
            </div></div>

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
    const debutSemaine = new Date(now);
    debutSemaine.setDate(now.getDate() - now.getDay());
    const finSemaine = new Date(debutSemaine);
    finSemaine.setDate(debutSemaine.getDate() + 6);

    document.querySelectorAll('.onecreneau').forEach(item => {
        const jour = new Date(item.dataset.jour);
        item.style.display = (jour >= debutSemaine && jour <= finSemaine) ? '' : 'none';
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

window.addEventListener('DOMContentLoaded', () => {
    const boutonReset = document.querySelector('.filtre.reset');
    resetFiltres(boutonReset);
});



</script>  
</body>
</html>