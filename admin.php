<?php
session_start();
require 'config.php';

// Suppression de demande (AVANT TOUT HTML)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_message'])) {
    $id = (int) $_POST['id_message'];
    $stmt = $pdo->prepare('DELETE FROM demandes WHERE id_message = ?');
    $stmt->execute([$id]);
    header('Location: admin.php');
    exit;
}

// Déconnexion
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

// Vérification mot de passe
if (isset($_POST['password'])) {
    if ($_POST['password'] === ADMIN_PASSWORD) {
        $_SESSION['admin'] = true;
        header('Location: admin.php');
        exit;
    } else {
        $error = "Mot de passe incorrect.";
    }
}

// Si connecté en admin
if (!empty($_SESSION['admin'])) {
    // Suppression créneau
    if (isset($_GET['delete'])) {
        $stmt = $pdo->prepare("DELETE FROM crenaux WHERE id_crenaux = ?");
        $stmt->execute([$_GET['delete']]);
        header('Location: admin.php');
        exit;
    }

    // Modification
    if (isset($_POST['update'])) {
        $stmt = $pdo->prepare("UPDATE crenaux SET jour = ?, heure_debut = ?, heure_fin = ?, etat = ? WHERE id_crenaux = ?");
        $stmt->execute([
            $_POST['jour'],
            $_POST['heure_debut'],
            $_POST['heure_fin'],
            $_POST['etat'],
            $_POST['id']
        ]);
    }

    // Ajout
    if (isset($_POST['add'])) {
        $stmt = $pdo->prepare("INSERT INTO crenaux (jour, heure_debut, heure_fin, etat) VALUES (?, ?, ?, 'dispo')");
        $stmt->execute([
            $_POST['jour'],
            $_POST['heure_debut'],
            $_POST['heure_fin']
        ]);
    }

    // Récupération des créneaux
    $crenaux = $pdo->query("SELECT * FROM crenaux ORDER BY jour, heure_debut")->fetchAll();

    // Récupération des réservations
    $sql = "SELECT 
                d.id_message,
                d.nom,
                d.tel,
                d.text,
                d.id_crenau,
                c.jour,
                c.heure_debut,
                c.heure_fin
            FROM demandes d
            JOIN crenaux c ON d.id_crenau = c.id_crenaux
            ORDER BY c.jour ASC, c.heure_debut ASC";
    $reservations = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Privé | Bulle de Bonheur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="img/logobdb.png" type="image/x-icon">
    <link rel="stylesheet" href="always/header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            
            background-color: #f0fdf4;
        }
        .password-toggle {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        header{
    background-color:rgb(22 163 74);
}
        
        .password-toggle:hover {
            opacity: 0.8;
        }
        
        .admin-form {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .creneau-form {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .creneau-form:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary {
            background-color: #10b981;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: #059669;
        }
        
        .btn-danger {
            background-color: #ef4444;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-danger:hover {
            background-color: #dc2626;
        }
        
        .btn-secondary {
            background-color: #3b82f6;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background-color: #2563eb;
        }
        
        .fadeInUp {
            animation: fadeInUp 0.5s ease forwards;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    
        
        .logout-btn {
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            color: #ef4444;
        }
        
        input:focus, select:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
        }
        
        .reservation-row:hover {
            background-color: #ecfdf5;
        }
    </style>
</head>
<body class="min-h-screen">
<?php include 'always/header.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <?php if (empty($_SESSION['admin'])): ?>
            <section class="admin fadeInUp max-w-md mx-auto admin-form p-8">
                <div class="text-center mb-6">
                    <h1 class="text-2xl font-bold text-green-800 mb-2"><i class="fas fa-lock mr-2"></i>Espace administrateur</h1>
                    <p class="text-gray-600">Entrez le mot de passe pour accéder aux fonctionnalités d'administration</p>
                </div>
                
                <?php if (!empty($error)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <?= $error ?>
                    </div>
                <?php endif; ?>
                
                <form method="post" class="space-y-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                        <div class="relative">
                            <input 
                                id="password" 
                                type="password" 
                                name="password" 
                                placeholder="Entrez votre mot de passe" 
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            >
                            <button type="button" id="togglePassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                                <i class="far fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full btn-primary py-2 px-4 rounded-md font-medium">
                        <i class="fas fa-sign-in-alt mr-2"></i>Se connecter
                    </button>
                </form>
            </section>
        <?php else: ?>
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-green-800"><i class="fas fa-tools mr-2"></i>Panneau d'administration</h1>
                <a href="?logout=true" class="logout-btn text-green-600 hover:text-red-500">
                    <i class="fas fa-sign-out-alt mr-1"></i>Déconnexion
                </a>
            </div>
            
            <div class="grid gap-8">
                <div>

                <div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-green-700 mb-4">
            <i class="fas fa-calendar-check mr-2"></i>Réservations
        </h2>
        
        <?php if (count($reservations) > 0): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-green-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">Nom</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">Téléphone</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">Message</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">Jour</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">Heure</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($reservations as $res): ?>
                            <tr class="reservation-row">
                                <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars($res['id_message']) ?></td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900"><?= htmlspecialchars($res['nom']) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars($res['tel']) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars($res['text']) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars($res['jour']) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <span class="bg-green-100 text-green-800 py-1 px-2 rounded-full text-xs">
                                        <?= htmlspecialchars($res['heure_debut']) ?>h → <?= htmlspecialchars($res['heure_fin']) ?>h
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <form method="POST" action="" onsubmit="return confirm('Confirmer la suppression ?');">
                                        <input type="hidden" name="id_message" value="<?= $res['id_message'] ?>">
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                            Supprimer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-8 text-gray-500">
                <i class="far fa-calendar-times text-4xl mb-2"></i>
                <p>Aucune réservation trouvée</p>
            </div>
        <?php endif; ?>
    </div>
</div>

                    <div class="bg-white rounded-lg shadow-md p-6 mb-8 mt-8" >
                        <h2 class="text-xl font-semibold text-green-700 mb-4"><i class="fas fa-plus-circle mr-2"></i>Ajouter un créneau</h2>
                        <form method="post" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jour</label>
                                <input 
                                    type="date" 
                                    name="jour" 
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                >
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Heure de début</label>
                                    <select 
                                        name="heure_debut"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    >
                                        <?php for ($i = 0; $i < 24; $i++): ?>
                                            <option value="<?= $i ?>"><?= $i ?>h</option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Heure de fin</label>
                                    <select 
                                        name="heure_fin"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    >
                                        <?php for ($i = 1; $i <= 24; $i++): ?>
                                            <option value="<?= $i ?>"><?= $i ?>h</option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <button 
                                type="submit" 
                                name="add"
                                class="w-full btn-primary py-2 px-4 rounded-md font-medium"
                            >
                                <i class="fas fa-save mr-2"></i>Ajouter le créneau
                            </button>
                        </form>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-green-700 mb-4"><i class="fas fa-edit mr-2"></i>Gérer les créneaux existants</h2>
                        
                        <?php if (count($crenaux) > 0): ?>
                            <div class="space-y-4">
                                <?php foreach ($crenaux as $creneau): ?>
                                    <form method="post" class="creneau-form p-4 border border-gray-200 rounded-lg">
                                        <input type="hidden" name="id" value="<?= $creneau['id_crenaux'] ?>">
                                        
                                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Jour</label>
                                                <input 
                                                    type="date" 
                                                    name="jour" 
                                                    value="<?= $creneau['jour'] ?>"
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                >
                                            </div>
                                            
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">État</label>
                                                <select 
                                                    name="etat"
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                >
                                                    <option value="dispo" <?= $creneau['etat'] === 'dispo' ? 'selected' : '' ?>>Disponible</option>
                                                    <option value="nodispo" <?= $creneau['etat'] === 'nodispo' ? 'selected' : '' ?>>Non disponible</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Début</label>
                                                <select 
                                                    name="heure_debut"
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                >
                                                    <?php for ($i = 0; $i < 24; $i++): ?>
                                                        <option value="<?= $i ?>" <?= ($i == $creneau['heure_debut']) ? 'selected' : '' ?>><?= $i ?>h</option>
                                                    <?php endfor; ?>
                                                </select>
                                            </div>
                                            
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Fin</label>
                                                <select 
                                                    name="heure_fin"
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                >
                                                    <?php for ($i = 1; $i <= 24; $i++): ?>
                                                        <option value="<?= $i ?>" <?= ($i == $creneau['heure_fin']) ? 'selected' : '' ?>><?= $i ?>h</option>
                                                    <?php endfor; ?>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="flex justify-between">
                                            <button 
                                                type="submit" 
                                                name="update"
                                                class="btn-secondary py-2 px-4 rounded-md font-medium"
                                            >
                                                <i class="fas fa-sync-alt mr-2"></i>Modifier
                                            </button>
                                            
                                            <a 
                                                href="?delete=<?= $creneau['id_crenaux'] ?>" 
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce créneau ?')"
                                                class="btn-danger py-2 px-4 rounded-md font-medium inline-block"
                                            >
                                                <i class="fas fa-trash-alt mr-2"></i>Supprimer
                                            </a>
                                        </div>
                                    </form>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-8 text-gray-500">
                                <i class="far fa-calendar-times text-4xl mb-2"></i>
                                <p>Aucun créneau disponible</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        
        if (togglePassword) {
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                if (type === 'password') {
                    eyeIcon.classList.remove('fa-eye-slash');
                    eyeIcon.classList.add('fa-eye');
                } else {
                    eyeIcon.classList.remove('fa-eye');
                    eyeIcon.classList.add('fa-eye-slash');
                }
            });
        }
        
        // Add animation to elements
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.fadeInUp');
            elements.forEach((el, index) => {
                setTimeout(() => {
                    el.style.opacity = 1;
                    el.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>

<script>
    const passwordInput = document.querySelector('.password');
    const eyeOpen = document.querySelector('.ouvert');
    const eyeClosed = document.querySelector('.ferme');

    eyeOpen?.addEventListener('click', () => {
        passwordInput.type = 'text';
        eyeOpen.style.display = 'none';
        eyeClosed.style.display = 'inline';
    });

    eyeClosed?.addEventListener('click', () => {
        passwordInput.type = 'password';
        eyeOpen.style.display = 'inline';
        eyeClosed.style.display = 'none';
    });
</script>

</body>
</html>
