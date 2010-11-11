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

    ./symfony plugin:install fzTagPlugin-1.0.3.tgz

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


###Autocomplete widget appearance###

If you want to show autocomplete widget to the user, first of all, you have to
enable *fzTagAutocomplete* module for the app, that the widget has to be included:

    all:
      .settings:
        # Some settings
        enabled_modules: [default, fzTagAutocomplete]

Without this, autocomplete widget wouldn't be included into the form of taggable
object. Enabling this module will also append a route that the widget is getting
tags from.

There might be however some cases, where you wouldn't like the autocomplete widget to show
for specified form only, if that's the case, all you need to do, is to unset
tags_list field in your form's configure() method:

    public function configure()
    {
        unset( $this['tags_list'] );
    }

###FCBKcomplete###

To be able to use FCBKcomplete, you have to include jQuery library. Either locally,
or from [Google code](http://www.google.pl/url?q=http://code.google.com/apis/ajaxlibs/).
FCBKcomplete won't be working without it!

Here are settings for fcbkcomplete script that can be set for tagging per app basis:

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

Unlike in dmTagPlugin, the *complete_text* parameter is configured through i18n string.

###Others###

There's an admin module, that you can activate in your backend app:

    all:
      .settings:
        # Some settings
        enabled_modules: [default, fzTagAdmin]

The route for the fzTagAdmin module is fz_tag and will be added to your app when
the fzTagAdmin module is enabled.

###Tasks###

* fzTag:calculate-weight - this task should update each tag's count attribiute - number indicating how many times tag was used. Depends on how many tags and taggable models are in project it may take quite a lot of time and/or memory to do that.

###Compinents###

fzTag comes with two components:

* list - is a simple component showing list of tags
* tagCloud - is a simple tag cloud component. By default it shows 20 most popular tags ordered by their popularity.

Libraries
------------
Emposha's [FCBKcomplete](http://github.com/emposha/FCBKcomplete) (2.7.4)

Thanks
------------

Great thanks to Thibault Duplesis, who developed the original
[dmTagPlugin](http://diem-project.org/plugins/dmtagplugin) for
[Diem CMF](http://diem-project.org/)