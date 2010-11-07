<?php

/**
 * fzTaggable, is a clone of Doctrine_Template_DmTaggable template class from dmTagPlugin
 *
 * @author fizyk
 */
class fzTaggable extends Doctrine_Template
{

    protected $_options = array(
        'tagClass' => 'fzTag',
        'tagAlias' => 'Tags',
        'className' => '%CLASS%FzTag',
        'generateFiles' => false,
        'table' => false,
        'pluginTable' => false,
        'children' => array(),
        'cascadeDelete' => true,
        'appLevelDelete' => false,
        'cascadeUpdate' => false
    );

    public function __construct(array $options = array())
    {
        $this->_options = Doctrine_Lib::arrayDeepMerge($this->_options, $options);

        $this->_plugin = new fzTaggableGenerator($this->_options);
    }

    public function setUp()
    {
        $this->_plugin->initialize($this->_table);

        $className = $this->_table->getComponentName();

        Doctrine_Core::getTable($this->_options['tagClass'])->bind(
                array($className.' as '.$className.'s',
                    array('class' => $className,
                        'local' => 'tag_id',
                        'foreign' => 'id',
                        'refClass' => $this->_plugin->getTable()->getOption('name')
                )), Doctrine_Relation::MANY);
    }

    /**
     * Returns number of tags, which model has been tagged with
     * @return integer
     */
    public function getNbTags()
    {
        return $this->getInvoker()->get($this->_options['tagAlias'])->count();
    }

    /**
     * Check's wheter object has any tags
     * @return boolean
     */
    public function hasTags()
    {
        return $this->getNbTags() > 0;
    }

    /**
     * Returns array with tag names
     * @return array
     */
    public function getTagNames()
    {
        $tagNames = array();
        foreach($this->getInvoker()->get($this->_options['tagAlias']) as $tag)
        {
            $tagNames[] = $tag->get('name');
        }

        return $tagNames;
    }

    /**
     * Returns tags in a string
     * @param string $sep
     * @return string
     */
    public function getTagsAsString($sep = ', ')
    {
        return implode($sep, $this->getTagNames());
    }

    /**
     * Sets the tags for the object, uninking all first, and the linking passed again
     * @param mixed $tags
     * @return self
     */
    public function setTags($tags)
    {
        if(empty($tags))
        {
            $tags = array();
        }

        $tagIds = $this->getTagIds($tags);
        // TODO get id's of all conected tags
        // TODO decrease weight on all unlinked.
        $this->getInvoker()->unlink($this->_options['tagAlias']);
        //TODO increase weight on those linked
        $this->getInvoker()->link($this->_options['tagAlias'], $tagIds);

        return $this->getInvoker();
    }

    /**
     * adds tags to the object
     * @param mixed $tags
     * @return self
     */
    public function addTags($tags)
    {
        // TODO increase weight on of those tags
        $this->getInvoker()->link($this->_options['tagAlias'], $this->getTagIds($tags));

        return $this->getInvoker();
    }

    /**
     * Removes tags from the object
     * @param mixed $tags
     * @return self
     */
    public function removeTags($tags)
    {
        // TODO decrease weight on those unlinked tags
        $this->getInvoker()->unlink($this->_options['tagAlias'], $this->getTagIds($tags));

        return $this->getInvoker();
    }

    /**
     * Removes all tags from the tagged object
     * @return self
     */
    public function removeAllTags()
    {
        // TODO decrease weight on all unlinked tags
        $this->getInvoker()->unlink($this->_options['tagAlias']);

        return $this->getInvoker();
    }

    /**
     * returns related records of the same type
     * @param integer $hydrationMode
     * @return mixed
     */
    public function getRelatedRecords($hydrationMode = Doctrine::HYDRATE_RECORD)
    {
        return $this->getRelatedRecordsQuery()
                ->execute(array(), $hydrationMode);
    }

    /**
     * Checks wheter object has tag related objects
     * @return integer
     */
    public function hasRelatedRecords()
    {
        return $this->getRelatedRecordsQuery()
                ->count();
    }

    /**
     * Returns doctrine query
     * @return Doctrine_Query
     */
    public function getRelatedRecordsQuery()
    {
        return $this->getInvoker()->getTable()
                ->createQuery('a')
                ->leftJoin('a.Tags t')
                ->whereIn('t.id', $this->getCurrentTagIds())
                ->andWhere('a.id != ?', $this->getInvoker()->get('id'));
    }

    /**
     * Returns ids of tags, the object is tagged with
     * @return array
     */
    public function getCurrentTagIds()
    {
        $tagIds = array();
        foreach($this->getInvoker()->get($this->_options['tagAlias']) as $tag)
        {
            $tagIds[] = $tag->get('id');
        }

        return $tagIds;
    }

    /**
     * Returns tags id's
     * @param mixed $tags
     * @return mixed
     */
    private function getTagIds($tags)
    {
        if(is_string($tags))
        {
            //Get array from stringed tags
            $tagNames = array_unique(array_filter(array_map('trim', explode(',', $tags))));

            $tagsList = array();
            if(!empty($tagNames))
            {
                //Get tag table
                $tagTable = Doctrine_Core::getTable($this->_options['tagClass']);

                foreach($tagNames as $tagName)
                {
                    //check if tag is existing in db
                    $_existingTag = $tagTable
                                    ->createQuery('t')
                                    ->select('t.id')
                                    ->where('t.name = ?', $tagName)
                                    ->limit(1)->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
                    //if tag is not in db, insert tag
                    if(empty($_existingTag))
                    {
                        $tag = new $this->_options['tagClass']();
                        $tag->set('name', $tagName);
                        $tag->save();
                        $tagsList[] = $tag->get('id');
                    }
                    //If it is, add it's id to the array
                    else
                    {
                        $tagsList[] = $_existingTag['id'];
                    }
                }
            }

            return $tagsList;
        }
        elseif(is_array($tags))
        {
            if(is_numeric(current($tags)))
            {
                return $tags;
            }
            else
            {
                return $this->getTagIds(implode(',', $tags));
            }
        }
        elseif($tags instanceof Doctrine_Collection)
        {
            return $tags->getPrimaryKeys();
        }
        else
        {
            throw new Doctrine_Exception('Invalid $tags data provided. Must be a string of tags, an array of tag ids, or a Doctrine_Collection of tag records.');
        }
    }

}

?>
