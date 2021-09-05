<?php

class AdminController {

    public function cadFucionario() {
        $loader = new \Twig\Loader\FilesystemLoader('app/view/');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        $template = $twig->load('cadastro_funcionario.html');
        $parameters['name_user'] = $_SESSION['usr']['name_user'];
        $parameters['tipoUsuario'] = $_SESSION['usr']['tipoUsuario'];
        return $template->render($parameters);
    }

    public function telaLista() {

        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        $template = $twig->load('usuarios.html');
        $usuarios = new User();
        $lista = $usuarios->buscar();
        $parametros = array();
        $parametros['usuarios'] = $lista;
        $parametros['name_user'] = $_SESSION['usr']['name_user'];
        $parametros['id_user'] = $_SESSION['usr']['idusuario'];
        return $template->render($parametros);
    }

    public function logout() {
        unset($_SESSION['usr']);
        session_destroy();
        header('Location: http://localhost/SisAcad2.0/6_SistemaLogin');
    }

    public function inserirUsuario() {
        $usuario = new User();
        try {
            $usuario->__set('tipoUsuario', $_POST['tipousuario']);
            $usuario->__set('nomeUsuario', $_POST['nome']);
            $usuario->__set('cpf', $_POST['cpf']);
            $usuario->__set('dataNascimento', $_POST['dtnasc']);
            $usuario->__set('sexo', $_POST['sexo']);
            $usuario->__set('telefone', $_POST['tel']);
            $usuario->__set('telefone2', $_POST['tel2']);
            $usuario->__set('email', $_POST['email']);
            $usuario->__set('endereco', $_POST['endereco']);
            $usuario->__set('login', $_POST['login']);
            $usuario->__set('senha', $_POST['senha']);
            $usuario->inserir();
            echo '<script>alert("Usuario cadastrado com sucesso!");</script>';
            echo '<script>location.href="http://localhost/SisAcad2.0/admin/telaLista"</script>';
        } catch (Exception $exc) {
            echo '<script>alert("' . $exc->getMessage() . '");</script>';
            echo '<script>location.href="http://localhost/SisAcad2.0/admin/index"</script>';
        }
    }

    public function telaEdit($parsId) {
        $id = $parsId['id'];
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        $template = $twig->load('editar_funcionario.html');
        $usuario = new User();
        $lista = $usuario->buscarId($id);

        $parametros = array();
        $parametros['nome'] = $lista->getNomeUsuario();
        $parametros['tel'] = $lista->getTelefone();
        $parametros['tel2'] = $lista->getTelefone2();
        $parametros['cpf'] = $lista->getCpf();
        $parametros['email'] = $lista->getEmail();
        $parametros['dtnasc'] = $lista->getDataNascimento();
        $parametros['endereco'] = $lista->getEndereco();
        $parametros['sexo'] = $lista->getSexo();
        $parametros['descricao'] = $lista->getDescricao();
        $parametros['id'] = $lista->getIdusuario();
        $parametros['name_user'] = $_SESSION['usr']['name_user'];
        return $template->render($parametros);
    }

    public function telaEditDES($parsId) {
        $id = $parsId['id'];
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        $template = $twig->load('editar_fun_des.html');
        $usuario = new User();
        $lista = $usuario->buscarId($id);

        $parametros = array();
        $parametros['nome'] = $lista->getNomeUsuario();
        $parametros['tel'] = $lista->getTelefone();
        $parametros['tel2'] = $lista->getTelefone2();
        $parametros['cpf'] = $lista->getCpf();
        $parametros['email'] = $lista->getEmail();
        $parametros['dtnasc'] = $lista->getDataNascimento();
        $parametros['endereco'] = $lista->getEndereco();
        $parametros['sexo'] = $lista->getSexo();
        $parametros['descricao'] = $lista->getDescricao();
        $parametros['id'] = $lista->getIdusuario();
        $parametros['name_user'] = $_SESSION['usr']['name_user'];
        return $template->render($parametros);
    }

