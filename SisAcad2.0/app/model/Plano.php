<?php

class Plano {

    private $idplano;
    private $descricao;
    private $valor;
    private $modo;
    private $mensalidade;
    private $nrParcelas;

    public function getNrParcelas() {
        return $this->nrParcelas;
    }

    public function setNrParcelas($nrParcelas) {
        $this->nrParcelas = $nrParcelas;
        return $this;
    }

    public function getIdplano() {
        return $this->idplano;
    }

    public function getModo() {
        return $this->modo;
    }

    public function getMensalidade() {
        return $this->mensalidade;
    }

    public function setMensalidade($mensalidade) {
        $this->mensalidade = $mensalidade;
        return $this;
    }

    public function setModo($modo) {
        $this->modo = $modo;
        return $this;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function getValor() {
        return $this->valor;
    }

    public function setIdplano($idplano) {
        $this->idplano = $idplano;
        return $this;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
        return $this;
    }

    public function setValor($valor) {
        $this->valor = $valor;
        return $this;
    }

    public function listarP() {

        $conn = Database\Connection::getConn();

        $sql = "select idplano,p.descricao nome,p.valor, t.descricao from"
                . " plano p  left join tipousuario t on p.modo = t.idtipousuario ";
        $sql = $conn->prepare($sql);
        $sql->execute();

        $resultado = array();

        while ($row = $sql->fetchObject('Plano')) {
            $resultado[] = $row;
        }
        return $resultado;
    }

    public function planoMatricula() {

        $conn = Database\Connection::getConn();

        $sql = "select * from plano where modo <> 3";
        $sql = $conn->prepare($sql);
        $sql->execute();

        $resultado = array();

        while ($row = $sql->fetchObject('Plano')) {
            $resultado[] = $row;
        }
        return $resultado;
    }

    public function listarId($idpost) {
        $conn = Database\Connection::getConn();

        $sql = "SELECT * FROM plano WHERE idplano = :id";

        $sql = $conn->prepare($sql);

        $sql->bindValue(':id', $idpost, PDO::PARAM_INT);
        $sql->execute();

        $resultado = $sql->fetchObject('Plano');

        if (!$resultado) {
            throw new Exception('nenhum registro rncontrado');
        }
        return $resultado;
    }

    public function editar($paras) {
        $conn = Database\Connection::getConn();

        $sql = "UPDATE plano SET descricao = :descricao, "
                . "valor = :valor where idplano = :id";
        $sql = $conn->prepare($sql);

        $sql->bindValue(':descricao', $paras['descricao']);
        $sql->bindValue(':valor', $paras['valor']);
        $sql->bindValue(':id', $paras['id']);

        $resultado = $sql->execute();

        if ($resultado == 0) {
            throw new Exception("falha ao editar");
            return FALSE;
        }
        return TRUE;
    }

    public function inativarPlano($id) {
        $conn = Database\Connection::getConn();

        //$sql = "delete from plano where idplano = :id";
        $sql = "UPDATE plano SET modo = 3 WHERE idplano = :id;";
        $sql = $conn->prepare($sql);
        $sql->bindValue(':id', $id);
        $resultado = $sql->execute();
        if ($resultado == 0) {
            throw new Exception("falha ao excluir");
            return FALSE;
        }
        return TRUE;
    }

    public function ativarPlano($id) {
        $conn = Database\Connection::getConn();

        //$sql = "delete from plano where idplano = :id";
        $sql = "UPDATE plano SET modo = 5 WHERE idplano = :id;";
        $sql = $conn->prepare($sql);
        $sql->bindValue(':id', $id);
        $resultado = $sql->execute();
        if ($resultado == 0) {
            throw new Exception("falha ao excluir");
            return FALSE;
        }
        return TRUE;
    }

    public function inserir() {
        $conn = Database\Connection::getConn();

        $sql = "INSERT INTO plano (descricao, valor, modo)VALUES "
                . " (:descricao , :valor, 5) ";
        $smt = $conn->prepare($sql);

        $smt->bindValue('descricao', $this->descricao);
        $smt->bindValue('valor', $this->valor);

        if ($smt->execute()) {
            if ($smt->rowCount()) {
                
            }
        }
    }

    public function listarPlanoCaixa($idpost) {
        $conn = Database\Connection::getConn();
        $sql = $conn->prepare("select distinct  p.nrParcelas, p.idplano,p.descricao, p.valor from caixa c 
                                 left join plano p on c.plano = p.idplano
                                  left join usuario u on c.nomeCliente = u.idusuario 
                                  where u.idusuario = :id ORDER BY idcaixa DESC LIMIT 1");

        $sql->bindValue(':id', $idpost, PDO::PARAM_INT);
        $sql->execute();

        $resultado = $sql->fetchObject('Plano');

        if (!$resultado) {
            throw new Exception('nenhum registro rncontrado');
        }
        return $resultado;
    }

    public function mensalidades($idpost) {
        $conn = Database\Connection::getConn();
        $sql = $conn->prepare(" select  u2.nomeUsuario,p1.descricao,p1.valor,count(m1.descriModalidade) Modalidades ,sum(m1.valorModalidade) somaModalidades,
                          sum(valorModalidade)+p1.valor mensalidade,statusModalidade
                         from caixa c1  
left join usuario u1 on u1.idusuario = c1.nomeUsuario
left join usuario u2 on u2.idusuario = c1.nomeCliente
left join modalidades m1 on m1.idmodalidades = c1.nomeModalidade
left join plano p1 on p1.idplano = c1.plano where u2.idusuario = :id and statusModalidade <> 3 and statusPlano <> 3");

        $sql->bindValue(':id', $idpost, PDO::PARAM_INT);
        $sql->execute();

        $resultado = $sql->fetchObject('Plano');

        if (!$resultado) {
            throw new Exception('nenhum registro rncontrado');
        }
        return $resultado;
    }

    public function novaMensalidades($idpost) {
        $conn = Database\Connection::getConn();
        $sql = $conn->prepare(" select  u2.nomeUsuario,p1.descricao,p1.valor,count(m1.descriModalidade) Modalidades ,sum(m1.valorModalidade) somaModalidades,
                          sum(valorModalidade)+p1.valor mensalidade
                         from caixa c1  
left join usuario u1 on u1.idusuario = c1.nomeUsuario
left join usuario u2 on u2.idusuario = c1.nomeCliente
left join modalidades m1 on m1.idmodalidades = c1.nomeModalidade
left join plano p1 on p1.idplano = c1.plano where u2.idusuario = :id and statusModalidade <> 3 and statusPlano <> 3");

        $sql->bindValue(':id', $idpost, PDO::PARAM_INT);
        $sql->execute();

        $resultado = $sql->fetchObject('Plano');

        if (!$resultado) {
            throw new Exception('nenhum registro rncontrado');
        }
        return $resultado;
    }

    public function qntPlanos() {

        $conn = Database\Connection::getConn();

        $sql = "SELECT  nomeCliente ,p.descricao, COUNT( distinct nomeCliente) AS qtd
FROM caixa c 
inner join plano p on c.plano = p.idplano

GROUP BY plano
ORDER BY qtd DESC LIMIT 3";
        $sql = $conn->prepare($sql);
        $sql->execute();
        $resultado = array();
        while ($row = $sql->fetchObject('Plano')) {
            $resultado[] = $row;
        }
        return $resultado;
    }

}
