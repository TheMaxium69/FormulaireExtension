<?php

class MyFormulaire_Admin
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'addAdminMenu'));
        add_shortcode('myformulaire_suscribers_list', array($this, 'shortcodeSuscribersList'));
    }

    public function addAdminMenu()
    {
        add_menu_page(
            'Formulaire Extension - Mon plugin',
            'Formulaire Extension',
            'manage_options',
            'FormulaireExtension',
            array($this, 'generateHtml'),
            plugin_dir_url(__FILE__) . 'icon.png'
        );

        add_submenu_page(
            'FormulaireExtension',
            'Home',
            'Home',
            'manage_options',
            'FormulaireExtension',
            array($this, 'generateHtml')
        );

        add_submenu_page(
            'FormulaireExtension',
            'Les inscrits',
            'Register',
            'manage_options',
            'FormulaireExtension_Liste',
            array($this, 'generateSuscribersHtml')
        );

        add_submenu_page(
            'FormulaireExtension',
            'Je suis une page de test',
            'Test',
            'manage_options',
            'FormulaireExtension_Test',
            array($this, 'generateTestHtml')
        );
    }

    public function generateHtml()
    {
        echo '<h1>' . get_admin_page_title() . '</h1>';
        echo '<p>Bienvenue sur l\'accueil de mon extensions</p>';
    }

    public function generateSuscribersHtml()
    {
        echo '<h1>' . get_admin_page_title() . '</h1>';
        echo $this->genHtmlList();
    }

    public function generateTestHtml()
    {
        echo '<h1>' . get_admin_page_title() .'</h1>';
        echo '<p style="color: red"> Coucou je suis un simple paragraphe pour tester d\'afficher une page </p>';
        var_dump($this->getAllContacts());
        echo $this->genHtmlList();
    }

    private function getAllContacts()
    {
        global $wpdb;
        $suscribers = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}my_formulaire");

        return $suscribers;
    }

    public function shortcodeSuscribersList($attr, $content)
    {
        $html = "<h2>Liste des inscrits</h2>";
        if (isset($attr['subtitle'])) $html .= "<h3>{$attr['subtitle']}</h3>";
        $html .= $this->genHtmlList();
        return $html;
    }

    public function genHtmlList()
    {
        $suscribers = $this->getAllContacts();
        $html = "";
        if (count($suscribers) > 0) {
            $html .= '<table class="my-formulaire-liste" style="border-collapse:collapse"><tbody>';
            foreach ($suscribers as $suscriber) {
                $html .= "<tr><td width='150' style='border:1px solid black;'>{$suscriber->name}</td><td width='300' style='border:1px solid black;'>{$suscriber->email}</td></tr>";
            }
            $html .= '<tbody></table>';
        } else {
            $html .= "<p>Not inscrit</p>";
        }
        return $html;
    }
}
