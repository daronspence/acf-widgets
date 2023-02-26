# ACF Widgets 

## Warning: This plugin is deprecated.

As of Jan 2023, this plugin is now deprecated. It was a great 8+ year run but I've moved on from working in the WordPress ecosystem. Thank you to everyone who has contributed bug fixes and all of the cool stuff that you built with the plugin over the years.

--------

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

<details>
 <summary>Old Documentation</summary>
 
### Creating New Widgets
 
Navigate to Appearance > Add New Widgets and then click Add Widget button next to the title of the page. Give your widget a title and an optional description. After publishing, the widget will be available like any other widget.

### Assigning Fields to a Widget

In the ACF admin area, create a new Field Group. Add any of the fields that you want for your new widget. Then under the Location meta box, set it equal to Widget is equal to Your Custom Widget. The process is identical for 3rd Party Widgets.

### Creating & Using Templates

To show your widgets custom fields, you will first need to create a template file and include it in your theme. You can find the required name for the widget in the admin area of your website. Navigate to Appearance > Add New Widgets and the name of the template is shown in the second column across from the title of it’s respective widget. The normal format of templates is equal to widget-slug.php though you can optionally use widget-id.php. Note, all widget templates are prefixed with widget- and end with a .php extension. For 3rd Party Widgets, only the widget-slug.php template is searched for. You can find the required template name for 3rd Party Widgets by enabling Template Debugging on the Settings > ACFW Options page. For WordPress widgets, there are pre-named templates bundled with the plugin in the templates folder in the plugin root.

Inside of your templates you can use any combination of tags available to normal PHP files. To retrieve values from your custom fields, you can use the normal ACF API. One example would be
<?php the_field('CUSTOM_FIELD_NAME', $acfw); ?>. Note you must pass in the predefined $acfw variable as the second parameter to any ACF function used within widget templates. This tell’s ACF to look for the fields associated with that particular instance of your widget. Since there is no limitation to the amount of widgets that can be shown at any time, this differentiates the widgets from other instances of the same type.

### Where to Display Custom Fields

You will notice that when assigning widgets to a sidebar, standard WordPress Widgets and 3rd Party Widgets have an additional field added by the plugin. For WordPress widgets, you can display your custom fields above the widget, below the title, or below the widget. Since all third party widgets do not include a title field, that option has been removed.
</details>

If you're interested, the normal filters are still available like $before and $after widget.

Those with a license can get support from the [Official ACFW Support Forums](http://acfwidgets.com/support/). Priority support is also available for those holding a "Developer" license of the plugin.

If you're interested in translating, [open an issue](https://github.com/Daronspence/acf-widgets/issues) and we'll work something out :)
