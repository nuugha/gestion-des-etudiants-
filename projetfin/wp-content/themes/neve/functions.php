<?php
/**
 * Neve functions.php file
 *
 * Author:          Andrei Baicus <andrei@themeisle.com>
 * Created on:      17/08/2018
 *
 * @package Neve
 */

define( 'NEVE_VERSION', '3.8.16' );
define( 'NEVE_INC_DIR', trailingslashit( get_template_directory() ) . 'inc/' );
define( 'NEVE_ASSETS_URL', trailingslashit( get_template_directory_uri() ) . 'assets/' );
define( 'NEVE_MAIN_DIR', get_template_directory() . '/' );
define( 'NEVE_BASENAME', basename( NEVE_MAIN_DIR ) );
define( 'NEVE_PLUGINS_DIR', plugin_dir_path( dirname( __DIR__ ) ) . 'plugins/' );

if ( ! defined( 'NEVE_DEBUG' ) ) {
	define( 'NEVE_DEBUG', false );
}
define( 'NEVE_NEW_DYNAMIC_STYLE', true );
/**
 * Buffer which holds errors during theme inititalization.
 *
 * @var WP_Error $_neve_bootstrap_errors
 */
global $_neve_bootstrap_errors;

$_neve_bootstrap_errors = new WP_Error();

if ( version_compare( PHP_VERSION, '7.0' ) < 0 ) {
	$_neve_bootstrap_errors->add(
		'minimum_php_version',
		sprintf(
		/* translators: %s message to upgrade PHP to the latest version */
			__( "Hey, we've noticed that you're running an outdated version of PHP which is no longer supported. Make sure your site is fast and secure, by %1\$s. Neve's minimal requirement is PHP%2\$s.", 'neve' ),
			sprintf(
			/* translators: %s message to upgrade PHP to the latest version */
				'<a href="https://wordpress.org/support/upgrade-php/">%s</a>',
				__( 'upgrading PHP to the latest version', 'neve' )
			),
			'7.0'
		)
	);
}
/**
 * A list of files to check for existance before bootstraping.
 *
 * @var array Files to check for existance.
 */

$_files_to_check = defined( 'NEVE_IGNORE_SOURCE_CHECK' ) ? [] : [
	NEVE_MAIN_DIR . 'vendor/autoload.php',
	NEVE_MAIN_DIR . 'style-main-new.css',
	NEVE_MAIN_DIR . 'assets/js/build/modern/frontend.js',
	NEVE_MAIN_DIR . 'assets/apps/dashboard/build/dashboard.js',
	NEVE_MAIN_DIR . 'assets/apps/customizer-controls/build/controls.js',
];
foreach ( $_files_to_check as $_file_to_check ) {
	if ( ! is_file( $_file_to_check ) ) {
		$_neve_bootstrap_errors->add(
			'build_missing',
			sprintf(
			/* translators: %s: commands to run the theme */
				__( 'You appear to be running the Neve theme from source code. Please finish installation by running %s.', 'neve' ), // phpcs:ignore WordPress.Security.EscapeOutput
				'<code>composer install --no-dev &amp;&amp; yarn install --frozen-lockfile &amp;&amp; yarn run build</code>'
			)
		);
		break;
	}
}
/**
 * Adds notice bootstraping errors.
 *
 * @internal
 * @global WP_Error $_neve_bootstrap_errors
 */
