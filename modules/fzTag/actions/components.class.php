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

    public function executeTagCloud()
    {
        $this->tags = fzTagTable::getInstance()->getTagsWeightOrderedQuery(20)->execute();
    }

}
