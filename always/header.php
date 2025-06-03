<?php
$current = basename($_SERVER['PHP_SELF']); // index.php, creneaux.php, etc.
?>

<header>
  <nav class="menu">
    <ul>
      <li class="<?= $current === 'index.php' ? 'active' : '' ?>"><a href="index.php">Accueil</a></li>
      <li class="<?= $current === 'creneaux.php' ? 'active' : '' ?>"><a href="creneaux.php">Disponibilités</a></li>
      <li class="<?= $current === 'tarifs.php' ? 'active' : '' ?>"><a href="tarifs.php">Prestations & Tarifs</a></li>
      <li><a href="#footer">Contact</a></li>
    </ul>
  </nav>

  <div class="btn-bg">
    <i class="barres fa-solid fa-bars"></i>
  </div>

  <nav class="menu-bg">
    <ul>
      <li class="fadeInUp-invers <?= $current === 'index.php' ? 'active' : '' ?>" style="animation-delay: 0s;"><a href="#"></a></li>
      <li class="fadeInUp-invers <?= $current === 'index.php' ? 'active' : '' ?>" style="animation-delay: 0.1s;"><a href="index.php">Accueil</a></li>
      <li class="fadeInUp-invers <?= $current === 'creneaux.php' ? 'active' : '' ?>" style="animation-delay: 0.2s;"><a href="creneaux.php">Disponibilités</a></li>
      <li class="fadeInUp-invers <?= $current === 'tarifs.php' ? 'active' : '' ?>" style="animation-delay: 0.3s;"><a href="tarifs.php">Prestations & Tarifs</a></li>
      <li class="fadeInUp-invers" style="animation-delay: 0.4s;"><a href="#footer">Lieu</a></li>
      <li class='fadeInUp-invers'><a href="#footer" style="animation-delay: 0.5s;">Contact</a></li>
      <li class="fadeInUp-invers back"><a href="#"style="animation-delay: 0.6s;">Fermer le menu</a></li>
      <li class="fadeInUp-invers logo"><img src="img/logobdb.png" alt=""></li>
    </ul>
  </nav>
</header>

<script src="always/header.js"></script>
