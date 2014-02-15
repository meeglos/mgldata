<?php 

	abstract class Controller
	{
		protected $_view;

		public function __construct()
		{
			$this->_view = new View(new Request);
		}
		/* Obliga a que todas las clases que hereden de Controller, 
		 * implementen un método index() aunque éste no tenga código.
		 */
		abstract public function index();
	}
	/**
	 * Added this comment line to test the branching and merging effects on git
	 */	

?>