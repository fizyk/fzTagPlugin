<?php
/**
 * Description of BasefzTagActions
 *
 * @author fizyk
 */
class BasefzTagActions extends sfActions
{
    /**
     * Action used to list all tags
     * 
     * @param sfWebRequest $request 
     */
    public function executeIndex(sfWebRequest $request)
    {
        // getting default sorter options from configuration
        $defaultSorters = sfConfig::get('app_fzTagPlugin_list_sort_default', array());
                
        //validating config sorters
        if( !is_array($defaultSorters) || 
            !( array_key_exists('by', $defaultSorters) && array_key_exists('order', $defaultSorters)) )
        {
            $defaultSorters = array(
                            'by' => 'name',
                            'order' => 'asc'
                            );
        }
        $this->sortParameters = $defaultSorters;
        
        // if there was any change by user, adapt to it
        if( $request->hasParameter('by') )
        {
            $this->sortParameters['by'] = strip_tags( $request->getParameter('by') );
        }
        if( $request->hasParameter('order') )
        {
            $this->sortParameters['order'] = strip_tags( $request->getParameter('order') );
        }
        
        // get the query and pager
        $query = fzTagTable::getInstance()->getListQuery( $this->sortParameters );
        $this->pager = new sfDoctrinePager('fzTag', sfConfig::get('app_fzTagPlugin_list_max_tags', 10));
        $this->pager->setQuery($query);
        $this->pager->setPage($request->getParameter( 'page', 1 ));
        $this->pager->init();
    }

    /**
     * Stub of an action used to show tag page
     * 
     * @param sfWebRequest $request 
     */
    public function executeShow(sfWebRequest $request)
    {
        $this->tag = $this->getRoute()->getObject();
    }
}
?>
