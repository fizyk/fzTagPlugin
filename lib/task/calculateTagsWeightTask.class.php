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
        $this->detailedDescription = 'This task recalculates weight for all present tags

  [php symfony fzTag:calculate-weight|INFO]';
    }

    protected function execute($arguments = array(), $options = array())
    {
        $this->logSection("Preparing", "Connecting to database");
        $databaseManager = new sfDatabaseManager($this->configuration);
        $this->logSection("Preparing", "Loading tagable models");
        $this->loadTaggableModels();
//        foreach(fzTagTable::getInstance()->getRelations() as $relation => $assocation)
//        {
//            echo $relation;
//        }
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
        foreach( $dirs as $dir )
        {
            $plugins[] = array_pop( explode('/', $dir) );
        }

        // Project Base files
        $files = sfFinder::type('.php')->in(sfConfig::get('sf_lib_dir').'/model/doctrine/base/' );

        // Plugins Base files
        foreach( $plugins as $plugin )
        {
            $files = array_merge( $files,
                               sfFinder::type('.php')->in(sfConfig::get('sf_lib_dir').'/model/doctrine/'.$plugin.'/base/' ) );
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
