<?php
namespace GroovyTheme;

/**
 * Get Template Part
 *
 * Locates and includes a template partial within the same scope as a data object/array. This makes it possible to
 * access raw data in the template.
 *
 * Note: Any data passed into this function will be casted as an array and then as an object. The final data available
 * within a template is in the form of an object with the variable name $data.
 *
 * e.g.
 *
 *      array('name' => 'Bob', 'age' => 42)
 *
 * Will be converted to an object to be used as;
 *
 *      $data->name
 *      $data->age
 *
 * @param   string $slug The template slug (filename without the extension)
 * @param   string|null $name A named variation for the template. This is in the form {$slug}-{$name}.php
 * @param   object|array $data An associative array or object to use inside the template.
 * @param   bool|true $include
 *
 * @return  bool|string
 */
function get_template_part( $slug, $name = null, $data = array(), $include = true )
{
	// if data is not already an object, cast as object
	if ( ! is_object( $data ) ) {
		$data = (object) (array) $data;
	}

	// prepare template hierarchy
	$templates = array();
	$name      = (string) $name;
	if ( '' !== $name ) {
		$templates[] = "{$slug}-{$name}.php";
	}
	$templates[] = "{$slug}.php";

	// include template
	if ( $t = locate_template( $templates, false, false ) ) {
		ob_start();
		include $t;
		$markup = ob_get_clean();
		if ( $include ) {
			echo $markup;
		} else {
			return $markup;
		}
	}

	return false;
}