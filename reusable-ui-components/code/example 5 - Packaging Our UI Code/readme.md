#Packaging up our UI related code

This example is basically the same as example 4, but we have now moved all code into a dedicated directory for this UI
component. This makes things much more portable between WordPress projects.

###Things to note;

- All HTML, JS, and Scss related to the view are now packed up together
- The packaged Scss file is now imported into the global.scss file
- The packaged JS file could be concatenated to the global.js file via Grunt/Gulp.
- A new function – `get_package_dir()` – has been added to the mix.