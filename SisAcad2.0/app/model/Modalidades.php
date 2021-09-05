<?php
class Modalidades {
    private $idmodalidades;
    private $descriModalidade;
    private $valorModalidade;
    
    public function getIdmodalidades() {
        return $this->idmodalidades;
    }

    public function getDescriModalidade() {
        return $this->descriModalidade;
    }

    public function getValorModalidade() {
        return $this->valorModalidade;
    }

    public function setIdmodalidades($idmodalidades) {
        $this->idmodalidades = $idmodalidades;
        return $this;
    }

    public function setDescriModalidade($descriModalidade) {
        $this->descriModalidade = $descriModalidade;
        return $this;
    }

    public function setValorModalidade($valorModalidade) {
        $this->valorModalidade = $valorModalidade;
        return $this;
    }

        
    public function listar(){
        
        $conn = Database\Connection::getConn();
        
        $sql = "SELECT * FROM modalidades where modo = 5 and idmodalidades <> 9";
        $sql = $conn->prepare($sql);
        $sql->execute();
        
        $resultado = array();
        
        while ($row = $sql->fetchObject('Modalidades')){
            $resultado[] = $row;
        }
        return $resultado;
    }
    public function listarId($idpost){
        
     $conn = Database\Connection::getConn();
     
     $sql = "SELECT idmodalidades,descriModalidade,valorModalidade"
             . " FROM modalidades WHERE idmodalidades = :id";
     
     $sql = $conn->prepare($sql);
        $sql->bindValue(':id', $idpost, PDO::PARAM_INT);
        $sql->execute();

        $resultado = $sql->fetchObject('Modalidades');

        if (!$resultado) {
            throw new Exception("Não foi encontrado nenhum registro");
        }
        return $resultado;
     
    }

    public function inserir(){
         $conn = Database\Connection::getConn();
         
         $sql = "INSERT INTO modalidades (descriModalidade, valorModalidade, modo)VALUES "
                 . " (:modalidade , :valor,5) ";
         $smt = $conn->prepare($sql);
         
         $smt->bindValue('modalidade', $this->descriModalidade);
         $smt->bindValue('valor', $this->valorModalidade);
        
         
            if ($smt->execute()) {
            if ($smt->rowCount()) {
               
            }
        }
                
    }
    public function excluir($id){
        $conn = Database\Connection::getConn();
        $sql = $conn->prepare(" UPDATE modalidades SET modo = 3 WHERE idmodalidades = :id");
        $sql->bindValue(':id', $id);
        $resultado = $sql->execute();
        if ($resultado == 0){
                 throw new Exception("falha ao inativar");
                 return FALSE;
             }
             return TRUE;

    }
    public function editar($paras){
        $conn = Database\Connection::getConn();
        
        $sql = "UPDATE modalidades SET descriModalidade = :modalidade, "
                . "valorModalidade = :valor where idmodalidades = :id";
        $sql = $conn->prepare($sql);
        
        $sql->bindValue(':modalidade',$paras['modalidade']);
        $sql->bindValue(':valor',$paras['valor']);
        $sql->bindValue(':id',$paras['id']);
        
        $resultado = $sql->execute();
        
        if ($resultado == 0){
            throw new Exception("falha ao editar");
                 return FALSE;
        }
        return TRUE;
    }
        public function qntModalidades() {

        $conn = Database\Connection::getConn();

        $sql = "SELECT m.descriModalidade, COUNT(nomeModalidade) AS qtd
FROM caixa c 
inner join modalidades m on c.nomeModalidade = m.idmodalidades
WHERE nomeModalidade <> 9
GROUP BY nomeModalidade
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