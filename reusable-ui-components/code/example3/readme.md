#Leveraging partials to keep our code DRY

Here we can see the use of the `get_template_part()` WordPress function as a means of not repeating ourselves. This 
function is an excellent starting point for practicing a DRY approach, but has clear limitations;

- It's a WordPress-specific function, which couples us to WordPress.
- It has no built in mechanism for passing data to the template partial. 
    - This implies that our partials will contain WordPress template tags, which then couples our partial to WordPress.
    - Data could be passed via global vars or via query vars, but that's a little bit messy and open to conflict with 
    other plugins/views that might be doing a similar thing.