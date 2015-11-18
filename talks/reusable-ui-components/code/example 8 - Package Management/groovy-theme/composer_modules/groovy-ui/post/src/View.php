<?php

namespace GroovyUI\Post;

/**
 * Class View
 * @package GroovyUI\Post
 *
 * Responsible for rendering the view with the viewmodel in context
 */
class View {

	protected $view_model;

	/**
	 * View constructor.
	 *
	 * Expects an object of type View_Model which is required for the template.
	 *
	 * @param \GroovyUI\Post\View_Model $view_model
	 */
	public function __construct( View_Model $view_model )
	{
		$this->view_model = $view_model;
	}

	/**
	 * Sets up the $data variable for use within the template and includes the template in the same context. Catches the
	 * output then returns it for echoing.
	 *
	 * @return string
	 */
	public function render()
	{
		$data = $this->view_model;
		ob_start();
		include( dirname( __FILE__ ) . '/template.php' );
		$markup = ob_get_clean();

		return $markup;
	}
}