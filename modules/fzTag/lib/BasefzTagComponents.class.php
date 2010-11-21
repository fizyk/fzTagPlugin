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
    public function executeList()
    {
        $this->tags = fzTagTable::getInstance()->createQuery('t')->execute();
    }

    public function executeTagCloud()
    {
        if( !isset( $this->limit ))
        {
            $this->limit = 20;
        }

        $weights = fzTagTable::getInstance()->getWeightsForCloudQuery($this->limit)->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        uasort($weights, array($this, 'sortWeights'));
        
        $this->tags = fzTagTable::getInstance()->getTagsForCloudQuery($this->limit)->execute();



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

    public function execute3dTagCloud()
    {
        $this->cloudOptions = array(
            'height' => $this->getVar('height') ? $this->getVar('height') : 100,
            'width' => $this->getVar('width') ? $this->getVar('width') : 100,
            'min_font_size' => $this->getVar('min_font_size') ? $this->getVar('min_font_size') : 10,
            'max_font_size' => $this->getVar('max_font_size') ? $this->getVar('max_font_size') : 16,
            'zoom' => $this->getVar('zoom') ? $this->getvar('zoom') : 100
        );

        $this->executeTagCloud();
    }

    public function executeCanvasTagCloud()
    {
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
        );

        $this->executeTagCloud();
    }

    private function sortWeights($a, $b)
    {
        if($a['weight'] == $b['weight'])
        {
            return 0;
        }
        return ($a['weight'] > $b['weight']) ? -1 : 1;
    }

}
?>
