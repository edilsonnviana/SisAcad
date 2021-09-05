<?php

require_once 'app/model/TipoUsuario.php';

class User extends TipoUsuario {

    private $idusuario;
    private $tipoUsuario;
    private $nomeUsuario;
    private $cpf;
    private $dataNascimento;
    private $sexo;
    private $telefone;
    private $telefone2;
    private $email;
    private $endereco;
    private $login;
    private $senha;
    private $descricao;
    private $tipousuario;

    function getIdusuario() {
        return $this->idusuario;
    }

    function getTipoUsuario() {
        return $this->tipoUsuario;
    }

    function getNomeUsuario() {
        return $this->nomeUsuario;
    }

    function getCpf() {
        return $this->cpf;
    }

    function getDataNascimento() {
        return $this->dataNascimento;
    }

    function getSexo() {
        return $this->sexo;
    }

    function getTelefone() {
        return $this->telefone;
    }

    function getTelefone2() {
        return $this->telefone2;
    }

    function getEmail() {
        return $this->email;
    }

    function getEndereco() {
        return $this->endereco;
    }

    function getLogin() {
        return $this->login;
    }

    function getSenha() {
        return $this->senha;
    }

    function getDescricao() {
        return $this->descricao;
    }

    function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function __set($atrib, $value) {
        $this->$atrib = $value;
    }

