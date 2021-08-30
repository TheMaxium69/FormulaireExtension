<?php

/*
Plugin Name: MaxWordPressPlugin
Author: Maxime
Author URI: https://tyrolium.fr
Description: mon plugin pour apprendre
Version: 1.0-BETA
*/

require_once "formualirewidget.php";

class MyFormulaire
{
    public function __construct() {
        /**
         * Lorsque Wordpress est prêt à charger les widgets (widget_init), nous lui demandons de recenser le notre en renseignant le nom de la classe du widget
         */
        add_action( 'widgets_init', function () {
            register_widget( 'MyFormulaire_Widget' );
        } );

        /**
         * __FILE__ permet de pointer vers le fichier courant
         * on indique ensuite le nom de la classe et de la méthode à lancer
         * on renseigne le nom de la classe et non une instance de cette dernière car la méthode install() et une méthode statique, donc relative à la classe.
         */
        register_activation_hook( __FILE__, array( 'MyFormulaire', 'install' ) );
        register_uninstall_hook( __FILE__, array( 'MyFormulaire', 'uninstall' ) );
        add_action( 'wp_loaded', array( $this, 'saveEmail' ) );
    }

    public static function install() {
        //on récupère l'instance de la classe permettant de manipuler la BDD
        global $wpdb;

        /**
         * On vient créer la table, si elle n'existe pas déjà
         * $wpdb->prefix : contient le préfixe défini à la création pour cette BDD
         * l'id est autoincrémenté et l'email doit être unique
         */

        $wpdb->query( "
        CREATE TABLE IF NOT EXISTS {$wpdb->prefix}my_formulaire 
        (id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE);
    " );
    }

    public function saveEmail() {
        //s'il y a une clé [email] dans le $_POST, on vient le récupérer pour l'enregistrer dans la base
        if ( isset( $_POST['email'] ) && ! empty( $_POST['email']) && isset( $_POST['clientName'] ) && ! empty( $_POST['clientName']) ) {
            //on récupère de nouveau l'instance de la classe permettant de manipuler la BDD
            global $wpdb;

            //on stocke la valeur saisie dans l'input dans une variable
            $email = $_POST['email'];
            if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
                $emailErr = "Invalid email format";
            } else {
                $clientName = $_POST['clientName'];

                /**
                 * on fait une requête SELECT pour vérifier s'il n'est pas déjà dans la base.
                 * retourne null en cas d'échec
                 */
                $user = $wpdb->get_row( "
	        SELECT * FROM {$wpdb->prefix}my_formulaire 
	        WHERE email = '$email'
	    " );

                //on ne veut insérer l'email que s'il n'est pas déjà dans la base et que donc $user est null
                if ( is_null( $user ) ) {

                    /**
                     * la méthode insert attend 2 informations :
                     * - le nom de la table dans laquelle insérer
                     * - les données à insérer sous la forme d'un tableau associatif ["nom_colonne" => valeur]
                     */

                    $wpdb->insert( "{$wpdb->prefix}my_formulaire", [ 'email' => $email ,'name'=> $clientName] );
                }
            }
        }
    }
}

//on instancie notre classe
new MyFormulaire();