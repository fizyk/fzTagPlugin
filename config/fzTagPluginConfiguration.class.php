<?php

/**
 * fzTagPlugin configuration class. Adds listener for the form.post_configure event
 * @author: Grzegorz Śliwinski
 */
class fzTagPluginConfiguration extends sfPluginConfiguration
{

    public function configure()
    {
        $this->dispatcher->connect( 'form.post_configure', array( $this, 'listenToFormPostConfigureEvent' ) );
    }

    public function initialize()
    {
        if( in_array( 'fzTagAutocomplete', sfConfig::get( 'sf_enabled_modules', array( ) ) ) )
        {
            $this->dispatcher->connect( 'routing.load_configuration', array( $this, 'addFzTagAutocompleteRoute' ) );
        }
        if( in_array( 'fzTagAdmin', sfConfig::get( 'sf_enabled_modules', array( ) ) ) )
        {
            $this->dispatcher->connect( 'routing.load_configuration', array( $this, 'addFzTagAdminRoute' ) );
        }
        if( in_array( 'fzTag', sfConfig::get( 'sf_enabled_modules', array( ) ) ) )
        {
            $this->dispatcher->connect( 'routing.load_configuration', array( $this, 'addFzTagRoute' ) );
        }
    }

    /**
     * Listener to the form.post_configure event, as set in configure method.
     * @param sfEvent $event
     */
    public function listenToFormPostConfigureEvent( sfEvent $event )
    {
        $form = $event->getSubject();

        /*
         * Only add autocomplete widget if:
         * 1. form is instance of BaseFormDoctrine
         * 2. Table behind form has fzTaggable behaviour
         * 3. fz_tag_autocomplete route is added in routing yml
         *    (since that depends on module availability, hence the check)
         * 4. if the tags_list is set in form.
         */
        if( $form instanceof BaseFormDoctrine
                && $form->getObject()->getTable()->hasTemplate( 'fzTaggable' )
                && in_array( 'fzTagAutocomplete', sfConfig::get( 'sf_enabled_modules', array( ) ) )
                && isset( $form[ 'tags_list' ] ) )
        {
//            var_dump( $form->getObject()->getTable()->getTemplate('fzTaggable')->getOptions() );
//            die();
            $form->setWidget( 'tags', new sfWidgetFormFzTagsAutocomplete(
                            array( 'choices' => $form->getObject()->getTagNames(),
                                'model_options' => $form->getObject()->getTable()->getTemplate('fzTaggable')->getOption('options'),
                                'complete_text' => sfContext::getInstance()
                                        ->getI18N()->
                                        __( 'Start to type...', array( ), 'fzTag' ) )
            ) );

            $form->setValidator( 'tags', new sfValidatorFzTagsAutocomplete( array(
                        'required' => false
                    ) ) );

            unset( $form[ 'tags_list' ] );
        }
    }

    /**
     * Listens to the routing.load_configuration event.
     *
     * @param sfEvent An sfEvent instance
     * @static
     */
    public function addFzTagAutocompleteRoute( sfEvent $event )
    {
        $r = $event->getSubject();
        // preprend our route
        $r->prependRoute( 'fz_tag_autocomplete', new sfRoute( '/fzTagAutocomplete', array( 'module' => 'fzTagAutocomplete', 'action' => 'index' ) ) );
    }

    public function addFzTagAdminRoute( sfEvent $event )
    {
        $r = $event->getSubject();
        // preprend our route
        $r->prependRoute( 'fz_tag_admin',
                new sfDoctrineRouteCollection( array(
                    'name' => 'fz_tag',
                    'model' => 'fzTag',
                    'module' => 'fzTagAdmin',
                    'prefix_path' => '/fz_tag_admin',
                    'with_wildcard_routes' => true,
                    'collection_actions' => array( 'filter' => 'post', 'batch' => 'post' ),
                    'requirements' => array( ),
                ) ) );
    }

    public function addFzTagRoute( sfEvent $event )
    {
        $r = $event->getSubject();
        // preprend our route
        $r->prependRoute( 'fz_tag',
                new sfDoctrineRoute( '/tags', 
                    array(
                        'module' => 'fzTag',
                        'action' => 'index'
                        ),
                    array(),
                    array(
                        'model' => 'fzTag',
                        'type' => 'object'
                ) ) );
        $r->prependRoute( 'fz_tag_show',
                new sfDoctrineRoute( '/tags/:name',
                    array(
                        'module' => 'fzTag',
                        'action' => 'show'
                        ),
                    array(),
                    array(
                        'model' => 'fzTag',
                        'type' => 'object'
                ) ) );
    }

}