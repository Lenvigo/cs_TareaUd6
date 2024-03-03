<?php

class UsuarioServicio
{

    const USER_DOES_NOT_EXIST = "No existe usuario";
    const PWD_INCORRECT = "La contraseña no es correcta";

    /*     En la clase UsuarioServicio:Añade 2 propiedades
            $userRepository de tipo IUserRepository y $rolRepository de tipo IRolRepository
            */

    private IRolRepository $rolRepository;
    private IUsuarioRepository $userRepository;


    /*    Instancia esas 2 propiedades en el constructor vacío  */
    public function __construct()
    {
        $this->rolRepository = new RolRepository();
        $this->userRepository = new UsuarioRepository();
    }


    /*    Implementa el método público  function getUsuarios(): array para que, haciendo uso de los métodos de los repositorios ya creados,
    recupere todos los usuarios de la BD y para cada uno, recupere, a su vez, sus roles, estableciendo para cada objeto Usuario, sus roles a
    través del método setter.
    Deberá devolver un array de objetos Usuario, donde cada objeto Usuario posee ya un array de objetos Rol.
    No es necesario crear métodos nuevos en los repositorios para este apartado. */

    public function getUsuarios(): array
    {
        $usuarios = $this->userRepository->findAll();

        foreach ($usuarios as $usuario) {

            $roles = $this->rolRepository->findRolesByUserId($usuario->getId());

            $usuario->setRoles($roles);
        }

        return $usuarios;
    }

    /*  Implementa el método público function login(string $user, string $pwd, $rolId): ?Usuario para que haciendo uso de los métodos de los repositorios:
    Recupere el usuario filtrando por email.
    Compruebe con password_verify si la contraseña es correcta.
    Si es correcta, deberá recuperar los roles del usuario y establecerlos en el objeto Usuario a través del método setter. Deberá comprobar si el rol indicado en el formulario html está entre los roles permitidos de la BD usando el método isUserInRole ya proporcionado. Si el rol está dentro de los permitidos en BD, devolverá el objeto usuario con los roles incorporados. En caso contrario, devolverá null.
    En caso contrario, devolverá null. */
    
    public function login(string $user, string $pwd, $rolId): ?Usuario
    {
        $esUsuarioValido = false;

        $usuario = $this->userRepository->findUsuarioByEmail($user);

        if ($usuario != null && password_verify($pwd, $usuario->getPwdhash())) {

            $roles = $this->rolRepository->findRolesByUserId($usuario->getId());
            $usuario->setRoles($roles);

            if ($this->isUserInRole($usuario, $rolId)) {
                $esUsuarioValido = true;
            }
        }


        return $esUsuarioValido ? $usuario : null;
    }

    public function getRoles(): array
    {

        $roles = $this->rolRepository->findAll();

        return $roles;
    }

    public function getRoleById(int $roleId): ?Rol
    {

        return $this->rolRepository->read($roleId);
    }

    private function isUserInRole(Usuario $usuario, int $roleId): bool
    {
        $rolesArray = $usuario->getRoles();
        foreach ($rolesArray as $rol) {
            if ($rol->getId() === $roleId) {
                return true;
            }
        }

        return false;
    }
}
