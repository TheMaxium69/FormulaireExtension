<?php


class MyFormulaire_Session
{
    public function __construct()
    {
        // Démarrage du module PHP de gestion des sessions
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function createMessage($type, $message)
    {
        $_SESSION['my-formulaire'] =
            [
                'type'    => $type,
                'message' => $message
            ];
    }

    public function getMessage()
    {
        return isset($_SESSION['my-formulaire']) && count($_SESSION['my-formulaire']) > 0 ? $_SESSION['my-formulaire'] : false;
    }

    public function destroy()
    {
        $_SESSION['my-formulaire'] = array();
    }
}
