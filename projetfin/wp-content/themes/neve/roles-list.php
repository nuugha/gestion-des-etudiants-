<?php
// Inclure WordPress
require_once('E:/xampp/htdocs/projetfin/wp-load.php');


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Rôles WordPress</title>
    <style>
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
            border: 1px solid #ddd;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #1e3a8a;
            color: white;
        }
        td {
            background-color: #f3f4f6;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center; color: #1e3a8a;">Liste des Rôles WordPress</h1>
    <?php
    // Récupérer et afficher les rôles
    global $wp_roles;
    if (!isset($wp_roles)) {
        $wp_roles = new WP_Roles();
    }

    echo '<table>';
    echo '<tr><th>Nom affiché</th><th>Slug (identifiant)</th><th>Capacités associées</th></tr>';
    foreach ($wp_roles->roles as $role_slug => $role_details) {
        echo '<tr>';
        echo '<td>' . esc_html($role_details['name']) . '</td>'; // Nom affiché
        echo '<td>' . esc_html($role_slug) . '</td>'; // Slug du rôle
        echo '<td><pre>' . print_r($role_details['capabilities'], true) . '</pre></td>'; // Capacités
        echo '</tr>';
    }
    echo '</table>';
    ?>
</body>
</html>