function _neve_bootstrap_errors() {
	global $_neve_bootstrap_errors;
	printf( '<div class="notice notice-error"><p>%1$s</p></div>', $_neve_bootstrap_errors->get_error_message() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

if ( $_neve_bootstrap_errors->has_errors() ) {
	/**
	 * Add notice for PHP upgrade.
	 */
	add_filter( 'template_include', '__return_null', 99 );
	switch_theme( WP_DEFAULT_THEME );
	unset( $_GET['activated'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	add_action( 'admin_notices', '_neve_bootstrap_errors' );

	return;
}

/**
 * Themeisle SDK filter.
 *
 * @param array $products products array.
 *
 * @return array
 */
function neve_filter_sdk( $products ) {
	$products[] = get_template_directory() . '/style.css';

	return $products;
}

add_filter( 'themeisle_sdk_products', 'neve_filter_sdk' );
add_filter(
	'themeisle_sdk_compatibilities/' . NEVE_BASENAME,
	function ( $compatibilities ) {

		$compatibilities['NevePro'] = [
			'basefile'  => defined( 'NEVE_PRO_BASEFILE' ) ? NEVE_PRO_BASEFILE : '',
			'required'  => '2.4',
			'tested_up' => '2.8',
		];

		return $compatibilities;
	}
);
require_once 'globals/migrations.php';
require_once 'globals/utilities.php';
require_once 'globals/hooks.php';
require_once 'globals/sanitize-functions.php';
require_once get_template_directory() . '/start.php';

/**
 * If the new widget editor is available,
 * we re-assign the widgets to hfg_footer
 */
if ( neve_is_new_widget_editor() ) {
	/**
	 * Re-assign the widgets to hfg_footer
	 *
	 * @param array  $section_args The section arguments.
	 * @param string $section_id The section ID.
	 * @param string $sidebar_id The sidebar ID.
	 *
	 * @return mixed
	 */
	function neve_customizer_custom_widget_areas( $section_args, $section_id, $sidebar_id ) {
		if ( strpos( $section_id, 'widgets-footer' ) ) {
			$section_args['panel'] = 'hfg_footer';
		}

		return $section_args;
	}

	add_filter( 'customizer_widgets_section_args', 'neve_customizer_custom_widget_areas', 10, 3 );
}

require_once get_template_directory() . '/header-footer-grid/loader.php';

add_filter(
	'neve_welcome_metadata',
	function() {
		return [
			'is_enabled' => ! defined( 'NEVE_PRO_VERSION' ),
			'pro_name'   => 'Neve Pro Addon',
			'logo'       => get_template_directory_uri() . '/assets/img/dashboard/logo.svg',
			'cta_link'   => tsdk_translate_link( tsdk_utmify( 'https://themeisle.com/themes/neve/upgrade/?discount=LOYALUSER582&dvalue=50', 'neve-welcome', 'notice' ), 'query' ),
		];
	}
);

add_filter( 'themeisle_sdk_enable_telemetry', '__return_true' );
function afficher_soumissions_wpforms() {
    global $wpdb;

    // Table où WPForms enregistre les données
    $table = $wpdb->prefix . 'wpforms_entries';

    // Récupération des soumissions
    $entries = $wpdb->get_results("SELECT * FROM $table ORDER BY date DESC");

    if ($entries) {
        // Commencer le tableau
        $output = '<table border="1" style="width: 100%; border-collapse: collapse; text-align: center;">';
        $output .= '<tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Données</th>
                    </tr>';

        // Boucler sur les soumissions
        foreach ($entries as $entry) {
            $fields = maybe_unserialize($entry->fields); // Déserialiser les données des champs
            $data = '';
            foreach ($fields as $key => $value) {
                $data .= "<strong>$key</strong>: $value<br>";
            }

            $output .= '<tr>';
            $output .= '<td>' . esc_html($entry->entry_id) . '</td>';
            $output .= '<td>' . esc_html($entry->date) . '</td>';
            $output .= '<td>' . $data . '</td>';
            $output .= '</tr>';
        }

        $output .= '</table>';
    } else {
        $output = '<p>Aucune soumission trouvée.</p>';
    }

    return $output;
}

add_shortcode('wpforms_table', 'afficher_soumissions_wpforms');
function afficher_toutes_les_soumissions() {
    global $wpdb;

    // Query to fetch Flamingo form entries and their metadata
    $results = $wpdb->get_results("
        SELECT pm.post_id, pm.meta_key, pm.meta_value
        FROM {$wpdb->prefix}postmeta AS pm
        INNER JOIN {$wpdb->prefix}posts AS p
        ON pm.post_id = p.ID
        WHERE p.post_type = 'flamingo_inbound'
        AND pm.meta_key IN ('_field_nom', '_field_prenom', '_field_datedb', '_field_datefn', '_field_raison')
        ORDER BY p.post_date DESC
    ");

    if ($results) {
        // Build a nested array of form submissions by post_id
        $entries = [];
        foreach ($results as $row) {
            $entries[$row->post_id][$row->meta_key] = $row->meta_value;
        }

        // Start the HTML table
        $output = '<table border="1" style="width: 100%; border-collapse: collapse; text-align: left;">';
        $output .= '<tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Date de début</th>
                        <th>Date de fin</th>
                        <th>Raison</th>
                    </tr>';

        // Populate the table rows with the retrieved data
        foreach ($entries as $entry) {
            $output .= '<tr>';
            $output .= '<td>' . esc_html($entry['_field_nom'] ?? '') . '</td>';
            $output .= '<td>' . esc_html($entry['_field_prenom'] ?? '') . '</td>';
            $output .= '<td>' . esc_html($entry['_field_datedb'] ?? '') . '</td>';
            $output .= '<td>' . esc_html($entry['_field_datefn'] ?? '') . '</td>';
            $output .= '<td>' . esc_html($entry['_field_raison'] ?? '') . '</td>';
            $output .= '</tr>';
        }

        $output .= '</table>';
    } else {
        // If no entries found
        $output = '<p>Aucune absence trouvée.</p>';
    }

    return $output;
}

// Create a shortcode to display the table of submissions


add_shortcode('liste_absences',  'afficher_toutes_les_soumissions');
// Fonction pour afficher toutes les notes enregistrées
function enregistrer_notes_etudiants($atts) {
    global $wpdb;

    // Retrieve all notes from Flamingo's database
    $results = $wpdb->get_results("
        SELECT pm.post_id, pm.meta_key, pm.meta_value
        FROM {$wpdb->prefix}postmeta AS pm
        INNER JOIN {$wpdb->prefix}posts AS p
        ON pm.post_id = p.ID
        WHERE p.post_type = 'flamingo_inbound'
        AND pm.meta_key IN ('_field_etudiant-id', '_field_matiere', '_field_note', '_field_commentaire')
        ORDER BY p.post_date DESC
    ");

    if (!empty($results)) {
        // Organize notes by ID étudiant
        $notes = [];
        foreach ($results as $row) {
            $notes[$row->post_id][$row->meta_key] = maybe_unserialize($row->meta_value); // Deserialize if needed
        }

        // Generate HTML table
        $output = '<table border="1" style="width: 100%; border-collapse: collapse; text-align: left;">';
        $output .= '<tr>
                        <th>ID Étudiant</th>
                        <th>Matière</th>
                        <th>Note</th>
                        <th>Commentaire</th>
                    </tr>';

        foreach ($notes as $note) {
            $output .= '<tr>';
            $output .= '<td>' . esc_html($note['_field_etudiant-id'] ?? '') . '</td>';
            $output .= '<td>' . esc_html(is_array($note['_field_matiere']) ? implode(', ', $note['_field_matiere']) : $note['_field_matiere']) . '</td>';
            $output .= '<td>' . esc_html($note['_field_note'] ?? '') . '</td>';
            $output .= '<td>' . esc_html($note['_field_commentaire'] ?? '') . '</td>';
            $output .= '</tr>';
        }

        $output .= '</table>';
    } else {
        // No results found
        $output = '<p>Aucune note enregistrée.</p>';
    }

    return $output;
}

// Shortcode to display all notes
add_shortcode('liste_notes', 'enregistrer_notes_etudiants');
function afficher_notes_etudiant($atts) {
    // Form for student ID input
    $form = '
        <form method="GET" action="" style="margin-bottom: 20px;">
            <label for="etudiant-id" style="font-weight: bold;">Entrez votre ID étudiant :</label>
            <input type="text" name="etudiant-id" id="etudiant-id" placeholder="Votre ID" required style="padding: 5px; margin-right: 10px; border: 1px solid #ccc; border-radius: 5px;">
            <button type="submit" style="padding: 5px 10px; background-color: #1e3a8a; color: white; border: none; border-radius: 5px; cursor: pointer;">Voir mes notes</button>
        </form>
    ';

    // If no student ID is provided, show only the form
    if (!isset($_GET['etudiant-id']) || empty($_GET['etudiant-id'])) {
        return $form . '<p>Entrez votre ID étudiant pour voir vos notes.</p>';
    }

    global $wpdb;
    $etudiant_id = sanitize_text_field($_GET['etudiant-id']);

    // Retrieve all notes for the specific student
    $results = $wpdb->get_results($wpdb->prepare("
        SELECT pm.post_id, pm.meta_key, pm.meta_value
        FROM {$wpdb->prefix}postmeta AS pm
        INNER JOIN {$wpdb->prefix}posts AS p
        ON pm.post_id = p.ID
        WHERE p.post_type = 'flamingo_inbound'
        AND p.ID IN (
            SELECT post_id FROM {$wpdb->prefix}postmeta 
            WHERE meta_key = '_field_etudiant-id' AND meta_value = %s
        )
        AND pm.meta_key IN ('_field_matiere', '_field_note', '_field_commentaire')
        ORDER BY p.post_date DESC
    ", $etudiant_id));

    if (!empty($results)) {
        // Group notes by post ID
        $organized_notes = [];
        foreach ($results as $row) {
            $organized_notes[$row->post_id][$row->meta_key] = maybe_unserialize($row->meta_value);
        }

        // Generate HTML table
        $output = '<table border="1" style="width: 100%; border-collapse: collapse; text-align: left;">';
        $output .= '<tr>
                        <th>Matière</th>
                        <th>Note</th>
                        <th>Commentaire</th>
                    </tr>';

        foreach ($organized_notes as $note) {
            $output .= '<tr>';
            $output .= '<td>' . esc_html(is_array($note['_field_matiere']) ? implode(', ', $note['_field_matiere']) : $note['_field_matiere']) . '</td>';
            $output .= '<td>' . esc_html($note['_field_note'] ?? '') . '</td>';
            $output .= '<td>' . esc_html($note['_field_commentaire'] ?? '') . '</td>';
            $output .= '</tr>';
        }

        $output .= '</table>';
    } else {
        // If no notes are found for the student
        $output = '<p>Aucune note trouvée pour cet ID étudiant.</p>';
    }

    return $form . $output;
}

// Shortcode to display notes for a specific student
add_shortcode('afficher_notes_etudiant', 'afficher_notes_etudiant');
function afficher_clubs_etudiant($atts) {
    // Form to input student ID
    $form = '
        <form method="GET" action="" style="margin-bottom: 20px;">
            <label for="etudiant-id" style="font-weight: bold;">Entrez votre ID étudiant :</label>
            <input type="text" name="etudiant-id" id="etudiant-id" placeholder="Votre ID étudiant" required style="padding: 5px; margin-right: 10px; border: 1px solid #ccc; border-radius: 5px;">
            <button type="submit" style="padding: 5px 10px; background-color: #1e3a8a; color: white; border: none; border-radius: 5px; cursor: pointer;">Voir mes clubs</button>
        </form>
    ';

    // If no student ID is provided, show only the form
    if (!isset($_GET['etudiant-id']) || empty($_GET['etudiant-id'])) {
        return $form . '<p>Entrez votre ID étudiant pour voir les clubs auxquels vous êtes inscrit(e).</p>';
    }

    global $wpdb;
    $etudiant_id = sanitize_text_field($_GET['etudiant-id']);

    // Retrieve clubs and submission dates for the specific student
    $results = $wpdb->get_results($wpdb->prepare("
        SELECT p.post_date, pm.meta_value AS club
        FROM {$wpdb->prefix}postmeta AS pm
        INNER JOIN {$wpdb->prefix}posts AS p
        ON pm.post_id = p.ID
        WHERE p.post_type = 'flamingo_inbound'
        AND p.ID IN (
            SELECT post_id FROM {$wpdb->prefix}postmeta 
            WHERE meta_key = '_field_etudiant-id' AND meta_value = %s
        )
        AND pm.meta_key = '_field_club'
        ORDER BY p.post_date DESC
    ", $etudiant_id));

    if (!empty($results)) {
        // Generate HTML table to display clubs with registration dates
        $output = '<table border="1" style="width: 100%; border-collapse: collapse; text-align: left;">';
        $output .= '<tr>
                        <th>Club</th>
                        <th>Date d\'inscription</th>
                    </tr>';

        foreach ($results as $row) {
            $club = maybe_unserialize($row->club);
            $date_inscription = date('d-m-Y', strtotime($row->post_date)); // Format the date

            $output .= '<tr>';
            $output .= '<td>' . esc_html(is_array($club) ? implode(', ', $club) : $club) . '</td>';
            $output .= '<td>' . esc_html($date_inscription) . '</td>';
            $output .= '</tr>';
        }

        $output .= '</table>';
    } else {
        // If no clubs are found for the student
        $output = '<p>Aucun club trouvé pour cet ID étudiant.</p>';
    }

    return $form . $output;
}

// Shortcode to display clubs with registration dates for a specific student
add_shortcode('afficher_clubs_etudiant', 'afficher_clubs_etudiant');
add_filter('wpcf7_skip_mail', '__return_true');
function afficher_examens() {
    global $wpdb;

    // Récupérer les données des examens depuis Flamingo
    $results = $wpdb->get_results("
        SELECT pm.post_id, pm.meta_key, pm.meta_value
        FROM {$wpdb->prefix}postmeta AS pm
        INNER JOIN {$wpdb->prefix}posts AS p
        ON pm.post_id = p.ID
        WHERE p.post_type = 'flamingo_inbound'
        AND pm.meta_key IN ('_field_nommod', '_field_date', '_field_horaire', '_field_salle')
        ORDER BY p.post_date DESC
    ");

    if (!empty($results)) {
        // Organiser les résultats par soumission
        $examens = [];
        foreach ($results as $row) {
            $examens[$row->post_id][$row->meta_key] = maybe_unserialize($row->meta_value); // Désérialiser si nécessaire
        }

        // Générer le tableau HTML
        $output = '<table border="1" style="width: 100%; border-collapse: collapse; text-align: left;">';
        $output .= '<tr>
                        <th>Nom du Module</th>
                        <th>Date</th>
                        <th>Horraire</th>
                        <th>Emplacement</th>
                    </tr>';

        foreach ($examens as $examen) {
            $nommod = esc_html($examen['_field_nommod'] ?? '');
            $date = esc_html($examen['_field_date'] ?? '');
            $horaire = esc_html($examen['_field_horaire'] ?? '');
            
            // Traiter les emplacements (tableaux ou sérialisés)
            $salle = $examen['_field_salle'] ?? '';
            if (is_array($salle)) {
                $salle = implode(', ', $salle); // Convertir les tableaux en chaîne de caractères
            } elseif (is_string($salle)) {
                $salle = esc_html($salle); // Protéger la chaîne de caractères
            }

            $output .= '<tr>';
            $output .= "<td>{$nommod}</td>";
            $output .= "<td>{$date}</td>";
            $output .= "<td>{$horaire}</td>";
            $output .= "<td>{$salle}</td>";
            $output .= '</tr>';
        }

        $output .= '</table>';
    } else {
        // Aucun examen trouvé
        $output = '<p>Aucun examen enregistré pour le moment.</p>';
    }

    return $output;
}

// Ajouter un shortcode pour afficher les examens
add_shortcode('afficher_examens', 'afficher_examens');


// Fonction pour récupérer les informations du cours depuis la base de données
function afficher_cours() {
    global $wpdb;

    // Récupérer les données des cours depuis Flamingo
    $results = $wpdb->get_results("SELECT pm.post_id, pm.meta_key, pm.meta_value
        FROM {$wpdb->prefix}postmeta AS pm
        INNER JOIN {$wpdb->prefix}posts AS p
        ON pm.post_id = p.ID
        WHERE p.post_type = 'flamingo_inbound'
        AND pm.meta_key IN ('_field_nom_professeur', '_field_course_title', '_field_course_description', '_field_course_duration', '_field_course_materials')
        ORDER BY p.post_date DESC");

    if (!empty($results)) {
        // Organiser les résultats par soumission
        $cours = [];
        foreach ($results as $row) {
            $cours[$row->post_id][$row->meta_key] = maybe_unserialize($row->meta_value); // Désérialiser si nécessaire
        }

        // Générer le tableau HTML
        $output = '<table border="1" style="width: 100%; border-collapse: collapse; text-align: left;">';
        $output .= '<tr>
                        <th>Nom du Professeur</th>
                        <th>Titre du Cours</th>
                        <th>Description</th>
                        <th>Durée (heures)</th>
                        <th>Matériaux</th>
                    </tr>';

        foreach ($cours as $cour) {
            $nom_professeur = esc_html($cour['_field_nom_professeur'] ?? '');
            $titre_cours = esc_html($cour['_field_course_title'] ?? '');
            $description_cours = esc_html($cour['_field_course_description'] ?? '');
            $duree_cours = esc_html($cour['_field_course_duration'] ?? '');

            // Traiter les matériaux (fichiers téléchargeables sous forme de hash/ID)
            $materiaux_cours = $cour['_field_course_materials'] ?? '';
            if (!empty($materiaux_cours)) {
                $file_url = 'http://localhost:8081/projetfin/uploads/' . esc_html($materiaux_cours);
                $materiaux_cours = '<a href="' . esc_url($file_url) . '" target="_blank">Télécharger</a>';
            } else {
                $materiaux_cours = 'Aucun fichier';
            }

            $output .= '<tr>';
            $output .= "<td>{$nom_professeur}</td>";
            $output .= "<td>{$titre_cours}</td>";
            $output .= "<td>{$description_cours}</td>";
            $output .= "<td>{$duree_cours}</td>";
            $output .= "<td>{$materiaux_cours}</td>";
            $output .= '</tr>';
        }

        $output .= '</table>';
    } else {
        // Aucun cours trouvé
        $output = '<p>Aucun cours enregistré pour le moment.</p>';
    }

    return $output;
}

// Ajouter un shortcode pour afficher les cours
add_shortcode('afficher_cours', 'afficher_cours');
function custom_login_redirect($redirect_to, $request, $user) {
    // Vérifie si l'utilisateur est connecté et a un rôle.
    if (isset($user->roles) && is_array($user->roles)) {
        if (in_array('administrator', $user->roles)) {
            return admin_url(); // Tableau de bord pour admin
        } elseif (in_array('subscriber', $user->roles)) {
            return site_url('/espace-useracc'); // Page personnalisée pour Subscribers
        } elseif (in_array('professeur', $user->roles)) {
            return site_url('/acceuil-enseignant'); // Page personnalisée pour Professeurs
        } elseif (in_array('adminecole', $user->roles)) {
            return site_url('/accueiladmin'); // Page personnalisée pour Admin école
        }
    }

    return $redirect_to; // Redirection par défaut
}
add_filter('login_redirect', 'custom_login_redirect', 10, 3);
function hide_admin_bar_for_specific_roles() {
    // Obtenez l'utilisateur actuellement connecté.
    $current_user = wp_get_current_user();

    // Liste des rôles pour lesquels la barre doit être masquée.
    $roles_to_hide_bar = array('subscriber', 'professeur','adminecole');

    // Si l'utilisateur a l'un des rôles spécifiés, désactiver la barre d'administration.
    if (array_intersect($roles_to_hide_bar, $current_user->roles)) {
        show_admin_bar(false);
    }
}
add_action('after_setup_theme', 'hide_admin_bar_for_specific_roles');
function redirect_after_logout() {
    wp_redirect(home_url()); // Redirige vers la page d'accueil
    exit(); // Nécessaire pour arrêter l'exécution du script après la redirection
}
add_action('wp_logout', 'redirect_after_logout');



