# ACF Widgets 

ACF Widgets (ACFW) allows users to easily create widgets to use with ACF. With ACFW, you can easily create new widgets without touching any code. No PHP classes or dealing with the Widgets API. After you create a widget, you can assign custom fields within ACF and then use theme templates to easily show the custom fields.

Install:

* Make sure the latest version of ACF Pro is installed and updated. At the time of this writing, that's 5.2.9
* Upload the .zip from the Plugins menu inside of Wordpress or the unzipped files via S/FTP
* Activate the Plugin
* Click on the new admin menu item called "Add New Widgets" located in the "Appearances" menu.
* Create a new "Widget".
* Create a new field group in ACF. Assign it to the type, "widget" -> "Your widget name".
* Add any fields you want to attach to your new widget.
* Add the widget to a sidebar and fill in your fields.
* Create a new file in your theme directory named `widget-mycool-slug.php`. Alternatively, you can name the template file `widget-id.php` where id is the post id of the widget. (note: `widget-` must be prefixed before the slug of your widget name.)
* NEW! Copy one of the convenient templates from the "templates" directory included in this plugin to your theme folder.
* In your newly created template files, call the fields using the normal methods. See http://www.advancedcustomfields.com/resources/get-values-widget/ for more info. 
 - Example. `<?php the_field( 'custom_field', $acfw ); ?>` The `$acfw` variable is automatically mapped for you to use.
* NEW! Template debugging. Navigate to "Settings > ACFW Options" and check the box under debugging. On the front end of your site, any active widgets will tell you what template they are looking for. 

That's it! 

If you're interested, the normal filters are still available like $before and $after widget.

Those with a license can get support from the [Official ACFW Support Forums](http://acfwidgets.com/support/). Priority support is also available for those holding a "Developer" license of the plugin.

If you're interested in translating, [open an issue](https://github.com/Daronspence/acf-widgets/issues) and we'll work something out :)