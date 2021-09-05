<?php

class Caixa {

    private $idcaixa;
    private $dataHora;
    private $nomeUsuario;
    private $nomeCliente;
    private $nomeModalidade;
    private $plano;
    private $datapag;

    public function getIdcaixa() {
        return $this->idcaixa;
    }

    public function getDatapag() {
        return $this->datapag;
    }

    public function setDatapag($datapag) {
        $this->datapag = $datapag;
        return $this;
    }

    public function getDataHora() {
        return $this->dataHora;
    }

    public function getNomeUsuario() {
        return $this->nomeUsuario;
    }

    public function getNomeCliente() {
        return $this->nomeCliente;
    }

    public function getNomeModalidade() {
        return $this->nomeModalidade;
    }

    public function getPlano() {
        return $this->plano;
    }

    public function setIdcaixa($idcaixa) {
        $this->idcaixa = $idcaixa;
        return $this;
    }

    public function setDataHora($dataHora) {
        $this->dataHora = $dataHora;
        return $this;
    }

    public function setNomeUsuario($nomeUsuario) {
        $this->nomeUsuario = $nomeUsuario;
        return $this;
    }

    public function setNomeCliente($nomeCliente) {
        $this->nomeCliente = $nomeCliente;
        return $this;
    }

    public function setNomeModalidade($nomeModalidade) {
        $this->nomeModalidade = $nomeModalidade;
        return $this;
    }

    public function setPlano($plano) {
        $this->plano = $plano;
        return $this;
    }

