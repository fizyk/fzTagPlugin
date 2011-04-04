<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BasefzTagComponents
 *
 * @author fizyk
 */
class BasefzTagComponents extends sfComponents
{
    /**
     * Tag's list component
     */
    public function executeList()
    {
        $this->tags = fzTagTable::getInstance()->createQuery('t')->execute();
    }

    /**
     * Basic tag cloud component
     */
    public function executeTagCloud()
    {
        // Checking if cloud option's hasn't been set before
        if(!is_array($this->cloudOptions))
        {
            $this->cloudOptions = array();
        }
        $this->cloudOptions['cloud_id'] = $this->getVar('cloud_id') ? '-'.$this->getVar('cloud_id') : '';
        if( !isset( $this->limit ))
        {
            $this->limit = 20;
        }
        
        // if tags arent already retrieved, querying for them
        if( !isset($this->tags) 
                || !$this->tags instanceof Doctrine_Collection 
                || (!$this->tags->getFirst() instanceof fzTag && !$this->tags->count() == 0) )
        {
            $this->tags = fzTagTable::getInstance()->getTagsForCloudQuery($this->limit)->execute();
        }
        // creating weight map
        $this->weightMap = $this->setWeightMap( $this->tags );
    }
    
    /**
     * metod is used to construct weigtMap, that helps generating different sizes of tags with different weights.
     * @param Doctrine_Collection $tags
     * @return array 
     * @author Grzegorz Śliwiński
     */
    protected function setWeightMap( Doctrine_Collection $tags )
    {
        $weights = array();
        foreach($this->tags as $tag)
        {
            if(!array_key_exists($tag->getWeight(), $weights))
            {
                $weights[$tag->getWeight()] = 1;
            }
            else
            {
                $weights[$tag->getWeight()]++;
            }
        }
        asort($weights);
        // That map will be used to create tag's style class (up to five different)
        $weightMap = array();
        // if we have only one weight, we'll just create one entry
        if(count($weights) <= 1)
        {
            foreach($weights as $weight => $count)
            {
                $weightMap[$weight] = 1;
            }
        }
        // if we have up to five different weights and up to five different tags,
        // we'll just create on entry per each weight
        elseif(count($weights) <= 5 && $this->tags->count() <= 5)
        {
            $css = 1;
            foreach($weights as $weight => $count)
            {
                $weightMap[$weight] = $css++;
            }
        }
        // otherwise we'll try to level that five levels between tags weight
        else
        {
            $css = 1;
            // we have five levels, so we set boundary, when each tag will change
            $space = $this->tags->count() / 5;
            $tags_mapped = 0;
            foreach($weights as $weight => $count)
            {
                $weightMap[$weight] = $css;
                // we add number of tags with current weight to know,
                // how many of them are alrady mapped
                $tags_mapped += $count;
                // simply as that, if we exceed given number, we'll just get the higher number.
                $css = floor(1 + ($tags_mapped / $space));
            }
        }
        
        return $weightMap;
    }

    /**
     * 3d tag cloud component
     * It's based on the basic tag cloud
     */
    public function execute3dTagCloud()
    {
        //checking for options necessary for the tag cloud
        $this->cloudOptions = array(
            'height' => $this->getVar('height') ? $this->getVar('height') : 100,
            'width' => $this->getVar('width') ? $this->getVar('width') : 100,
            'min_font_size' => $this->getVar('min_font_size') ? $this->getVar('min_font_size') : 10,
            'max_font_size' => $this->getVar('max_font_size') ? $this->getVar('max_font_size') : 16,
            'zoom' => $this->getVar('zoom') ? $this->getvar('zoom') : 100
        );
        
        $this->executeTagCloud();
    }

    /**
     * canvas tag cloud component
     * It is based on the basic tag cloud
     */
    public function executeCanvasTagCloud()
    {
        //checking for options necessary for the tag cloud
        $this->cloudOptions = array(
            'height' => $this->getVar('width') ? $this->getVar('width') : 100,
            'width' => $this->getVar('width') ? $this->getVar('width') : 100,
            'maxSpeed' => $this->getVar('maxSpeed') ? $this->getVar('maxSpeed'): 0.05,
            'minSpeed' => $this->getVar('minSpeed') ? $this->getVar('minSpeed'): 0.0,
            'decel' => $this->getVar('decel') ? $this->getVar('decel'): 0.95,
            'minBrightness' => $this->getVar('minBrightness') ? $this->getVar('minBrightness'): 0.1,
            'textColour' => $this->getVar('textColour') ? $this->getVar('textColour'): "#000000",
            'textHeight' => $this->getVar('textHeight') ? $this->getVar('textHeight'): 15,
            'textFont' => $this->getVar('textFont') ? $this->getVar('textFont'): "Helvetica, Arial, sans-serif",
            'outlineColour' => $this->getVar('outlineColour') ? $this->getVar('outlineColour'): "#000000",
            'outlineThickness' => $this->getVar('outlineThickness') ? $this->getVar('outlineThickness'): 1,
            'outlineOffset' => $this->getVar('outlineOffset') ? $this->getVar('outlineOffset'): 5,
            'pulsateTo' => $this->getVar('pulsateTo') ? $this->getVar('pulsateTo'): 1.0,
            'pulsateTime' => $this->getVar('pulsateTime') ? $this->getVar('pulsateTime'): 3,
            'depth' => $this->getVar('depth') ? $this->getVar('depth'): 0.5,
            'initial' => $this->getVar('initial') ? $this->getVar('initial'): null,
            'freezeActive' => $this->getVar('freezeActive') ? $this->getVar('freezeActive'): false,
            'reverse' => $this->getVar('reverse') ? $this->getVar('reverse'): false,
            'hideTags' => $this->getVar('hideTags') ? $this->getVar('hideTags'): true,
            'zoom' => $this->getVar('zoom') ? $this->getVar('zoom'): 1.0,
            'shadow' =>	$this->getVar('shadow') ? $this->getVar('shadow'): "#000000",
            'shadowBlur' => $this->getVar('shadowBlur') ? $this->getVar('shadowBlur'): 0,
            'shadowOffset' => $this->getVar('shadowOffset') ? $this->getVar('shadowOffset'): '[0,0]',
            'weight' => $this->getVar('weight') ? $this->getVar('weight'): true,
            'weightMode' => $this->getVar('weightMode') ? $this->getVar('weightMode'): "size",
            'weightSize' => $this->getVar('weightSize') ? $this->getVar('weightSize'): 1.0,
            'weightGradient' => $this->getVar('weightGradient') ? $this->getVar('weightGradient'): array('0' => '#f00', '0.33' => '#ff0', '0.66' => '#0f0', '1' =>'#00f'),
        );

        $this->executeTagCloud();
    }

}
?>
