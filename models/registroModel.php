<?php

class registroModel extends Model
{
    public function __construct() {
        parent::__construct();
    }
    
    public function verificarUsuario($usuario)
    {
        $id = $this->_db->query(
                "select id,codigo from usuarios where usuario = '$usuario'"
                );
        
        return $id->fetch();
    }
    
    public function verificarEmail($email)
    {
        $id = $this->_db->query(
                "select id from usuarios where email = '$email'"
                );
        
        if($id->fetch()){
            return true;
        }
        
        return false;
    }
    
    public function registrarUsuario($nombre, $usuario, $password, $email)
    {
        $random = rand(1234987651, 9999999999);

        $this->_db->prepare(
                "insert into usuarios values" .
                "(null, :nombre, :usuario, :password, :email, 'usuario', 0, now(), :codigo)"
                )
                ->execute(array(
                    ':nombre' => $nombre,
                    ':usuario' => $usuario,
                    ':password' => Hash::getHash('sha1', $password, HASH_KEY),
                    ':email' => $email,
                    ':codigo' => $random
                ));
    }

    public function getUsuario($id, $codigo)
    {
        $usuario = $this->_db->query(
            "SELECT * FROM usuarios WHERE id = $id AND codigo = '$codigo'"
            );

        return $usuario->fetch();
    }

    public function activarUsuario($id, $codigo)
    {
        $this->_db->query(
            "UPDATE usuarios SET estado = 1 WHERE id = $id AND codigo = '$codigo'"
            );
    } 
}

?>
