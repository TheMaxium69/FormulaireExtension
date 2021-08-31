<?php

class MyFormulaire_Admin
{
    public function __construct()
    {

        add_action('admin_menu', [$this => "addAdminMenu"]);
    }


}