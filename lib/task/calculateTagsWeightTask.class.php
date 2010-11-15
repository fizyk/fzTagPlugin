<?php

/**
 * @author Grzegorz Śliwiński
 */
class calculateTagsWeightTask extends sfBaseTask
{

    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
                // add your own options here
        ));

        $this->namespace = 'fzTag';
        $this->name = 'calculate-weight';
        $this->briefDescription = 'Recalculates weight for tags';
        $this->detailedDescription = 'This task recalculates weight for all present tags.
            It is heavily based on diem method to retrieve popular tags method. 
            Unlike in diem, fzTag utilises task to calculate weight, so it will
            not consume too much memory by loading all taggable models

  [php symfony fzTag:calculate-weight|INFO]';
    }

    protected function execute($arguments = array(), $options = array())
    {
        $this->logSection("Preparing", "Connecting to database");
        $databaseManager = new sfDatabaseManager($this->configuration);
        $this->logSection("Preparing", "Loading tagable models");
        // Loading all taggable models
        $this->loadTaggableModels();

        // getting all fzTag relations
        $relations = fzTagTable::getInstance()->getRelations();

        if(!empty($relations))
        {
            // building query to determine tags weight
            $this->logSection('fzTag', 'Fetching tags');
            $query = fzTagTable::getInstance()->createQuery('t')->select('t.*');
            $counts = array();
            foreach($relations as $relation)
            {
                $query->leftJoin('t.'.$relation->getAlias().' '.$relation->getAlias());
                $counts[] = 'COUNT(DISTINCT '.$relation->getAlias().'.id)';
            }
            $query->addSelect('(' . implode(' + ', $counts) . ') as count_total')->groupBy('t.id');
            $this->logSection('fzTag', 'Executing query');
            $tags = $query->execute(array(), Doctrine_Core::HYDRATE_ON_DEMAND);

            $this->logSection('fzTag', 'Saving calculated weights');
            // update each tag
            foreach( $tags as $tag )
            {
                $tag->setWeight( $tag->getCountTotal() );
                $tag->save();
            }
        }
        else
        {
            $this->logSection('fzTag', 'No relations');
        }
        $this->logSection('fzTag', 'Finished');
    }

    /**
     * This function loads all taggable models.
     * It is necessary to retrieve all Relations for Tags.
     * Loosely based on DmTaggable loadTaggable functionality
     */
    private function loadTaggableModels()
    {
        // This part finds out all plugins from plugins directory
        $dirs = sfFinder::type('dir')->maxdepth(0)
                        ->discard('.*')->ignore_version_control()
                        ->in(sfConfig::get('sf_plugins_dir'));
        $plugins = array();
        // here we'll take just folders names (plugin names)
        foreach($dirs as $dir)
        {
            $plugins[] = array_pop(explode('/', $dir));
        }

        // Project Base files
        $files = sfFinder::type('.php')->in(sfConfig::get('sf_lib_dir').'/model/doctrine/base/');

        // Plugins Base files
        foreach($plugins as $plugin)
        {
            $files = array_merge($files,
                            sfFinder::type('.php')->in(sfConfig::get('sf_lib_dir').'/model/doctrine/'.$plugin.'/base/'));
        }


        foreach($files as $modelBaseFile)
        {
            if(strpos(file_get_contents($modelBaseFile), 'new fzTaggable('))
            {
                Doctrine_Core::getTable(
                                preg_replace('|^Base(\w+).class.php$|', '$1', basename($modelBaseFile)));
            }
        }
    }

}
