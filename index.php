<?php

/*
Plugin Name: FormulaireExtension
Author: Maxime
Author URI: https://tyrolium.fr
Description: Extension WordPress, Première Extension
Version: 1.0-BETA
*/

require_once "myformulaire.php";
require_once "myformulaire_admin.php";

class MyFormulaire {
    public function __construct() {
        add_action( 'widgets_init', function () {
            register_widget( 'MyFormulaire_Widget' );
        });
        register_activation_hook( __FILE__, array( 'MyFormulaire', 'install' ) );
        register_uninstall_hook( __FILE__, array( 'MyFormulaire', 'uninstall' ) );
        add_action( 'wp_loaded', array( $this, 'saveEmail' ) );
    }

    public static function install() {
        global $wpdb;

        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}my_formulaire (id INT AUTO_INCREMENT PRIMARY KEY,name VARCHAR(255) NOT NULL,email VARCHAR(255) NOT NULL UNIQUE);");
    }

    public function saveEmail() {
        if ( isset( $_POST['email'] ) && ! empty( $_POST['email']) && isset( $_POST['clientName'] ) && ! empty( $_POST['clientName']) ) {
            global $wpdb;

            $email = $_POST['email'];
            if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
                $emailErr = "Invalid email format";
            } else {
                $clientName = $_POST['clientName'];

                $user = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}my_formulaire WHERE email = '$email'");
                if ( is_null( $user ) ) {
                    $wpdb->insert( "{$wpdb->prefix}my_formulaire", [ 'email' => $email ,'name'=> $clientName] );
                }
            }
        }
    }
}

new MyFormulaire();