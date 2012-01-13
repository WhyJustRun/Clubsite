# WhyJustRun orienteering website

## Database changes needed
   maps:scale should be an int(6), instead of tinytext.
   add memberships:year and memberships:postal_code
   make map_standards and roles shared?
   make events, courses, results shared?

## Todo

* Thomas: Use SMTP relay for mail
* Prevent multiple registrations (verify that is setup)
* Test session sharing
* Schedule of events on sidebar
* Major events on sidebar
* Event page
* CSS Styling!
* Contact us page
* Maps page
* News page (maybe should just be the home page)
* Separate DB for clubs and users shared between instances
* Merge user accounts :S
* WhyJustRun email through CakePHP is getting filtered into Gmail Spam, not arriving consistently
* Formatting on user login page is broken in chrome
* Wider style

## Step by Step Database Migrations

1. Make changes to the test database
2. Change directory to your test instance
3. Run `cake/console/cake migration generate`
4. Look at migrations in app/config/migrations to find the new migration number
5. Enter a migration name of the form: migration number_description
6. When `Do you want compare the schema.php file to the database?` say yes
7. Run 	`cake/console/cake schema generate`
8. Choose overwrite schema file
9. Now your changes will automatically propagate through other instances!
