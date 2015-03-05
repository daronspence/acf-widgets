# ACF Widgets 

Beta Testers: *New!*

If you want to beta test, you can install the plugin from the master branch. I may move over to a develop branch in the future, but for now, master it is. If everything is ok with the plugin, head [over here](http://acfwidgets.com/support/topic/v1-4/) and leave a nice comment. Otherwise, [open an issue](https://github.com/Daronspence/acf-widgets/issues) here on GitHub.

I think it goes without saying, but make a backup of your database before you upgrade, or use a testing server.

Thanks!

*You will now be returned to the scheduled programming.*

ACF Widgets (ACFW) allows users to easily create widgets to use with ACF. With ACFW, you can easily create new widgets without touching any code. No PHP classes or dealing with the Widgets API. After you create a widget, you can assign custom fields within ACF and then use theme templates to easily show the custom fields.

Install:

* Make sure the latest version of ACF is installed and updated. At the time of this writing, that's 5.1.0
* Upload the .zip from the Plugins menu inside of Wordpress.
* Activate the Plugin
* Click on the new admin menu item called "Add New Widgets" located in the "Appearances" menu.
* Create a new "Widget".
* Create a new field group in ACF. Assign it to the type, "widget" -> "Your widget name".
* Add any fields you want to attach to your new widget.
* Add the widget to a sidebar and fill in your fields.
* Create a new file in your theme directory named "widget-your-widget-slug.php". Alternatively, you can name the template file "widget-id.php" where id is the post id of the widget. (note: "widget-" must be prefixed before the slug of your widget name.)
* NEW! Copy one of the convenient templates from the "templates" directory included in this plugin to your theme folder.
* In your newly created template files, call the fields using the normal methods. See http://www.advancedcustomfields.com/resources/get-values-widget/ for more info. 
 - Example. <?php the_field('custom_field', 'widget_'.$widget_id); ?>
* NEW! Template debugging. Navigate to "Settings > ACFW Options" and check the box under debugging. On the front end of your site, any active widgets will tell you what template they are looking for. 

That's it! 

If you're interested, the normal filters are still available like $before and $after widget.

I can't wait to see what people make with this! :)
