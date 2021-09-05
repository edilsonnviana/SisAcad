<?php


class TipoUsuario {
    private $idtipousuario;
    private $descricao;
    function getIdtipousuario() {
        return $this->idtipousuario;
    }

    function getDescricao() {
        return $this->descricao;
    }

    function setIdtipousuario($idtipousuario) {
        $this->idtipousuario = $idtipousuario;
    }

    function setDescricao($descricao) {
        $this->descricao = $descricao;
    }


}
