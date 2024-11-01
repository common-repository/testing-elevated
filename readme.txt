=== Testing Elevated ===
Contributors: utsavladani
Donate link: https://github.com/Utsav-Ladani/Testing-Elevated
Tags: testing, testing elevated, database, commit, rollback
Requires at least: 5.0
Tested up to: 6.5
Stable tag: 1.0.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Quick testing environment for WordPress. It helps to test the feature and commit or rollback the changes.

== Description ==

# Testing Elevated
Test out various features of your WordPress website effortlessly with this plugin. Unsure about how your site's UI appears or what specific features do? Simply activate this plugin to make changes, view the results, and decide whether to keep them or revert back. Choose the 'Commit' option to save changes or 'Rollback' to undo them.

## How to use

1. Install and activate the plugin.
2. Select the *Start* option from the sidebar.
3. Make the desired changes to your website and view the results.
4. Select the *Rollback* option from the sidebar to revert the changes.
5. Select the *Commit* option from the sidebar to save the changes.

## Caveats
- Not recommended for production sites.
- Always take a backup before making changes.
- Not compatible with plugins storing data outside the WordPress database.
- May contain bugs, use at your own risk. However, we've made efforts to minimize bugs.

## Want to contribute?

1. [Fork the repository](https://github.com/Utsav-Ladani/Testing-Elevated). and clone it on your local machine.
2. Create a new branch and make changes.
3. Commit and push the changes.
4. Raise a PR and wait for the review.
5. If everything is good, your PR will be merged.

## Support

If you have any queries or need help, feel free to ask us on [Testing Elevated](https://github.com/Utsav-Ladani/Testing-Elevated) GitHub repository.

== Screenshots ==

1. Testing Elevated plugin sidebar menu.


== Frequently Asked Questions ==

= Does this plugin use file-based storage? =

Yes, this plugin uses the `queries.json` file to store the data.

= Does this plugin work with all plugins? =

No, this plugin may not work with plugins that are storing/modifying data outside the WordPress or using `db.php` drop-in.

= Does this plugin save/roll back file changes? =

No, this plugin only saves/rolls back the database changes.


== Changelog ==

= 1.0.0 =
* First release :)

== Upgrade Notice ==

= 1.0.0 =
* First release :)
