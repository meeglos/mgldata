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
        if(Session::get('autenticado')){
            $this->redireccionar();
        }
        
        $this->_view->titulo = 'Registro';
        
        if($this->getInt('enviar') == 1){
            $this->_view->datos = $_POST;
            
            if(!$this->getSql('nombre')){
                $this->_view->_error = 'Debe introducir su nombre';
                $this->_view->renderizar('index', 'registro');
                exit;
            }
            
            if(!$this->getAlphaNum('usuario')){
                $this->_view->_error = 'Debe introducir su nombre usuario';
                $this->_view->renderizar('index', 'registro');
                exit;
            }
            
            if($this->_registro->verificarUsuario($this->getAlphaNum('usuario'))){
                $this->_view->_error = 'El usuario ' . $this->getAlphaNum('usuario') . ' ya existe';
                $this->_view->renderizar('index', 'registro');
                exit;
            }
            
            if(!$this->validarEmail($this->getPostParam('email'))){
                $this->_view->_error = 'La direccion de email es inv&aacute;lida';
                $this->_view->renderizar('index', 'registro');
                exit;
            }
            
            if($this->_registro->verificarEmail($this->getPostParam('email'))){
                $this->_view->_error = 'Esta direccion de correo ya esta registrada';
                $this->_view->renderizar('index', 'registro');
                exit;
            }
            
            if(!$this->getSql('pass')){
                $this->_view->_error = 'Debe introducir su password';
                $this->_view->renderizar('index', 'registro');
                exit;
            }
            
            if($this->getPostParam('pass') != $this->getPostParam('confirmar')){
                $this->_view->_error = 'Los passwords no coinciden';
                $this->_view->renderizar('index', 'registro');
                exit;
            }

            $this->getLibrary('class.phpmailer');
            $mail = new PHPMailer();
            
            $this->_registro->registrarUsuario(
                    $this->getSql('nombre'),
                    $this->getAlphaNum('usuario'),
                    $this->getSql('pass'),
                    $this->getPostParam('email')
                    );
            
            $usuario = $this->_registro->verificarUsuario($this->getAlphaNum('usuario'));

             if(!$usuario){
                $this->_view->_error = 'Error al registrar el usuario';
                $this->_view->renderizar('index', 'registro');
                exit;
            }

            $mail->From = 'test@miglos-lab.com';
            $mail->FromName = 'Proyecto PHP/MVC';
            $mail->Subject = 'Activación cuenta de usuario';
            $mail->Body = 'Hola <strong>' . $this->getSql('nombre') . '</strong>,' .
                            '<p>Se ha registrado en www.miglos-lab.com, para activar su ' .
                            'cuenta haga clic sobre el siguiente enlace: <br>' .
                            '<a href="' . BASE_URL . 'registro/activar/' .
                            $usuario['id'] . '/' . $usuario['codigo'] . '">' .
                            BASE_URL . 'registro/activar/' .
                            $usuario['id'] . '/' . $usuario['codigo'] .'</a></p>';
            
            $mail->AltBody = 'Su servidor de correo no soporta html';
            $mail->addAddress($this->getPostParam('email'));
            $mail->Send();             

            $this->_view->datos = false;
            $this->_view->_mensaje = 'Registro Completado, revise su email para activar su cuenta';
        }        
        
        $this->_view->renderizar('index', 'registro');
    }

    public function activar($id, $codigo)
    {
        $this->_view->titulo = 'Activaci&oacute;n de cuenta';
        
        if(!$this->filtrarInt($id) || !$this->filtrarInt($codigo)) {
            $this->_view->_error = 'Esta cuenta no existe';
            $this->_view->renderizar('activar', 'registro');
            exit;    
        }

        $row = $this->_registro->getUsuario(
                    $this->filtrarInt($id),
                    $this->filtrarInt($codigo)
                    );

        if(!$row) {
            $this->_view->_error = 'Esta cuenta no existe';
            $this->_view->renderizar('activar', 'registro');
            exit;        
        }

        if($row['estado'] == 1) {
            $this->_view->_error = 'Esta cuenta ya ha sido activada';
            $this->_view->renderizar('activar', 'registro');
            exit;    
        }

        $this->_registro->activarUsuario(
                        $this->filtrarInt($id),
                        $this->filtrarInt($codigo)
                        );

        $row = $this->_registro->getUsuario(
                        $this->filtrarInt($id),
                        $this->filtrarInt($codigo)
                        );

        if($row['estado'] == 0) {
            $this->_view->_error = 'Error al activar la cuenta, vuelva a intentarlo';
            $this->_view->renderizar('activar', 'registro');
            exit;    
        }

        $this->_view->_mensaje = 'Su cuenta ha sido activada.';
        $this->_view->renderizar('activar', 'registro');
    }
}

?>
