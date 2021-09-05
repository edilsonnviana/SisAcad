<?php

Class CadFaturas {

    private $idparcelas;
    private $cliente;
    private $usuario;
    private $nrParcelas;
    private $dataVenci;
    private $valorParcelas;
    private $statusPag;
    private $tipoPag;
    private $numParcela;

    public function getIdparcelas() {
        return $this->idparcelas;
    }

    public function getCliente() {
        return $this->cliente;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function getNrParcelas() {
        return $this->nrParcelas;
    }

    public function getDataVenci() {
        return $this->dataVenci;
    }

    public function getValorParcelas() {
        return $this->valorParcelas;
    }

    public function getStatusPag() {
        return $this->statusPag;
    }

    public function getTipoPag() {
        return $this->tipoPag;
    }

    public function getNumParcela() {
        return $this->numParcela;
    }

    public function setIdparcelas($idparcelas) {
        $this->idparcelas = $idparcelas;
        return $this;
    }

    public function setCliente($cliente) {
        $this->cliente = $cliente;
        return $this;
    }

    public function setUsuario($usuario) {
        $this->usuario = $usuario;
        return $this;
    }

    public function setNrParcelas($nrParcelas) {
        $this->nrParcelas = $nrParcelas;
        return $this;
    }

    public function setDataVenci($dataVenci) {
        $this->dataVenci = $dataVenci;
        return $this;
    }

    public function setValorParcelas($valorParcelas) {
        $this->valorParcelas = $valorParcelas;
        return $this;
    }

    public function setStatusPag($statusPag) {
        $this->statusPag = $statusPag;
        return $this;
    }

    public function setTipoPag($tipoPag) {
        $this->tipoPag = $tipoPag;
        return $this;
    }

    public function setNumParcela($numParcela) {
        $this->numParcela = $numParcela;
        return $this;
    }

    public function cadParcelas() {
        $conn = Database\Connection::getConn();

        $sql = '
         INSERT INTO cadparcelas(cliente, usuario, nrParcelas, dataVenci,valorParcelas,tipoPag,
         statusPag,numParcela,statusMatricula)
         VALUES(:cliente,:usuario, :nrParcelas, :dataVenci,:valor,2,2,:numParcela,4);';

        $smt = $conn->prepare($sql);

        $smt->bindValue('cliente', $this->cliente);
        $smt->bindValue('usuario', $this->usuario);
        $smt->bindValue('nrParcelas', $this->nrParcelas);
        $smt->bindValue('dataVenci', $this->dataVenci);
        $smt->bindValue('valor', $this->valorParcelas);
        $smt->bindValue('numParcela', $this->numParcela);

        if ($smt->execute()) {
            if ($smt->rowCount()) {
                
            }
        }
    }

    public function listarFaturas($idpost) {

        $conn = Database\Connection::getConn();

        $sql = $conn->prepare("select u1.idusuario, u1.nomeUsuario cliente, u2.login usuario ,c.nrParcelas,c.dataVenci,date_format(c.dataVenci,'%d/%m/%Y') dn ,c.valorParcelas,
            s1.descricao situacao,date_format(dataPag,'%d/%m/%Y') dataPag,s2.descricao,c.numParcela parc,c.valorPago, CURDATE() hoje, month(dataVenci) mes , month(now()) mhoje
from cadparcelas c inner join usuario u1 on c.cliente = u1.idusuario
					inner join usuario u2 on c.usuario = u2.idusuario
                    inner join statuscaixa s1 on c.statusPag = s1.idstatus
                    inner join	statuscaixa s2 on c.tipoPag = s2.idstatus where c.cliente = :id and statusMatricula = 4 order by parc;");

        $sql->bindValue(':id', $idpost, PDO::PARAM_INT);
        $sql->execute();
        $resultado = array();
        while ($row = $sql->fetchObject('CadFaturas')) {
            $resultado[] = $row;
        }
        return $resultado;
    }

    public function matriculaFinalizada($idpost) {

        $conn = Database\Connection::getConn();

        $sql = $conn->prepare("   select u1.idusuario, u1.nomeUsuario cliente, u2.login usuario ,c.nrParcelas,c.dataVenci,date_format(c.dataVenci,'%d/%m/%Y') dn ,c.valorParcelas,
            s1.descricao situacao,date_format(dataPag,'%d/%m/%Y') dataPag,s2.descricao,c.numParcela parc,c.valorPago, CURDATE() hoje 
from cadparcelas c inner join usuario u1 on c.cliente = u1.idusuario
					inner join usuario u2 on c.usuario = u2.idusuario
                    inner join statuscaixa s1 on c.statusPag = s1.idstatus
                    inner join	statuscaixa s2 on c.tipoPag = s2.idstatus where c.cliente = :id and statusMatricula = 3");

        $sql->bindValue(':id', $idpost, PDO::PARAM_INT);
        $sql->execute();
        $resultado = array();
        while ($row = $sql->fetchObject('CadFaturas')) {
            $resultado[] = $row;
        }
        return $resultado;
    }

    public function faturas() {
        $conn = Database\Connection::getConn();
        $sql = $conn->prepare("select distinct d.descricao,u1.idusuario id, u1.nomeUsuario cliente,DATE_FORMAT(u3.dataNascimento, '%d / %m / %Y') DN,u2.login usuario
from cadparcelas c inner join usuario u1 on c.cliente = u1.idusuario
					inner join usuario u2 on c.usuario = u2.idusuario
                    inner join	usuario u3 on c.cliente = u3.idusuario
                    inner join	statuscaixa d on c.statusMatricula = d.idstatus where statusMatricula = 4 order by
                    idParcelas desc ");
        $sql->execute();
        $resultado = array();
        while ($row = $sql->fetchObject('CadFaturas')) {
            $resultado[] = $row;
        }
        return $resultado;
    }

    public function listarFatura($idpost) {

        $conn = Database\Connection::getConn();

        $sql = $conn->prepare(" select  *, c.tipoPag from cadparcelas c left join statuscaixa s on c.tipoPag = s.idstatus
  where cliente:id");

        $sql->bindValue(':id', $idpost, PDO::PARAM_INT);
        $sql->execute();
        $resultado = array();
        while ($row = $sql->fetchObject('CadFaturas')) {
            $resultado[] = $row;
        }
        return $resultado;
    }

    public function cadpag($paras) {
        $conn = Database\Connection::getConn();

        $sql = "UPDATE cadparcelas
SET valorPago = :valor ,statusPag = 1, tipoPag = :tipoPag, dataPag = :dataPag
WHERE cliente = :id and numParcela = :forma and statusMatricula = 4";
        $sql = $conn->prepare($sql);

        $sql->bindValue(':valor', $paras['mensal']);
        $sql->bindValue(':tipoPag', $paras['pag']);
        $sql->bindValue(':dataPag', $paras['data']);
        $sql->bindValue(':id', $paras['id']);
        $sql->bindValue(':forma', $paras['forma']);
        $resultado = $sql->execute();

        if ($resultado == 0) {
            throw new Exception("falha ao realizar pagamento");
            return FALSE;
        }
        return TRUE;
    }

    public function fimContrato($idpost) {

        $conn = Database\Connection::getConn();

        $sql = $conn->prepare(" select cliente,DATE_FORMAT(dataVEnci, '%d / %m / %Y') vencimento ,dataVenci, curdate() hoje, s.descricao  from cadparcelas  inner join 
 statuscaixa s  on  cadparcelas.statusPag = s.idstatus  where idParcelas = (SELECT MAX(idparcelas) FROM cadparcelas where cliente = :id)");

        $sql->bindValue(':id', $idpost, PDO::PARAM_INT);
        $sql->execute();
        $resultado = array();
        while ($row = $sql->fetchObject('CadFaturas')) {
            $resultado[] = $row;
        }
        return $resultado;
    }

    public function fimMatricula($paras) {
        $conn = Database\Connection::getConn();

        $sql = "update caixa , cadparcelas set statusPlano = 3, statusModalidade = 3 , "
                . "statusMatricula = 3 where nomeCliente = :id and cliente = :id";


        $sql = $conn->prepare($sql);

        // $sql->bindValue(':statusMatricula', $paras['status']);
        $sql->bindValue(':id', $paras['id']);

        $resultado = $sql->execute();

        if ($resultado == 0) {
            throw new Exception("falha ao editar");
            return FALSE;
        }
        return TRUE;
    }

    public function exalunos() {

        $conn = Database\Connection::getConn();

        $sql = "select   u1.idusuario, u1.nomeUsuario cliente,u1.cpf,DATE_FORMAT(u1.dataNascimento, '%d / %m / %Y') dataNascimento,statusMatricula,MAX(statusMatricula) As utimaMatricula
from cadparcelas c inner join usuario u1 on c.cliente = u1.idusuario
                    inner join statuscaixa s1 on c.statusPag = s1.idstatus
                    inner join	statuscaixa s2 on c.tipoPag = s2.idstatus group by u1.nomeUsuario";
        $sql = $conn->prepare($sql);
        $sql->execute();

        $resultado = array();

        while ($row = $sql->fetchObject('CadFaturas')) {
            $resultado[] = $row;
        }
        return $resultado;
    }

    public function rematricula($idpost) {

        $conn = Database\Connection::getConn();

        $sql = $conn->prepare(" select * from cadparcelas where cliente = :id ORDER BY statusMatricula   DESC LIMIT 1 ");

        $sql->bindValue(':id', $idpost, PDO::PARAM_INT);
        $sql->execute();
        $resultado = array();
        while ($row = $sql->fetchObject('CadFaturas')) {
            $resultado[] = $row;
        }
        return $resultado;
    }

    public function lista_Historico($idpost) {

        $conn = Database\Connection::getConn();

        $sql = $conn->prepare("select distinct numParcela ,nrParcelas,date_format(dataVenci,'%d-%m-%Y') 
            inicio ,s1.descricao,s2.descricao forma , valorPago, date_format(dataPag,'%d-%m-%Y') fim,s3.descricao final, statusMatricula
from cadparcelas c
inner join statuscaixa s1 on c.statusPag = s1.idstatus
inner join statuscaixa s2 on c.tipoPag = s2.idstatus
inner join statuscaixa s3 on c.statusMatricula = s3.idstatus
where cliente = :id order by dataVenci");

        $sql->bindValue(':id', $idpost, PDO::PARAM_INT);
        $sql->execute();
        $resultado = array();
        while ($row = $sql->fetchObject('CadFaturas')) {
            $resultado[] = $row;
        }
        return $resultado;
    }

    public function todosPlanos($idpost) {

        $conn = Database\Connection::getConn();

        $sql = $conn->prepare("  select distinct p.descricao plano , s.descricao, DATE_FORMAT(dataVenci, '%d / %m / %Y') inicio,statusMatricula from cadparcelas c 
 left join plano p on c.nrParcelas = p.nrParcelas
 left join statuscaixa s on c.statusMatricula = s.idstatus
 where cliente = :id and numParcela = 1 order by dataVenci");

        $sql->bindValue(':id', $idpost, PDO::PARAM_INT);
        $sql->execute();
        $resultado = array();
        while ($row = $sql->fetchObject('CadFaturas')) {
            $resultado[] = $row;
        }
        return $resultado;
    }

    public function valoresPagos($idpost) {

        $conn = Database\Connection::getConn();

        $sql = $conn->prepare("select distinct nrParcelas,valorPago * nrParcelas valores,statusMatricula from cadparcelas 
where cliente = :id");

        $sql->bindValue(':id', $idpost, PDO::PARAM_INT);
        $sql->execute();
        $resultado = array();
        while ($row = $sql->fetchObject('CadFaturas')) {
            $resultado[] = $row;
        }
        return $resultado;
    }

    public function progresso($idpost) {

        $conn = Database\Connection::getConn();

        $sql = $conn->prepare("select max(dataVenci) vence , CURDATE() hoje from cadparcelas where cliente = :id");

        $sql->bindValue(':id', $idpost, PDO::PARAM_INT);
        $sql->execute();
        $resultado = array();
        while ($row = $sql->fetchObject('CadFaturas')) {
            $resultado[] = $row;
        }
        return $resultado;
    }

    public function gerarDados() {

        $conn = Database\Connection::getConn();

        $sql = "select u1.idusuario id, u1.nomeUsuario cliente, u2.login usuario ,c.nrParcelas parcelas ,c.dataVenci vencimento,date_format(c.dataVenci,'%d/%m/%Y') dn ,c.valorParcelas valor,
            s1.descricao situacao ,date_format(dataPag,'%d/%m/%Y') dataPagamento ,s2.descricao forma,c.numParcela parc,c.valorPago pago,s3.descricao modo
from cadparcelas c inner join usuario u1 on c.cliente = u1.idusuario
					inner join usuario u2 on c.usuario = u2.idusuario
                    inner join statuscaixa s1 on c.statusPag = s1.idstatus
                    inner join	statuscaixa s2 on c.tipoPag = s2.idstatus 
                     inner join	statuscaixa s3 on c.statusMatricula = s3.idstatus";
        $sql = $conn->prepare($sql);
        $sql->execute();

        $resultado = array();

        while ($row = $sql->fetchObject('CadFaturas')) {
            $resultado[] = $row;
        }
        return $resultado;
    }

    public function qntAlunos() {

        $conn = Database\Connection::getConn();

        $sql = "select count(distinct cliente) cliente,format( SUM(valorPago) ,2,'de_DE' )total   from cadparcelas where statusMatricula = 4;";
        $sql = $conn->prepare($sql);
        $sql->execute();
        $resultado = array();
        while ($row = $sql->fetchObject('CadFaturas')) {
            $resultado[] = $row;
        }
        return $resultado;
    }

    public function faturamento() {

        $conn = Database\Connection::getConn();

        $sql = " select count(distinct cliente) cliente,format( SUM(valorPago) ,2,'de_DE' )total,format( SUM(valorParcelas) -  SUM(valorPago) ,2,'de_DE' ) previsto  from cadparcelas";
        $sql = $conn->prepare($sql);
        $sql->execute();
        $resultado = array();
        while ($row = $sql->fetchObject('CadFaturas')) {
            $resultado[] = $row;
        }
        return $resultado;
    }

    public function tipoPag() {
        $conn = Database\Connection::getConn();
        $sql = "select s.descricao, format( SUM(c.valorPago) ,2,'de_DE' )total,s1.descricao total,count(c.tipoPag) qnt ,
round(count(c.tipoPag) / (SELECT count(tipoPag)  from cadparcelas where statusPag = 1) * 100,0) Porcento, tipoPag forma  from cadparcelas c 
inner join statuscaixa s on c.tipoPag = s.idstatus
inner join statuscaixa s1 on c.statusPag = s1.idstatus
where c.statusPag = 1 group by tipoPag";
        $sql = $conn->prepare($sql);
        $sql->execute();
        $resultado = array();
        while ($row = $sql->fetchObject('CadFaturas')) {
            $resultado[] = $row;
        }
        return $resultado;
    }

}
