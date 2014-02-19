<?php 

	class registroController extends Controller
	{
		private $_registro;

		public function __construct() {
			parent::__construct();
		
			$this->_registro = $this->loadModel('registro');
		}

		public function index()
		{
			if(Session::get('autenticado')) {
				$this->redireccionar();
			}

			$this->_view->titulo = 'Registro';

			if($this->getInt('enviar') == 1) {
				$this->_view->datos = $_POST;
				// $this->_view->_error = 'Formulario enviado';

				if(!$this->getSql('nombre')) {					
					$this->_view->_error = 'Debe ingresar su nombre';
					$this->_view->renderizar('index', 'registro');
					exit;
				}

				if(!$this->getAlphaNum('usuario')) {					
					$this->_view->_error = 'Debe ingresar su usuario';
					$this->_view->renderizar('index', 'registro');
					exit;
				}

				if($this->_registro->verificarUsuario($this->getAlphaNum('usuario'))) {					
					$this->_view->_error = 'El usuario <b>"' . $this->getAlphaNum('usuario') . '"</b> ya existe';
					$this->_view->renderizar('index', 'registro');
					exit;
				}

				if(!$this->validarEmail($this->getPostParam('email'))) {
					$this->_view->_error = 'La direcci&oacute;n de email es inv&aacute;lida';
					$this->_view->renderizar('index','registro');
					exit;
				}

				if(!$this->getSql('pass')) {					
					$this->_view->_error = 'Debe ingresar su password';
					$this->_view->renderizar('index', 'registro');
					exit;
				}
				
				if(!$this->getPostParam('pass') != $this->getPostParam('confirmar')) {					
					$this->_view->_error = 'Los passwords nocoinciden';
					$this->_view->renderizar('index', 'registro');
					exit;
				}

				$this->_registro->registrarUsuario(
					$this->getSql('nombre'),
					$this->getAlphaNum('usuario'),
					$this->getSql('pass'),
					$this->getPostParam('email')
					);

				if($this->_registro->verificarUsuario($this->getAlphaNum('usuario'))) {
					$this->_view->_mensaje = 'Registro Completado';
					$this->_view->renderizar('index', 'registro');
					exit;
				}
			}

			$this->_view->renderizar('index', 'registro');
		}

	}

 ?>