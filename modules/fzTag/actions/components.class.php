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
        $this->tags = fzTagTable::getInstance()->getTagsForCloudQuery(20)->execute();
        $weights = fzTagTable::getInstance()->getWeightsForCloudQuery(20)->execute(array(), Doctrine_Core::HYDRATE_ARRAY);

        // That map will be used to create tag's style class (up to five different)
        $this->weightMap = array();
        // if we have only one weight, we'll just create one entry
        if(count($weights) <= 1)
        {
            foreach($weights as $weight)
            {
                $this->weightMap[$weight['weight']] = 1;
            }
        }
        // if we have up to five different weights and up to five different tags,
        // we'll just create on entry per each weight
        elseif(count($weights) <= 5 && $this->tags->count() <= 5)
        {
            $css = 1;
            foreach($weights as $weight)
            {
                $this->weightMap[$weight['weight']] = $css++;
            }
        }
        // otherwise we'll try to level that five levels between tags weight
        else
        {
            $css = 1;
            // we have five levels, so we set boundary, when each tag will change
            $space = $this->tags->count() / 5;
            $tags_mapped = 0;
            foreach($weights as $weight)
            {
                $this->weightMap[$weight['weight']] = $css;
                // we add number of tags with current weight to know,
                // how many of them are alrady mapped
                $tags_mapped += $weight['num'];
                // simply as that, if we exceed given number, we'll just get the higher number.
                $css = floor(1 + ($tags_mapped / $space));
            }
        }
    }

}
