<?php 

	class postController extends Controller
	{
		private $_post;

		public function __construct() {
			parent::__construct();
			$this->_post = $this->loadModel('post');
		}

		public function index()
		{
			$this->_view->posts = $this->_post->getPosts();
			$this->_view->titulo = 'Post';
			$this->_view->renderizar('index', 'post');
		}

		public function nuevo()
		{
			$this->_view->titulo = 'Nuevo Post';
			$this->_view->setJs(array('nuevo'));

			if($this->getInt('guardar') == 1) {

				// se deberia incluir en una clase que nos devuelva los parámetros POST
				$this->_view->datos = $_POST;

				if(!$this->getTexto('titulo')) {
					$this->_view->_error = 'Debe introducir el título del post';
					$this->_view->renderizar('nuevo', 'post');
					exit;
				}

				if(!$this->getTexto('cuerpo')) {
					$this->_view->_error = 'Debe introducir el cuerpo del post';
					$this->_view->renderizar('nuevo', 'post');
					exit;
				}

				$this->_post->insertarPost (
						$this->getTexto('titulo'),
						$this->getTexto('cuerpo')				
					);
	
				$this->redireccionar('post');
			}

			$this->_view->renderizar('nuevo','post');
		}
	}

?>