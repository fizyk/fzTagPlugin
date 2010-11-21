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
        $this->sortParameters = array(
            'by' => 'name',
            'order' => 'asc'
            );
        if( $request->hasParameter('by') )
        {
            $this->sortParameters['by'] = strip_tags( $request->getParameter('by') );
        }
        if( $request->hasParameter('order') )
        {
            $this->sortParameters['order'] = strip_tags( $request->getParameter('order') );
        }

        $query = fzTagTable::getInstance()->getListQuery( $this->sortParameters );
        $this->pager = new sfDoctrinePager('fzTag', 10);
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
