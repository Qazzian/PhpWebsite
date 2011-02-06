<?php

class Layout {

	/** Name of the template, Used to derive lots of info about the layout. */
	protected $name;

	/**
	 * The name of the file which defines the template.
	 * This file will be included once the server is ready to start printing the content.
	 * It should contain calls to the static methods defined below
	 */
	protected $template;

	protected $path;

	/** The Content to stick in the middle of the page, passed from the ContentManager. */
	protected $content;

	/**
	 * An array of menus, in order of precedence.
	 * The layout will choose how to display them.
	 * The content will need to define which menus it is under in order to activate the 'selected' class.
	 * The ContentManager Defines which menus are available to display.
	 */
	protected $menus;
	protected $menu_index = 0;

	/**
	 * hash of options and values.
	 * The layout object can use this to record any options it supports.
	 * The content object should have a property 'layout_options' whish the layout will read before generating content
	 */
	protected $options = array();

	/** Any other property */
	protected $properties = array();

	public function __construct(array $params){
		foreach ($params as $key => $value){
			$this->$key = $value;
		}
		$this->path = LayoutFactory::getLayoutDir($this->name);
	}

	public function __set($param, $value){
		$this->properties[$param] = $value;
	}

	public function __get($param){
		if ( array_key_exists($param, $this->properties) ) {
			return $this->properties[$param];
		}
	}

	public function __isset($arg){
		return isset($this->properties[$arg]);
	}

	public function __unset($arg){
		unset($this->properties[$arg]);
	}

	public function attach_content($content){
		$this->content = $content;

		return $this;
	}

	/**
	 * Called by the ContentManager. This starts the display of the page.
	 */
	public function buildPage(){
		if (!$this->_has_content()){
			throw new Exception("No content to build");
		}
		$this->_include_template();
		return $this;
	}

	private function _has_content(){
		return $this->content ? true : false;
	}

	private function _include_template(){
		include ($this->path . '/' . $this->template);
	}

	#############################
	### Functions called by the layout template ###
	#############################

	/**
	* Prints the Next menu in the $menus array.
	*/
	public function buildNextMenu(){
		$this->buildMenu($this->menu_index);
		$this->menu_index ++;
	}

	/**
	* Builds the menu of the Specified level. If there is no menu defined at this level will not print anything.
	*/
	public function buildMenu($level){
		if (!isset($this->menus))
		{
			$this->menus = $this->content->menus;
		}
		site_log ("Menu number $level");
		if (!isset($this->menus[$level])) {
			site_log("Menu $level not defined");
			return '';
		}
		site_log($this->menus[$level]);
		$menu = new Menu($this->menus[$level]);
		return $menu->toString();

	}

	/**
	 * Called from within the template. This will include the content from the Content object.
	 */
	public function printContent(){
		echo $this->content->content;
	}


	#
	# Layout Options
	#

	/** Returns the value of the named option */
	public function getOption($option_name){ }

	/** Returns a hash of the names options and their current values. */
	public function getOptions(array $option_names){ }

	/**
	* Called by a management program to tell the user what options are available for setting
	* and the possible values.
	*/
	public function getAcceptedOptions(){ }

	/** Called by a template Instance. used to set the available options that the template can accept. */
	protected function setAcceptedOptions($hash){ }

	/** */
	public function setOption($hash_ref){ }

	/** */
	public function setOptions($hash_ref){ }

	/** If the given Content instance has any templateOptions defined, use them. */
	public function setOptionsFromContent(){ }

}

?>