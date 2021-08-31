<?php

class MyFormulaire_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct( 'my_formulaire', 'Formulaire newsletter', array( 'description' => "Formulaire d'inscription à la newsletter." ) );
    }

    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        echo $args['before_title'];
        echo $instance['title'];
        echo $args['after_title'];
        echo $args['after_widget'];
        echo('<form action="" method="POST"><p><label for="my-formulaire-name">Votre Nom :</label><input type="text" name="clientName" id="my-formulaire-name"><label for="my-formulaire-email">Votre email :</label><input type="email" name="email" id="my-formulaire-email"></p><input type="submit" value="S\'inscrire"></form>');
    }

    public function form( $instance ) {
        $title = isset( $instance['title'] ) ? $instance['title'] : '';
        echo('<p><label for="' . $this->get_field_id( 'title' ) . '">Titre :</label><input class="widefat" id="' . $this->get_field_id( 'title' ) . '" name="' . $this->get_field_name( 'title' ) . '" type="text" value="' . $title . '" /></p>');
    }
}