    public function editarFunc() {
        $usuario = new User();
        try {
            $usuario->editaFunc($_POST);
            echo '<script>alert("Usuario editado com sucesso!");</script>';
            echo '<script>location.href="http://localhost/SisAcad2.0/admin/telaLista"</script>';
        } catch (Exception $ex) {
            echo '<script>alert("' . $ex->getMessage() . '");</script>';
        }
    }

    public function editarFuncDES() {
        $usuario = new User();
        try {
            $usuario->editaFuncDES($_POST);
            echo '<script>alert("Usuario habilitado com sucesso!");</script>';
            echo '<script>location.href="http://localhost/SisAcad2.0/admin/telaLista"</script>';
        } catch (Exception $ex) {
            echo '<script>alert("' . $ex->getMessage() . '");</script>';
        }
    }

    public function desabilitaFunc($id) {
        $id = $_GET['id'];
        $usuario = new User();
        try {
            $usuario->desabilitaFunc($id);
            echo '<script>alert("Usuario desabilitado, para habiliatar novamente vá na aba DESABILITADOS");</script>';
            echo '<script>location.href="http://localhost/SisAcad2.0/admin/telaLista"</script>';
        } catch (Exception $ex) {
            echo '<script>alert("' . $ex->getMessage() . '");</script>';
        }
    }

    public function telaDesabilitados() {
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        $template = $twig->load('desabilitados.html');
        $usuarios = new User();
        $lista = $usuarios->buscarDes();
        $parametros = array();
        $parametros['usuarios'] = $lista;
        $parametros['name_user'] = $_SESSION['usr']['name_user'];
        return $template->render($parametros);
    }

    public function telaModalidade() {
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        $template = $twig->load('modalidades.html');
        $modalidade = new Modalidades();
        $lista = $modalidade->listar();
        $parametros = array();
        $parametros['modalidades'] = $lista;
        $parametros['name_user'] = $_SESSION['usr']['name_user'];
        return $template->render($parametros);
    }

    public function telaCadMod() {
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        $template = $twig->load('cadastro_modalidades.html');

        $parameters['name_user'] = $_SESSION['usr']['name_user'];
        $parameters['tipoUsuario'] = $_SESSION['usr']['tipoUsuario'];
        return $template->render($parameters);
    }

    public function inserirModalidade() {
        $modalidade = new Modalidades();

        try {
            $modalidade->setDescriModalidade($_POST['modalidade']);
            $modalidade->setValorModalidade($_POST['valor']);

            $modalidade->inserir();
            echo '<script>alert("Modalidade cadastrada com sucesso!");</script>';
            echo '<script>location.href="http://localhost/SisAcad2.0/admin/telaModalidade"</script>';
        } catch (Exception $exc) {
            echo '<script>alert("' . $exc->getMessage() . '");</script>';
            echo '<script>location.href="http://localhost/SisAcad2.0/admin/telaCadMod"</script>';
        }
    }

    public function excluirModalidade($id) {
        $id = $_GET['id'];
        try {
            $moda = new Modalidades();
            $moda->excluir($id);
            echo '<script>alert("Modalidade excluida com Sucesso!!!");</script>';
            echo '<script>location.href="http://localhost/SisAcad2.0/admin/telaModalidade"</script>';
        } catch (Exception $ex) {
            echo '<script>alert("' . $ex->getMessage() . '");</script>';
        }
    }

    public function telaEditMod($idpost) {
        $id = $idpost['id'];
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        $template = $twig->load('editar_modalidade.html');

        $moda = new Modalidades();

        $lista = $moda->listarId($id);

        $parametros = array();

        $parametros['nome'] = $lista->getDescriModalidade();
        $parametros['valor'] = $lista->getValorModalidade();
        $parametros['id'] = $lista->getIdModalidades();
        $parametros['name_user'] = $_SESSION['usr']['name_user'];
        return $template->render($parametros);
    }

    public function editarModalidade() {
        $moda = new Modalidades();
        try {
            $moda->editar($_POST);
            echo '<script>alert("Modalidade editado com sucesso!");</script>';
            echo '<script>location.href="http://localhost/SisAcad2.0/admin/telaModalidade"</script>';
        } catch (Exception $ex) {
            echo '<script>alert("' . $ex->getMessage() . '");</script>';
        }
    }

