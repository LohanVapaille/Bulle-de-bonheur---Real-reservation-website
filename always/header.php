<?php
$current = basename($_SERVER['PHP_SELF']); // index.php, creneaux.php, etc.
?>

<header>
  <nav class="menu">
    <ul>
      <li class="<?= $current === 'index.php' ? 'active' : '' ?>"><a href="index.php">Accueil</a></li>
      <li class="<?= $current === 'creneaux.php' ? 'active' : '' ?>"><a href="creneaux.php">Disponibilités</a></li>
      <li class="<?= $current === 'tarifs.php' ? 'active' : '' ?>"><a href="tarifs.php">Prestations & Tarifs</a></li>
      <li class="<?= $current === 'adresse.php' ? 'active' : '' ?>"><a href="adresse.php">Adresse & Contact</a></li>
    </ul>
  </nav>

  <div class="btn-bg">
    <i class="barres fa-solid fa-bars"></i>
  </div>

  <nav class="menu-bg">
    <i class='back bx  bx-x' style="animation-delay: 0.1s;"></i>
    <ul>
      
      <li class="fadeInUp-invers <?= $current === 'index.php' ? 'active' : '' ?>" style="animation-delay: 0.1s;"><a href="index.php">Accueil</a></li>
      <li class="fadeInUp-invers <?= $current === 'creneaux.php' ? 'active' : '' ?>" style="animation-delay: 0.1s;"><a href="creneaux.php">Disponibilités</a></li>
      <li class="fadeInUp-invers <?= $current === 'tarifs.php' ? 'active' : '' ?>" style="animation-delay: 0.1s;"><a href="tarifs.php">Prestations & Tarifs</a></li>
      <li class="fadeInUp-invers <?= $current === 'adresse.php' ? 'active' : '' ?>" style="animation-delay: 0.1s;"><a href="adresse.php">Adresse & Contact</a></li>
      
    </ul>
  </nav>
</header>

<script src="always/header.js"></script>
