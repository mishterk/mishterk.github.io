# Package Management

This example is almost identical to the previous, except we have moved our packages into a Composer library called
**GroovyUI**.

###Key differences in this implementation;

- All packaged class includes in `functions.php` have been replaced by a single include of the Composer `autoload.php`
- Namespaces in UI packages have changed
- Implemntation in templates is exactly the same, only namespaces have changed
- Import paths have changed in the `scss/global.scss` file

###Major advantages

- Our Composer UI library can now have it's own repository
- Composer now does the heavy lifting as far as autoloading, installing, updating, and managing dependencies
