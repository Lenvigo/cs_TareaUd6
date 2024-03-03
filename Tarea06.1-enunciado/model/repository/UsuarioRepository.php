<?php

class UsuarioRepository extends BaseRepository
implements IUsuarioRepository
{
    function __construct()
    {
        parent::__construct();
        $this->table_name = "usuario";
        $this->pk_name = "id";
        $this->class_name = "Usuario";
        $this->default_order_column = "email";
    }

    // revisar la funcion y la  consulta

    function findUsuarioByEmail($email):? Usuario
    {
        $consulta = "Select * from". $this->table_name. "WHERE email= :email";
        $pdostmt = $this->conn->prepare($consulta);
        $pdostmt->bindValue(':email', $email);
        $pdostmt->execute();

        $usuario = $pdostmt->fetchObject( $this->class_name);
        return $usuario ?: null;
    }

    public function create($object){return null;}

    public function update($object): bool{return false;}

    //public function read($id);

    //public function delete($id): bool;
    
    //public function findAll(): array ;
}