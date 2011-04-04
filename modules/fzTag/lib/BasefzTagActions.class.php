<?php
/**
 * Description of BasefzTagActions
 *
 * @author fizyk
 */
class BasefzTagActions extends sfActions
{
    public function executeIndex(sfWebRequest $request)
    {
        $defaultSorters = sfConfig::get('app_fzTagPlugin_list_sort_default', array());
                
        if( !is_array($defaultSorters) or !(array_key_exists('by', $defaultSorters) && array_key_exists('order', $defaultSorters)) )
        {
            $defaultSorters = array(
                            'by' => 'name',
                            'order' => 'asc'
                            );
        }
        $this->sortParameters = $defaultSorters;
        
        if( $request->hasParameter('by') )
        {
            $this->sortParameters['by'] = strip_tags( $request->getParameter('by') );
        }
        if( $request->hasParameter('order') )
        {
            $this->sortParameters['order'] = strip_tags( $request->getParameter('order') );
        }

        $query = fzTagTable::getInstance()->getListQuery( $this->sortParameters );
        $this->pager = new sfDoctrinePager('fzTag', sfConfig::get('app_fzTagPlugin_list_max_tags', 10));
        $this->pager->setQuery($query);
        $this->pager->setPage($request->getParameter( 'page', 1 ));
        $this->pager->init();
    }

    public function executeShow(sfWebRequest $request)
    {
        $this->tag = $this->getRoute()->getObject();
    }
}
?>
