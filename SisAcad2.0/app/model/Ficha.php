<?php

class Ficha{
    private $idficha;
    private $cliente;
    private $peso;
    private $altura;
    
    public function getIdficha() {
        return $this->idficha;
    }

    public function getCliente() {
        return $this->cliente;
    }

    public function getPeso() {
        return $this->peso;
    }

    public function getAltura() {
        return $this->altura;
    }

    public function setIdficha($idficha) {
        $this->idficha = $idficha;
        return $this;
    }

    public function setCliente($cliente) {
        $this->cliente = $cliente;
        return $this;
    }

    public function setPeso($peso) {
        $this->peso = $peso;
        return $this;
    }

    public function setAltura($altura) {
        $this->altura = $altura;
        return $this;
    }
        public function inserirFicha() {
        $conn = Database\Connection::getConn();
        $sql = 'INSERT INTO ficha (cliente, ,peso, 
                altura) 
                VALUES (:cliente, :peso, :altura)'; 
        $smt = $conn->prepare($sql);
        $smt->bindValue('cliente', $this->cliente);
        $smt->bindValue('peso', $this->peso);
        $smt->bindValue('altura', $this->altura);
        if ($smt->execute()) {
            if ($smt->rowCount()) {           
            }
        }
    }
}