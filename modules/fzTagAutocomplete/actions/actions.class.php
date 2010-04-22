<?php
/**
 * Description of fzTagAutocompleteActions
 *
 * @author fizyk
 */
class fzTagAutocompleteActions extends sfActions
{
  public function executeIndex( sfWebRequest $request )
  {
    $this->tags = fzTagTable::getInstance()->getTagsForAutocomplete();
    
    $this->setLayout(false);
    $request->setParameter('sf_format','json');
  }
}