    public function telaPlano() {
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        $template = $twig->load('plano.html');

        $plano = new Plano();

        $lista = $plano->listarP();

        $parametros = array();
        $parametros['plano'] = $lista;
        $parametros['name_user'] = $_SESSION['usr']['name_user'];
        return $template->render($parametros);
    }

    public function telaEditPlano($idpost) {
        $id = $idpost['id'];
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        $template = $twig->load('editar_plano.html');

        $plano = new Plano();
        $lista = $plano->listarId($id);

        $parametros = array();

        $parametros['nome'] = $lista->getDescricao();
        $parametros['valor'] = $lista->getValor();
        $parametros['id'] = $lista->getIdplano();
        $parametros['name_user'] = $_SESSION['usr']['name_user'];
        return $template->render($parametros);
    }

    public function editarPlano() {
        $plano = new Plano();
        try {
            $plano->editar($_POST);
            echo '<script>alert("Plano editado com sucesso!");</script>';
            echo '<script>location.href="http://localhost/SisAcad2.0/admin/telaPlano"</script>';
        } catch (Exception $ex) {
            echo '<script>alert("' . $ex->getMessage() . '");</script>';
        }
    }

    public function inativarPlano($id) {
        $id = $_GET['id'];
        try {
            $plano = new Plano();
            $plano->inativarPlano($id);
            echo '<script>alert("Plano excluida com Sucesso!!!");</script>';
            echo '<script>location.href="http://localhost/SisAcad2.0/admin/telaPlano"</script>';
        } catch (Exception $ex) {
            echo '<script>alert("' . $ex->getMessage() . '");</script>';
        }
    }

    public function ativarPlano($id) {
        $id = $_GET['id'];
        try {
            $plano = new Plano();
            $plano->ativarPlano($id);
            echo '<script>alert("Plano ativado com Sucesso!!!");</script>';
            echo '<script>location.href="http://localhost/SisAcad2.0/admin/telaPlano"</script>';
        } catch (Exception $ex) {
            echo '<script>alert("' . $ex->getMessage() . '");</script>';
        }
    }

    public function telaCadPlano() {
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        $template = $twig->load('cadastro_plano.html');

        $parameters['name_user'] = $_SESSION['usr']['name_user'];
        $parameters['tipoUsuario'] = $_SESSION['usr']['tipoUsuario'];
        return $template->render($parameters);
    }

    public function inserirPlano() {
        $plano = new Plano();

        try {
            $plano->setDescricao($_POST['descricao']);
            $plano->setValor($_POST['valor']);

            $plano->inserir();
            echo '<script>alert("Plano cadastrada com sucesso!");</script>';
            echo '<script>location.href="http://localhost/SisAcad2.0/admin/telaPlano"</script>';
        } catch (Exception $exc) {
            echo '<script>alert("' . $exc->getMessage() . '");</script>';
            echo '<script>location.href="http://localhost/SisAcad2.0/admin/telaCadPlano"</script>';
        }
    }

    public function caixa_adm() {
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        $template = $twig->load('caixa_adm.html');
        $caixa = new Caixa();
        $lista = $caixa->listarCaixa();
        $parametro = array();
        $parametro['caixa'] = $lista;
        $parametro['name_user'] = $_SESSION['usr']['name_user'];
        return $template->render($parametro);
    }

    public function clientes() {

        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        $template = $twig->load('clientes_adm.html');
        $usuarios = new User();
        $lista = $usuarios->buscarCliente();
        $parametros = array();
        $parametros['clientes'] = $lista;
        $parametros['name_user'] = $_SESSION['usr']['name_user'];
        return $template->render($parametros);
    }

    public function matriculados() {
        $loader = new \Twig\Loader\FilesystemLoader('app/view/');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        $template = $twig->load('matriculados.html');
        $faturas = new CadFaturas();
        $lista = $faturas->faturas();
        $parametro = array();
        $parametro['matriculados'] = $lista;
        $parametro['name_user'] = $_SESSION['usr']['name_user'];
        $parametro['tipoUsuario'] = $_SESSION['usr']['tipoUsuario'];
        return $template->render($parametro);
    }

