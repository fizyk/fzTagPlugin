<?php
/**
 * fzTagPlugin configuration class. Adds listener for the form.post_configure event
 * @author: Grzegorz Śliwinski
 */
class fzTagPluginConfiguration extends sfPluginConfiguration
{
  
  public function configure()
  {
    $this->dispatcher->connect('form.post_configure', array($this, 'listenToFormPostConfigureEvent'));
  }
  
  public function initialize()
  {
    if( in_array('fzTagAutocomplete', sfConfig::get('sf_enabled_modules', array())))
    {
      $this->dispatcher->connect('routing.load_configuration', array($this, 'listenToRoutingLoadConfigurationEvent'));
    }
  }

  /**
   * Listener to the form.post_configure event, as set in configure method.
   * @param sfEvent $event
   */
  public function listenToFormPostConfigureEvent(sfEvent $event)
  {
    $form = $event->getSubject();

    if( $form instanceof BaseFormDoctrine && $form->getObject()->getTable()->hasTemplate('fzTaggable'))
    {
      $form->setWidget('tags', new sfWidgetFormFzTagsAutocomplete(
        array('choices' => $form->getObject()->getTagNames() )
      ));

      $form->setValidator('tags', new sfValidatorFzTagsAutocomplete(array(
        'required' => false
      )));

      unset( $form['tags_list'] );
    }
  }


  /**
   * Listens to the routing.load_configuration event.
   *
   * @param sfEvent An sfEvent instance
   * @static
   */
  public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
  {
    $r = $event->getSubject();
    // preprend our route
    $r->prependRoute('fz_tag_autocomplete', new sfRoute('/fzTagAutocomplete', array('module' => 'fzTagAutocomplete', 'action' => 'index')));
  }
}