    public function validateLogin() {
        $conn = Database\Connection::getConn();
        $sql = 'SELECT idusuario,tipoUsuario,nomeUsuario,login,senha 
            FROM usuario WHERE login = :login and tipoUsuario <> 3 ';
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':login', $this->login);
        $stmt->execute();
        if ($stmt->rowCount()) {
            $result = $stmt->fetch();
            if ($result['senha'] === $this->senha) {
                $_SESSION['usr'] = array(
                    'id_user' => $result['idusuario'],
                    'name_user' => $result['nomeUsuario'],
                    'tipoUsuario' => $result['tipoUsuario'],
                    'idusuario' => $result['idusuario']
                );

                return true;
            }
        }
        throw new \Exception('Login Inválido');
    }

    public function inserir() {
        $conn = Database\Connection::getConn();
        $sql = 'INSERT INTO usuario (tipoUsuario, nomeUsuario, cpf, 
                dataNascimento, sexo, telefone, telefone2, email, endereco, login, senha) 
                VALUES (:tipousuario, :nome, :cpf, :dtnasc, :sexo, :tel, :tel2,
                 :email, :endereco, :login, :senha)';
        $smt = $conn->prepare($sql);
        $smt->bindValue('tipousuario', $this->tipoUsuario);
        $smt->bindValue('nome', $this->nomeUsuario);
        $smt->bindValue('cpf', $this->cpf);
        $smt->bindValue('dtnasc', $this->dataNascimento);
        $smt->bindValue('sexo', $this->sexo);
        $smt->bindValue('tel', $this->telefone);
        $smt->bindValue('tel2', $this->telefone2);
        $smt->bindValue('email', $this->email);
        $smt->bindValue('endereco', $this->endereco);
        $smt->bindValue('login', $this->login);
        $smt->bindValue('senha', $this->senha);

        if ($smt->execute()) {
            if ($smt->rowCount()) {
                
            }
        }
    }

    public function inserirCliente() {
        $conn = Database\Connection::getConn();
        $sql = 'INSERT INTO usuario (tipoUsuario, nomeUsuario, cpf, 
                dataNascimento, sexo, telefone, telefone2, email, endereco) 
                VALUES (:tipousuario, :nome, :cpf, :dtnasc, :sexo, :tel, :tel2,
                 :email, :endereco)';
        $smt = $conn->prepare($sql);
        $smt->bindValue('tipousuario', $this->tipoUsuario);
        $smt->bindValue('nome', $this->nomeUsuario);
        $smt->bindValue('cpf', $this->cpf);
        $smt->bindValue('dtnasc', $this->dataNascimento);
        $smt->bindValue('sexo', $this->sexo);
        $smt->bindValue('tel', $this->telefone);
        $smt->bindValue('tel2', $this->telefone2);
        $smt->bindValue('email', $this->email);
        $smt->bindValue('endereco', $this->endereco);
        if ($smt->execute()) {
            if ($smt->rowCount()) {
                
            }
        }
    }

    public function buscar() {
        $conn = Database\Connection::getConn();

        $sql = "select idusuario,nomeUsuario,cpf,telefone,email,endereco,descricao
             from usuario left join
             tipousuario on tipousuario = idtipoUsuario 
             where tipousuario <> 3 and tipoUsuario <>
             4 order by descricao asc";
        $sql = $conn->prepare($sql);
        $sql->execute();
        $resultado = array();
        while ($row = $sql->fetchObject('User')) {
            $resultado[] = $row;
        }
        return $resultado;
    }

    public function buscarDes() {
        $conn = Database\Connection::getConn();

        $sql = "select idusuario,nomeUsuario,cpf,telefone,email,endereco,descricao 
             from usuario left join
             tipousuario on tipousuario = idtipoUsuario 
             where tipoUsuario = 3 ";

        $sql = $conn->prepare($sql);
        $sql->execute();
        $resultado = array();
        while ($row = $sql->fetchObject('User')) {
            $resultado[] = $row;
        }
        return $resultado;
    }

    public function buscarId($idpost) {

        $conn = Database\Connection::getConn();

        $sql = "select idusuario,nomeUsuario,cpf,telefone,telefone2
            ,email,endereco,descricao,sexo,date_format(dataNascimento,'%d/%m/%Y')dataNascimento,senha
             from usuario left join
             tipousuario on tipousuario = idtipoUsuario 
             where idusuario = :id";
        $sql = $conn->prepare($sql);
        $sql->bindValue(':id', $idpost, PDO::PARAM_INT);
        $sql->execute();

        $resultado = $sql->fetchObject('User');

        if (!$resultado) {
            throw new Exception("Não foi encontrado nenhum registro");
        }
        return $resultado;
    }

    public function desabilitaFunc($id) {
        $conn = Database\Connection::getConn();
        $sql = "UPDATE usuario SET tipoUsuario = 3 WHERE idusuario = :id";

        $sql = $conn->prepare($sql);
        $sql->bindValue(':id', $id);
        $resultado = $sql->execute();
        if ($resultado == 0) {
            throw new Exception("falha ao desabilitar");
            return FALSE;
        }
        return TRUE;
    }

    public function editaFunc($parans) {
        $conn = Database\Connection::getConn();
        $sql = "UPDATE usuario SET  nomeUsuario = :nome,cpf = :cpf,sexo = :sexo,"
                . "telefone = :tel,telefone2 = :tel2,email = :email,endereco = :endereco"
                . " where idusuario = :id";
        $sql = $conn->prepare($sql);
        $sql->bindValue(':nome', $parans['nome']);
        $sql->bindValue(':cpf', $parans['cpf']);
      
        $sql->bindValue(':sexo', $parans['sexo']);
        $sql->bindValue(':tel', $parans['tel']);
        $sql->bindValue(':tel2', $parans['tel2']);
        $sql->bindValue(':email', $parans['email']);
        $sql->bindValue(':endereco', $parans['endereco']);
        $sql->bindValue(':id', $parans['id']);

        $resultado = $sql->execute();

        if ($resultado == 0) {

            echo '<script>alert("falha ao editar");</script>';
            echo '<script>location.href="http://localhost/SisAcad2.0/dashboard/telaCliente"</script>';
            return FALSE;
        }
        return TRUE;
    }

    public function editaFuncDes($parans) {
        $conn = Database\Connection::getConn();
        $sql = "UPDATE usuario SET  tipoUsuario = :tipoUsuario,login = :login, senha = :senha"
                . " where idusuario = :id";
        $sql = $conn->prepare($sql);
        $sql->bindValue(':tipoUsuario', $parans['tipoUsuario']);
        $sql->bindValue(':login', $parans['login']);
        $sql->bindValue(':senha', $parans['senha']);
        $sql->bindValue(':id', $parans['id']);

        $resultado = $sql->execute();

        if ($resultado == 0) {
            throw new Exception("falha ao editar");
            return FALSE;
        }
        return TRUE;
    }

    public function buscarCliente() {
        $conn = Database\Connection::getConn();
        $sql = $conn->prepare("select * from usuario u where tipoUsuario = 4 and "
                . " not exists ( select * from caixa c where u.idusuario = c.nomeCliente)");
        $sql->execute();
        $resultado = array();
        while ($row = $sql->fetchObject('User')) {
            $resultado[] = $row;
        }
        return $resultado;
    }
    public function editarSenha($paras) {
        $conn = Database\Connection::getConn();

        $sql = "UPDATE usuario SET senha = :senha where idusuario = :id";
              
        $sql = $conn->prepare($sql);
        $sql->bindValue(':senha', $paras['senha1']);     
        $sql->bindValue(':id', $paras['id']);

        $resultado = $sql->execute();

        if ($resultado == 0) {
            throw new Exception("falha ao editar");
            return FALSE;
        }
        return TRUE;
    }


}