    public function telaFaturas($parsId) {
        $id = $parsId['id'];
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        $template = $twig->load('faturas.html');
        $usuario = new User();
        $lista1 = $usuario->buscarId($id);
        $fatura = new CadFaturas();
        $lista4 = $fatura->listarFaturas($id);
        $parametro = array();
        $parametro['id'] = $lista1->getIdusuario();
        $parametro['faturas'] = $lista4;
        $parametro['name_user'] = $_SESSION['usr']['name_user'];
        $parametro['tipoUsuario'] = $_SESSION['usr']['tipoUsuario'];
        $parametro['id_user'] = $_SESSION['usr']['idusuario'];
        return $template->render($parametro);
    }

    public function exAlunos() {
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        $template = $twig->load('exalunos.html');

        $exaluno = new CadFaturas();

        $lista = $exaluno->exalunos();

        $parametros = array();
        $parametros['exaluno'] = $lista;
        $parametros['name_user'] = $_SESSION['usr']['name_user'];
        return $template->render($parametros);
    }

    public function faturamento() {
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        $template = $twig->load('faturamento.html');

        $cliente = new CadFaturas();
        $dado = $cliente->qntAlunos();
        $fat = $cliente->faturamento();
        $planos = new Plano();
        $qntP = $planos->qntPlanos();
        $modal = new Modalidades();
        $qntM = $modal->qntModalidades();
        $deve = new Caixa();
        $devem = $deve->inadiplentes();
        $parametros = array();
        $parametros['qnt'] = $dado;
        $parametros['fat'] = $fat;
        $parametros['plano'] = $qntP;
        $parametros['modal'] = $qntM;
        $parametros['deve'] = $devem;
        $tipo = $cliente->tipoPag();
        $parametros['tipo'] = $tipo;



        $parametros['name_user'] = $_SESSION['usr']['name_user'];
        $parametros['id_user'] = $_SESSION['usr']['idusuario'];
        return $template->render($parametros);
    }

    public function gerar() {
        $loader = new \Twig\Loader\FilesystemLoader('app/view/');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        $template = $twig->load('dados.html');
        $dados = new CadFaturas();
        $lista = $dados->gerarDados();
        $parametro = array();
        $parametro['dados'] = $lista;

        $arquivo = 'dadosFaturamentoSisAcad.xls';
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: application/x-msexcel");
        header("Content-Disposition: attachment; filename=\"{$arquivo}\"");
        header("Content-Description: PHP Generated Data");


        $parametro['name_user'] = $_SESSION['usr']['name_user'];
        $parametro['tipoUsuario'] = $_SESSION['usr']['tipoUsuario'];
        return $template->render($parametro);
    }

    public function gerarDadosCaixa() {
        $loader = new \Twig\Loader\FilesystemLoader('app/view/');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        $template = $twig->load('dadosCaixa.html');
        $dadosCaixa = new Caixa;
        $dados = $dadosCaixa->gerarDadosCaixa();

        $parametro = array();
        $parametro['caixa'] = $dados;

        $arquivo = 'dadosCaixaSisAcad.xls';
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: application/x-msexcel");
        header("Content-Disposition: attachment; filename=\"{$arquivo}\"");
        header("Content-Description: PHP Generated Data");


        $parametro['name_user'] = $_SESSION['usr']['name_user'];
        $parametro['tipoUsuario'] = $_SESSION['usr']['tipoUsuario'];
        return $template->render($parametro);
    }

    public function editarSenha($id) {

        $id = $_POST['id'];
      
        $senha = $_POST['senha'];
        $senha1 = $_POST['senha1'];
        $senha2 = $_POST['senha2'];
        $nova = new User();
        $antes = $nova->buscarId($id);
        $a1 = $antes->getSenha();

        if ($senha == $a1 && $senha1 == $senha2) {

            $nova->editarSenha($_POST);
            echo '<script>alert("Senha alterada com sucesso!");</script>';
            echo '<script>location.href="http://localhost/SisAcad2.0/dashboard"</script>';
        } else {
            echo '<script>alert("Senhas não confere");</script>';
            echo '<script>location.href="http://localhost/SisAcad2.0/dashboard"</script>';
        }
    }

}
