<?php

/**
 * sfWidgetFormFzTagsAutocomplete is modified sfWidgetFormDoctrineFBAutocompleter
 * mixing some swWidgetFormDmTagsAutocmomplete's code from dmTagPlugin.
 *
 * @package    symfony
 * @subpackage widget
 * @author     Grzegorz Śliwiński
 * @see http://www.symfony-project.org/plugins/sfDoctrineFBAutocompletePlugin
 * @see http://diem-project.org/plugins/dmtagplugin
 */
class sfWidgetFormFzTagsAutocomplete extends sfWidgetFormSelect
{
  /**
   * Configures the current widget.
   *
   * Available options:
   *
   *  * url:            The URL to call to get the choices to use (required)
   *  * config:         A JavaScript array that configures the JQuery autocompleter widget
   *  * value_callback: A callback that converts the value before it is displayed
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);
    $this->setOption('multiple', true);
    $this->addOption('model_options', $options['model_options']);
    
    $this->setFcbkCompleteOptions();


  }

  private function setFcbkCompleteOptions()
  {
    $appOptions = sfConfig::get( 'app_fzTagPlugin_fcbkcomplete' );
    $model_options = $this->getOption('model_options');
//    var_dump( $model_options );
//    die();
    
    $this->addOption('json_url', sfContext::getInstance()->getController()->genUrl( '@fz_tag_autocomplete', false) );
    $this->addOption('cache', false);
    $this->addOption('height', false);
    $this->addOption('newel', array_key_exists('newel', $model_options) ?
                                $model_options['newel'] : $appOptions['newel']  );
    $this->addOption('firstselected', array_key_exists('firstselected', $model_options) ?
                                $model_options['firstselected'] : $appOptions['firstselected'] );
    $this->addOption('filter_case', $appOptions['filter_case'] );
    $this->addOption('filter_hide', $appOptions['filter_hide'] );
    $this->addOption('filter_selected', false);
    $this->addOption('complete_text', true );
    $this->addOption('maxshownitems', $model_options['maxshownitems'] ?
                                $model_options['maxshownitems'] : $appOptions['maxshownitems'] );
    $this->addOption('maxitems', $model_options['maxitems'] ?
                                $model_options['maxitems'] : $appOptions['maxitems'] );
    $this->addOption('onselect', false);
    $this->addOption('onremove', false);
    $this->addOption('delay ', 1);
    $this->addOption('template', <<<EOF
    %associated%
    <script type="text/javascript">
      jQuery(document).ready(function() {
        jQuery("#%id% option").attr('selected','selected');
        jQuery("#%id%").fcbkcomplete({%config%});
      });
    </script>
EOF
);
  }


  /**
   *
   * @return string
   */
  private function getFcbkComompleteOptions()
  {
    $json_url           = 'json_url : "' . $this->getOption('json_url')  .'",';
    $cache              = $this->getOption('cache') ? 'cache : true,' : '' ;
    $newel              = $this->getOption('newel') ? 'newel : true,' : '' ;
    $firstselected      = $this->getOption('firstselected') ? 'firstselected : "'.$this->getOption('firstselected').'",' : '' ;
    $filter_case        = $this->getOption('filter_case') ? 'filter_case : true,' : '' ;
    $filter_hide        = $this->getOption('filter_hide') ? 'filter_hide : true,' : '' ;
    $filter_selected    = $this->getOption('filter_selected') ? 'filter_selected : true,' : '' ;
    $complete_text      = $this->getOption('complete_text') ? 'complete_text : "'.$this->getOption('complete_text').'",' : '' ;
    $maxshownitems      = $this->getOption('maxshownitems') ? 'maxshownitems : "'.$this->getOption('maxshownitems').'",' : '' ;
    $maxitems           = $this->getOption('maxitems') ? 'maxitems : "'.$this->getOption('maxitems').'",' : '' ;
    $onselect           = $this->getOption('onselect') ? 'onselect : "'.$this->getOption('onselect').'",' : '' ;
    $onremove           = $this->getOption('onremove') ? 'onremove : "'.$this->getOption('onremove').'",' : '' ;
    $delay              = $this->getOption('delay') ? 'delay : "'.$this->getOption('delay').'",' : '' ;

    $config = $json_url.$cache.$newel.$firstselected.$filter_case.$filter_hide.$filter_selected.$complete_text.$maxshownitems.$maxitems.$onselect.$onremove.$delay;

    return substr($config,0,-1);
  }

  /**
   * @param  string $name        The element name
   * @param  string $value       The date displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (is_null($value)){$value = array();}

    $choices = $this->getChoices();
    
    $associated = array();
    
    foreach ($choices as $key => $option)
    {
        $associated[$option] = $option;
    }
    
    $associatedWidget = new sfWidgetFormSelect(array('multiple' => true, 'choices' => $associated ));
    
    return strtr($this->getOption('template'), array(
      '%id%'                => $this->generateId($name),
      '%config%'          => $this->getFcbkComompleteOptions(),
      '%associated%'        => $associatedWidget->render($name)
    ));

    
  }

  /**
   * Gets the stylesheet paths associated with the widget.
   *
   * @return array An array of stylesheet paths
   */
  public function getStylesheets()
  {
    return array('/fzTagPlugin/css/style.css' => 'all');
  }

  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
  public function getJavascripts()
  {
    return array('/fzTagPlugin/js/jquery.fcbkcomplete.min.js');
  }
}