    public function listarCaixa() {
        $conn = Database\Connection::getConn();
        $sql = $conn->prepare("  select distinct u2.idusuario, u1.login,u2.nomeUsuario,u2.email  from caixa c 
 left join usuario u2 on c.nomeCliente = u2.idusuario 
 left join usuario u1 on c.nomeUsuario = u1.idusuario 
 
 left join modalidades m on c.nomeModalidade = m.idmodalidades  where statusPagamento = 2 and statusModalidade = 4
 and not exists ( select * from cadparcelas d where d.cliente = u2.idusuario and statusMatricula = 4 )
");
        $sql->execute();
        $resultado = array();
        while ($row = $sql->fetchObject('Caixa')) {
            $resultado[] = $row;
        }
        return $resultado;
    }

    public function matricula() {
        $conn = Database\Connection::getConn();

        $sql = '
         INSERT INTO caixa(nomeUsuario, nomeCliente, nomeModalidade, plano,
         statusModalidade,statusPlano,statusPagamento)
         VALUES(:usuario, :clientes, :modalidade, :plano,4,4,2 );';

        $smt = $conn->prepare($sql);

        $smt->bindValue('usuario', $this->nomeUsuario);
        $smt->bindValue('clientes', $this->nomeCliente);
        $smt->bindValue('modalidade', $this->nomeModalidade);
        $smt->bindValue('plano', $this->plano);

        if ($smt->execute()) {
            if ($smt->rowCount()) {
                
            }
        }
    }

    public function receberCaixa($idpost) {

        $conn = Database\Connection::getConn();

        $sql = $conn->prepare("select  idcaixa,date_format(dataHora,'%d-%m-%Y às %Hh%i')dataHora,u1.login Usuario_Logado, 
m1.descriModalidade Modalidades,m1.valorModalidade valorModalidade, p1.descricao, p1.valor ValorPlano,s.descricao mensalidade,
s1.descricao plano, s2.descricao Modalidade,statusModalidade
from caixa c1  
left join statuscaixa s on s.idstatus = c1.statusPagamento
left join statuscaixa s1 on s1.idstatus = c1.statusPlano
left join statuscaixa s2 on s2.idstatus = c1.statusModalidade
left join usuario u1 on u1.idusuario = c1.nomeUsuario
left join usuario u2 on u2.idusuario = c1.nomeCliente
left join modalidades m1 on m1.idmodalidades = c1.nomeModalidade
left join plano p1 on p1.idplano = c1.plano where u2.idusuario = :id and statusModalidade = 4;");

        $sql->bindValue(':id', $idpost, PDO::PARAM_INT);
        $sql->execute();
        $resultado = array();
        while ($row = $sql->fetchObject('Caixa')) {
            $resultado[] = $row;
        }
        return $resultado;
    }

    public function excluirCaixa($id) {
        $conn = Database\Connection::getConn();

        $sql = $conn->prepare("delete from caixa where nomeCliente = :id");
        $sql->bindValue(':id', $id);
        $resultado = $sql->execute();
        if ($resultado == 0) {
            throw new Exception("falha ao excluir");
            return FALSE;
        }
        return TRUE;
    }

    public function editarCaixa($idpost) {
        $conn = Database\Connection::getConn();
        $sql = $conn->prepare("  select m.idmodalidades,m.descriModalidade from caixa c left join modalidades "
                . "m on c.nomeModalidade = m.idmodalidades  where  nomeCliente= :id");
        $sql->bindValue(':id', $idpost, PDO::PARAM_INT);
        $sql->execute();
        $resultado = array();
        while ($row = $sql->fetchObject('Caixa')) {
            $resultado[] = $row;
        }
        return $resultado;
    }

    public function editarMatriculas() {
        $conn = Database\Connection::getConn();

        $sql = '
        UPDATE caixa SET nomeCliente = :clientes, nomeModalidade = 
        :modalidade, plano = :plano, statusModalidade = 4,statusPlano = 4,
        statusPagamento =  2 WHERE nomeCliente = :clientes ;';

        $smt = $conn->prepare($sql);


        $smt->bindValue('clientes', $this->nomeCliente);
        $smt->bindValue('modalidade', $this->nomeModalidade);
        $smt->bindValue('plano', $this->plano);

        if ($smt->execute()) {
            if ($smt->rowCount()) {
                
            }
        }
    }

    public function pagamento($parans) {
        $conn = Database\Connection::getConn();

        $sql = "UPDATE caixa SET `statusPagamento` = 1,"
                . " tipoPag = :tipopag, mensalidade = :mensal, datapag = :datapag WHERE nomeCliente = :id";

        $sql = $conn->prepare($sql);

        $sql->bindValue(':tipopag', $parans['pag']);
        $sql->bindValue(':id', $parans['id']);
        $sql->bindValue(':mensal', $parans['mensal']);
        $sql->bindValue(':datapag', $parans['datapag']);

        $resultado = $sql->execute();

        if ($resultado == 0) {
            throw new Exception("falha ao editar");
            return FALSE;
        }
        return TRUE;
    }

    public function matriculados() {
        $conn = Database\Connection::getConn();
        $sql = $conn->prepare("select distinct u2.nomeUsuario cliente,p.descricao plano,s1.descricao StatusPagamento
            ,s2.descricao tipoPagamento, mensalidade,
            date_format(datapag,'%d/%m/%Y') Pagamento,
            date_format(  ADDDATE(datapag, INTERVAL 1 MONTH), '%d/%m/%Y' )proxima   from caixa c 
 left join usuario u2 on c.nomeCliente = u2.idusuario 
 left join usuario u1 on c.nomeUsuario = u1.idusuario 
 left join statuscaixa s1 on c.statusPagamento = s1.idstatus
  left join statuscaixa s2 on c.tipoPag= s2.idstatus
 left join plano p on  c.plano = p.idplano
 left join modalidades m on c.nomeModalidade = m.idmodalidades where
 statusPagamento = 1 order by datapag;
 ");
        $sql->execute();
        $resultado = array();
        while ($row = $sql->fetchObject('Caixa')) {
            $resultado[] = $row;
        }
        return $resultado;
    }
    
    public function modalidade($idpost) {
        $conn = Database\Connection::getConn();
        $sql = $conn->prepare("  select m.descriModalidade, m.valorModalidade from caixa c inner join modalidades m on c.nomeModalidade = m.idmodalidades
        where nomeCliente= :id  and statusModalidade = 4 ");
        $sql->bindValue(':id', $idpost, PDO::PARAM_INT);
        $sql->execute();
        $resultado = array();
        while ($row = $sql->fetchObject('Caixa')) {
            $resultado[] = $row;
        }
        return $resultado;
    }
     public function listarHistorico($idpost) {

        $conn = Database\Connection::getConn();

        $sql = $conn->prepare(" select dataHora,m.descriModalidade,p.descricao from caixa c  inner join modalidades m on c.nomeModalidade = m.idmodalidades
 inner join plano p on c.plano = p.idplano where  nomeCliente = :id");

        $sql->bindValue(':id', $idpost, PDO::PARAM_INT);
        $sql->execute();
        $resultado = array();
        while ($row = $sql->fetchObject('CadFaturas')) {
            $resultado[] = $row;
        }
        return $resultado;
    }
 
    public function editarMatricula($paras) {
        $conn = Database\Connection::getConn();

        $sql =  "UPDATE caixa SET statusModalidade = :este WHERE idcaixa = :idcaixa";
        $sql = $conn->prepare($sql);

     
        $sql->bindValue(':este', $paras['este']);
        $sql->bindValue(':idcaixa', $paras['idcaixa']);

        $resultado = $sql->execute();

        if ($resultado == 0) {
            throw new Exception("falha ao editar");
            return FALSE;
        }
        return TRUE;
    }
        public function mensalidade($idpost) {

        $conn = Database\Connection::getConn();

        $sql = $conn->prepare("  select  u2.nomeUsuario,p1.descricao,p1.valor,count(m1.descriModalidade) Modalidades ,sum(m1.valorModalidade) somaModalidades,
                          sum(valorModalidade)+p1.valor mensalidade,statusModalidade
                         from caixa c1  
left join usuario u1 on u1.idusuario = c1.nomeUsuario
left join usuario u2 on u2.idusuario = c1.nomeCliente
left join modalidades m1 on m1.idmodalidades = c1.nomeModalidade
left join plano p1 on p1.idplano = c1.plano where u2.idusuario = :id");

        $sql->bindValue(':id', $idpost, PDO::PARAM_INT);
        $sql->execute();
        $resultado = array();
        while ($row = $sql->fetchObject('Caixa')) {
            $resultado[] = $row;
        }
        return $resultado;
    }
        public function finalizarMatricula($paras) {
        $conn = Database\Connection::getConn();

        $sql =  "UPDATE caixa SET statusModalidade = :este WHERE idcaixa = :idcaixa";
        $sql = $conn->prepare($sql);

     
        $sql->bindValue(':este', $paras['este']);
      

        $resultado = $sql->execute();

        if ($resultado == 0) {
            throw new Exception("falha ao editar");
            return FALSE;
        }
        return TRUE;
    }
     public function modalidadesFim($idpost) {

        $conn = Database\Connection::getConn();

        $sql = $conn->prepare("SELECT modalidades.descriModalidade FROM caixa inner join modalidades on caixa.nomeModalidade = "
                . "modalidades.idmodalidades where nomeCliente = :id and statusModalidade = 3;");

        $sql->bindValue(':id', $idpost, PDO::PARAM_INT);
        $sql->execute();
        $resultado = array();
        while ($row = $sql->fetchObject('Caixa')) {
            $resultado[] = $row;
        }
        return $resultado;
    }
        public function faturamento() {
        $conn = Database\Connection::getConn();
        $sql = $conn->prepare("SELECT s.descricao ,SUM(valorPago) AS total 
FROM cadparcelas c left join statuscaixa s  on c.tipoPag = s.idstatus
 where statusPag = 1 GROUP BY tipoPag;");
        $sql->execute();
        $resultado = array();
        while ($row = $sql->fetchObject('Caixa')) {
            $resultado[] = $row;
        }
        return $resultado;
    }
     public function gerarDadosCaixa() {

        $conn = Database\Connection::getConn();

        $sql = " select  u2.idusuario, u1.login atendente,u2.nomeUsuario cliente ,u2.email,m.descriModalidade modalidade , p.descricao plano , s.descricao matricula   from caixa c 
 left join usuario u2 on c.nomeCliente = u2.idusuario 
 left join usuario u1 on c.nomeUsuario = u1.idusuario 
 left join plano p on c.plano = p.idplano
 left join modalidades m on c.nomeModalidade = m.idmodalidades
 left join statuscaixa s on c.statusModalidade = s.idstatus";
        $sql = $conn->prepare($sql);
        $sql->execute();

        $resultado = array();

        while ($row = $sql->fetchObject('Caixa')) {
            $resultado[] = $row;
        }
        return $resultado;
    }
        public function inadiplentes() {

        $conn = Database\Connection::getConn();

        $sql = "select distinct idusuario,u.nomeUsuario, nrParcelas, valorParcelas from cadparcelas c  inner join usuario u on c.cliente = u.idusuario
 where statusPag = 2 and dataVenci < curdate() and statusMatricula = 4 order by valorParcelas desc limit 6";
        $sql = $conn->prepare($sql);
        $sql->execute();
        $resultado = array();
        while ($row = $sql->fetchObject('Plano')) {
            $resultado[] = $row;
        }
        return $resultado;
    }

}
