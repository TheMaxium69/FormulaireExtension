<?php

/*
Plugin Name: FormulaireExtension
Plugin URI : https://github.com/TheMaxium69/FormulaireExtension
Author: Maxime Tournier
Author URI: https://tyrolium.fr
Description: Extension WordPress, Première Extension
Version: 1.1-BETA
*/

require_once "myformulaire.php";
require_once "myformulaire_session.php";
require_once "myformulaire_admin.php";

class MyFormulaire {
    public function __construct() {
        add_action( 'widgets_init', function () {
            register_widget('MyFormulaire_Widget');
        });
        add_action('init', array('MyFormulaire', 'loadFile'));
        register_activation_hook(__FILE__, array('MyFormulaire', 'install'));
        register_uninstall_hook(__FILE__, array('MyFormulaire', 'uninstall'));
        add_action('wp_loaded', array($this, 'saveEmail'), 1);
        add_action('wp_loaded', array($this, 'checkInfo'), 2);
    }

    public static function install() {
        global $wpdb;
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}my_formulaire (id INT AUTO_INCREMENT PRIMARY KEY,name VARCHAR(255) NOT NULL,email VARCHAR(255) NOT NULL UNIQUE);");
    }

    public static function uninstall() {
        global $wpdb;
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}my_formulaire;");
    }

    public static function loadFile()
    {
        wp_register_style('MyFormulaire', plugins_url('extend.css', __FILE__));
        wp_enqueue_style('MyFormulaire');

        wp_register_script('MyFormulaire', plugins_url('extend.js', __FILE__));
        wp_enqueue_script('MyFormulaire');
    }

    public function saveEmail() {
        $regex = "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix";

        if (isset($_POST['email']) && !empty($_POST['email'])) {

            $myFormulaire_Session = new MyFormulaire_Session();


            $email = $_POST['email'];
            if (preg_match($regex, $email)) {
                global $wpdb;

                $user = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}my_formulaire WHERE email = '$email'");

                if (is_null($user)) {

                    $datas = ['email' => $email];

                    if (isset($_POST['my-name']) && !empty($_POST['my-name'])) {
                        $datas['name'] = $_POST['my-name'];
                    }
                    $result = $wpdb->insert("{$wpdb->prefix}my_formulaire", $datas);

                    if ($result === false) {
                        $myFormulaire_Session->createMessage("error", "Il y a une erreur, réseillez plus tarrd");
                    } else {
                        $myFormulaire_Session->createMessage("success", "Ajout dans le newsletter effectuer.");
                    }
                } else {
                    $myFormulaire_Session->createMessage("error", "Vous etes déjà inscrit a notre newsletter.");
                }
            } else {
                $myFormulaire_Session->createMessage("error", "Vous avez saisie un email invalide.");
            }
        }
    }

    public function checkInfo()
    {
        $myFormulaire_Session = new MyFormulaire_Session();

        $message = $myFormulaire_Session->getMessage();

        if ($message !== false) {
            echo ("
                <p class='my-formulaire-info " . $message["type"] . "'>
                    " . $message["message"] . "
                </p>
            ");
        }

        $message = $myFormulaire_Session->destroy();
    }

}

new MyFormulaire();
new MyFormulaire_Admin();