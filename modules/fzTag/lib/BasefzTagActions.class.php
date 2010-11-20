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
        $query = fzTagTable::getInstance()->createQuery('t')->orderBy('t.name ASC');
        $this->pager = new sfDoctrinePager('fzTag', 10);
        $this->pager->setQuery($query);
        $this->pager->setPage($request->getParameter( 'page', 1 ));
        $this->pager->init();
        # TODO! Add possibility to sort by name (asc, desc) and weight (asc, weight)
    }

    public function executeShow(sfWebRequest $request)
    {
        $this->tag = $this->getRoute()->getObject();
    }
}
?>
