<?php

namespace GroovyUI\Post;

/**
 * Class View_Model
 * @package GroovyUI\Post
 *
 * Handles some WordPress specific tasks
 */
class WordPress_View_Model extends View_Model {

	public function __construct(\WP_Post $post)
	{
		$this->title = get_the_title($post);
		$this->excerpt = $post->post_excerpt;
		$this->link = get_permalink($post);

		$author = get_userdata($post->post_author);
		$this->author = $author->user_nicename;
	}

}