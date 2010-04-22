<?php
/**
 * Components class to service tag lists
 * @author fizyk
 */
class fzTagComponents extends sfComponents
{
  public function executeList()
  {
    $this->tags = fzTagTable::getInstance()->createQuery('t')->execute();
  }
}
