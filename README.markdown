fzTagPlugin
=================

This plugin provides similar functionality to that provided by
[dmTagPlugin](http://diem-project.org/plugins/dmtagplugin) for
[Diem CMF](http://diem-project.org/). It's in fact backported dmTagPlugin, with
few tweaks more than just clearing all diem specific code:

* Uses non modified fcbkcomplete library (http://www.emposha.com/javascript/fcbkcomplete.html)
* Uses modified sfWidgetFormDoctrineFBAutocompleter widget from
[sfDoctrineFBAutocompletePlugin](http://www.symfony-project.org/plugins/sfDoctrineFBAutocompletePlugin)
version 0.1.3


Installation
------------

To install plugin from symfony plugin repository run:

    ./symfony plugin:install fzTagPlugin

To install plugin from package, copy it to your project root's directory and run:

    ./symfony plugin:install fzTagPlugin-1.0.0.tgz

After installing, you have to run:

    ./symfony doctrine:generate-migrations-diff
    ./symfony doctrine:migrate
    ./symfony doctrine:build --all-classes

Now you're ready to configure and use tags for your project

Configuration
------------

Some basic configuration is being done automaticlly:

* Route is being registered when you enable fzTagAutocomplete module for app
* Tag widget is being added, when you add fzTaggable behaviour to your model.

###Behaviour###

To add taggable behaviour to your model edit your schema.yml file:

*/config/doctrine/schema.yml*

    Model:
      actAs:
        #Other behaviours
        fzTaggable:
        #Other behaviours
      columns:
        #Columns

After adding behaviour run:

    ./symfony doctrine:generate-migrations-diff
    ./symfony doctrine:migrate
    ./symfony doctrine:build --all-classes

###FCBKcomplete###

Here are settings for fcbkcomplete script that can be set for per app basis:

    all:
      fzTagPlugin:
        fcbkcomplete:           # options to pass to the jQuery.fcbkcomplete plugin
                                # see http://www.emposha.com/javascript/fcbkcomplete.html
          newel:          true  # show typed text like a element
          filter_case:    false # case sensitive filter
          filter_hide:    false # show/hide filtered items
          maxshownitems:  30    # maximum numbers that will be shown at drop-down list (less better performance)
          maxitems:             # maximum item that can be added to the list
          firstselected:  false # automatically select first element from drop-down
          complete_text: "Start to type..."