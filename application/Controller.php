<?php 

	abstract class Controller
	{
		/* Obliga a que todas las clases que hereden de Controller, 
		 * implementen un método index() aunque éste no tenga código.
		 */
		abstract public function index();
	}

?>