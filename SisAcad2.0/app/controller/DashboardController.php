<?php

class DashboardController {

    public function index() {
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        $template = $twig->load('dashboard.html');
        $parameters['name_user'] = $_SESSION['usr']['name_user'];
        $parameters['tipoUsuario'] = $_SESSION['usr']['tipoUsuario'];
        $parameters['id_user'] = $_SESSION['usr']['idusuario']; 

        if ($_SESSION['usr']['tipoUsuario'] == 1) {
            $template = $twig->load('admin.html');
            
        }
        return $template->render($parameters);
    }

    public function logout() {
        unset($_SESSION['usr']);
        session_destroy();
        header('Location: http://localhost/SisAcad2.0/6_SistemaLogin');
    }

    public function telaCliente() {
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        $template = $twig->load('clientes.html');
        $usuarios = new User();
        $lista = $usuarios->buscarCliente();
        $parametros = array();
        $parametros['clientes'] = $lista;
        $parametros['name_user'] = $_SESSION['usr']['name_user'];
        return $template->render($parametros);
    }

    public function telaEditCliente($parsId) {
        $id = $parsId['id'];
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        $template = $twig->load('editar_cliente.html');
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

    public function editarCliente() {
        $usuario = new User();
        try {
            $usuario->editaFunc($_POST);
            echo '<script>alert("Cliente editado com sucesso!");</script>';
            echo '<script>location.href="http://localhost/SisAcad2.0/dashboard/telaCliente"</script>';
        } catch (Exception $ex) {
            echo '<script>alert("' . $ex->getMessage() . '");</script>';
        }
    }

    public function telaMatricula($parsId) {
        $id = $parsId['id'];
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        $template = $twig->load('matricula.html');
        $modalidade = new Modalidades();
        $lista = $modalidade->listar();
        $parametros = array();
        $parametros['modalidades'] = $lista;
        $usuario = new User();
        $lista1 = $usuario->buscarId($id);
        $parametros['nome'] = $lista1->getNomeUsuario();
        $parametros['id'] = $lista1->getIdusuario();
        $plano = new Plano();
        $lista2 = $plano->planoMatricula();
        $parametros['planos'] = $lista2;
        $modal = new Caixa();
        $lista3 = $modal->modalidadesFim($id);
        $parametros['moda'] = $lista3;
        $parametros['name_user'] = $_SESSION['usr']['name_user'];
        $parametros['idusuario'] = $_SESSION['usr']['idusuario'];
        return $template->render($parametros);
    }

    public function telaCaixa() {
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        $template = $twig->load('caixa.html');
        $caixa = new Caixa();
        $lista = $caixa->listarCaixa();
        $parametro = array();
        $parametro['caixa'] = $lista;
        $parametro['name_user'] = $_SESSION['usr']['name_user'];
        return $template->render($parametro);
    }

    public function cadCliente() {

        $loader = new \Twig\Loader\FilesystemLoader('app/view/');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        $template = $twig->load('cadastro_clientes.html');
        $parameters['name_user'] = $_SESSION['usr']['name_user'];
        $parameters['tipoUsuario'] = $_SESSION['usr']['tipoUsuario'];
        return $template->render($parameters);
    }

    public function inserirCliente() {
        $usuario = new User();
        try {
            $usuario->__set('tipoUsuario', 4);
            $usuario->__set('nomeUsuario', $_POST['nome']);
            $usuario->__set('cpf', $_POST['cpf']);
            $usuario->__set('dataNascimento', $_POST['dtnasc']);
            $usuario->__set('sexo', $_POST['sexo']);
            $usuario->__set('telefone', $_POST['tel']);
            $usuario->__set('telefone2', $_POST['tel2']);
            $usuario->__set('email', $_POST['email']);
            $usuario->__set('endereco', $_POST['endereco']);
            $usuario->inserirCliente();
            echo '<script>alert("Cliente cadastrado com sucesso!");</script>';
            echo '<script>location.href="http://localhost/SisAcad2.0/dashboard/telaCliente"</script>';
        } catch (Exception $exc) {
            echo '<script>alert("' . $exc->getMessage() . '");</script>';
            echo '<script>location.href="http://localhost/SisAcad2.0/admin/index"</script>';
        }
    }

    public function telaAcesso($parsId) {
        $id = $parsId['id'];
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        $template = $twig->load('ficha_cliente.html');
        $clientes = new Caixa();
        $lista = $clientes->receberCaixa($id);
        $usuario = new User();
        $lista1 = $usuario->buscarId($id);
        $plano = new Plano();
        $lista2 = $plano->listarPlanoCaixa($id);
        $lista3 = $plano->mensalidades($id);
        $lista4 = $plano->novaMensalidades($id);
        $lista5 = $clientes->mensalidade($id);
        $parametro = array();
        $parametro['nome'] = $lista1->getNomeUsuario();
        $parametro['id'] = $lista1->getIdusuario();
        $parametro['plano'] = $lista2->getDescricao();
        $parametro['nrParcelas'] = $lista2->getNrparcelas();
        $parametro['valor'] = $lista2->getValor();
        $parametro['mensalidade'] = $lista5;
        
        
        $parametro['nova'] = $lista4->getMensalidade();
        $parametro['clientes'] = $lista;
        $parametro['name_user'] = $_SESSION['usr']['name_user'];
        $parametro['tipoUsuario'] = $_SESSION['usr']['tipoUsuario'];
        $parametro['id_user'] = $_SESSION['usr']['idusuario'];
        return $template->render($parametro);
    }

    public function editarMat($parsId) {
        $id = $parsId['id'];
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        $template = $twig->load('editarMat.html');
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

        return $template->render($parametros);
    }

    public function excluirCaixa($id) {
        $id = $id['id'];
        $caixa = new Caixa();
        try {
            $caixa->excluirCaixa($id);
            echo '<script>alert("Matricula excluida com sucesso!");</script>';
            echo '<script>location.href="http://localhost/SisAcad2.0/dashboard/telaCliente"</script>';
        } catch (Exception $exc) {
            echo '<script>alert("' . $ex->getMessage() . '");</script>';
        }
    }

    public function editarMatricula($parsId) {
        $id = $parsId['id'];
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        $template = $twig->load('editar_matricula.html');

        $modalidade = new Modalidades();
        $lista = $modalidade->listar();

        $parametros = array();
        $parametros['modalidades'] = $lista;

        $usuario = new User();
        $lista1 = $usuario->buscarId($id);

        $parametros['nome'] = $lista1->getNomeUsuario();
        $parametros['id'] = $lista1->getIdusuario();

        $plano = new Plano();
        $lista2 = $plano->planoMatricula();
        $parametros['planos'] = $lista2;
        $lista4 = $plano->listarPlanoCaixa($id);
        //$parametro = array();
        $parametros['plano'] = $lista4->getDescricao();
        $parametros['idplano'] = $lista4->getIdplano();
        $parametros['valor'] = $lista4->getValor();

        $caixa = new Caixa();
        $lista3 = $caixa->editarCaixa($id);
        $parametros['modal'] = $lista3;


        $parametros['name_user'] = $_SESSION['usr']['name_user'];
        $parametros['idusuario'] = $_SESSION['usr']['idusuario'];

        return $template->render($parametros);
    }

    public function editarMatri() {
        // $id = $_POST['cliente'];
        if (!empty($_POST['modal'])) {
            $moda = $_POST['modal'];
            try {
                $caixa = new Caixa();
                foreach ($moda as $value) {

                    $caixa->setNomeCliente($_POST['cliente']);
                    $caixa->setNomeModalidade($value);
                    $caixa->setPlano($_POST['plano']);
                    $caixa->editarMatriculas();
                }
                echo '<script>alert("Cliente matriculado com sucesso!");</script>';
                echo '<script>location.href="http://localhost/SisAcad2.0/dashboard/telaCaixa"</script>';
            } catch (Exception $exc) {
                echo '<script>alert("' . $exc->getMessage() . '");</script>';
                echo '<script>location.href="http://localhost/SisAcad2.0/dashboard/index"</script>';
            }
        } else {
            $moda = 9;
            try {
                $caixa = new Caixa();

                $caixa->setNomeCliente($_POST['cliente']);
                $caixa->setNomeModalidade($moda);
                $caixa->setPlano($_POST['plano']);
                $caixa->editarMatriculas();

                echo '<script>alert("Cliente matriculado com sucesso!");</script>';
                echo '<script>location.href="http://localhost/SisAcad2.0/dashboard/telaCaixa"</script>';
            } catch (Exception $exc) {
                echo '<script>alert("' . $exc->getMessage() . '");</script>';
                echo '<script>location.href="http://localhost/SisAcad2.0/dashboard/index"</script>';
            }
        }
    }

    public function cadMatricula() {

        if (!empty($_POST['modal'])) {
            $moda = $_POST['modal'];
            try {
                $caixa = new Caixa();
                foreach ($moda as $value) {
                    $caixa->setNomeUsuario($_POST['usuario']);
                    $caixa->setNomeCliente($_POST['id']);
                    $caixa->setNomeModalidade($value);
                    $caixa->setPlano($_POST['pl']);
                    $caixa->matricula();
                }
                echo '<script>alert("Cliente matriculado com sucesso!");</script>';
                echo '<script>location.href="http://localhost/SisAcad2.0/dashboard/telaCaixa"</script>';
            } catch (Exception $exc) {
                echo '<script>alert("' . $exc->getMessage() . '");</script>';
                echo '<script>location.href="http://localhost/SisAcad2.0/dashboard/index"</script>';
            }
        } else {
            $moda = 9;
            try {
                $caixa = new Caixa();
                $caixa->setNomeUsuario($_POST['usuario']);
                $caixa->setNomeCliente($_POST['id']);
                $caixa->setNomeModalidade($moda);
                $caixa->setPlano($_POST['pl']);
                $caixa->matricula();

                echo '<script>alert("Cliente matriculado com sucesso!");</script>';
                echo '<script>location.href="http://localhost/SisAcad2.0/dashboard/telaCaixa"</script>';
            } catch (Exception $exc) {
                echo '<script>alert("' . $exc->getMessage() . '");</script>';
                echo '<script>location.href="http://localhost/SisAcad2.0/dashboard/index"</script>';
            }
        }
    }

    public function cadMat() {
        $plano = $_POST['pl'];
        $moda = $_POST['modal'];

        if ($plano == 3) {
            
        } else {

            echo $plano;
            echo "<hr/>";
            foreach ($moda as $value) {
                echo $value;
            }
        }
    }

    public function editarMatriculas() {
        
        $este =  $_POST['este'];
        $idcaixa = $_POST['idcaixa']; 
        
        
      
               
        try {
              $editarMatricula = new caixa();  
                     foreach ($este as $value) {
                //echo $value;
                $editarMatricula->editarMatricula($value);   
                }  
                 
             echo '<script>alert("Modalidade Finalizada com sucesso!");</script>';
            // echo '<script>location.href="http://localhost/SisAcad2.0/dashboard/telaCaixa"</script>';
        } catch (Exception $ex) {
           echo '<script>alert("'.$ex->getMessage().'");</script>';  
        } 
 
        
    }

    public function pagamento() {
        $caixa = new Caixa();
        try {
            $caixa->pagamento($_POST);
            echo '<script>alert("pagamento realizado com sucesso!");</script>';
            echo '<script>location.href="http://localhost/SisAcad2.0/admin/matriculados"</script>';
        } catch (Exception $ex) {
            echo '<script>alert("' . $ex->getMessage() . '");</script>';
        }
    }

    public function matriculados() {
        $loader = new \Twig\Loader\FilesystemLoader('app/view/');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        $template = $twig->load('matriculadosOper.html');
        $faturas = new CadFaturas();
        $lista = $faturas->faturas();
     
        $parametro = array();
        $parametro['matriculados'] = $lista;
       
        $parametro['name_user'] = $_SESSION['usr']['name_user'];
        $parametro['tipoUsuario'] = $_SESSION['usr']['tipoUsuario'];
        return $template->render($parametro);
    }

    public function cadPagamento() {
        try {
            $parcelas = $_POST['nrParcelas'];
            $gravadata = $_POST['datapag'];
            $parcela = new CadFaturas();
            $diavencimento = $_POST['dia'];
            for ($i = 1; $i <= $parcelas; $i++) {
                $parcela->setCliente($_POST['id']);
                $parcela->setUsuario($_POST['usuario']);
                $parcela->setDataVenci($gravadata);
                $parcela->setNrParcelas($parcelas);
                $parcela->setValorParcelas($_POST['mensal']);
                // $parcela->setTipoPag($_POST['pag']);
                $parcela->setNumParcela($i);
                $parcela->cadParcelas();
                if (!empty($diavencimento)) { //Se o dia de vencimento não tiver em branco
                    $dia = $diavencimento; //o dia será igual ao dia de vencimento
                } else {
                    $dia = date("d", strtotime($gravadata)); //se o dia de vencimento tiver em branco, então o dia será igual ao dia da data de vencimento
                }
                $mes = date("m", strtotime($gravadata)) + 1;
                $ano = date("Y", strtotime($gravadata));
                if ($mes == 13) {  
                    $mes = 1;
                    $ano = $ano + 1;
                }
                if ($dia == 30 && $mes == 2) {  
                    $gravadata = $ano . "-" . $mes . "-28";
                } else {
                    $gravadata = $ano . "-" . $mes . "-" . $dia;
                }
            } echo '<script>alert("pagamento realizado com sucesso!");</script>';
            echo '<script>location.href="http://localhost/SisAcad2.0/dashboard/clientesMatriculados"</script>';
        } catch (Exception $exc) {
            echo '<script>alert("' . $ex->getMessage() . '");</script>';
        }
    }

    public function telaParcelas($parsId) {
        $id = $parsId['id'];
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        $template = $twig->load('parcelas.html');
        $parametros = array();
        $usuario = new User();
        $lista1 = $usuario->buscarId($id);
        $parametros['nome'] = $lista1->getNomeUsuario();
        $parametros['id'] = $lista1->getIdusuario();
        $plano = new Plano();
        $lista2 = $plano->listarPlanoCaixa($id);
        $parametros['plano'] = $lista2->getDescricao();
        $parametros['valor'] = $lista2->getValor();
        $lista3 = $plano->mensalidades($id);
        $parametros['mensalidade'] = $lista3->getMensalidade();
        $faturas = new CadFaturas();
        $lista4 = $faturas->listarFaturas($id);
        $parametros['cliente'] = $lista4;
        $lista6 = $faturas->fimContrato($id);
        $parametros['fim'] = $lista6;
        $modal = new Caixa();
        $lista5 = $modal->modalidade($id);
        $parametros['modal'] = $lista5;
        


        $parametros['name_user'] = $_SESSION['usr']['name_user'];
        $parametros['idusuario'] = $_SESSION['usr']['idusuario'];

        return $template->render($parametros);
    }

    public function cadPag() {
       
        try {          
        $pagamento = new CadFaturas();
               $id = $_POST['id'];
         //echo $id;          
            $pagamento->cadpag($_POST);
            
   
         echo '<script>alert("Pagamento efetuado com sucesso!");</script>';  
        // echo '<script>location.href="http://localhost/SisAcad2.0/dashboard/telaParcelas&id="+$id</script>';
          echo '<script>location.href="http://localhost/SisAcad2.0/dashboard/matriculados "</script>';
        } catch (Exception $ex) {
            echo '<script>alert("' . $ex->getMessage() . '");</script>';
        } 
        
    }

    public function clientesMatriculados() {
        $loader = new \Twig\Loader\FilesystemLoader('app/view/');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        $template = $twig->load('matriculadosOper.html');
        $faturas = new CadFaturas();
        $lista = $faturas->faturas();
        $parametro = array();
        $parametro['matriculados'] = $lista;
        $parametro['name_user'] = $_SESSION['usr']['name_user'];
        $parametro['tipoUsuario'] = $_SESSION['usr']['tipoUsuario'];
        return $template->render($parametro);
    }
        public function fimMatricula(){
            $fim = new CadFaturas();
        try {
	
             $fim->fimMatricula($_GET);
            echo '<script>alert("Contrato Finalizado!");</script>';
           echo '<script>location.href="http://localhost/SisAcad2.0/dashboard/exAlunos"</script>';
        } catch (Exception $ex) {
            echo '<script>alert("' . $ex->getMessage() . '");</script>';
        }
            
        }
        public function exAlunos() {
       $loader = new \Twig\Loader\FilesystemLoader('app/view');
        $twig = new \Twig\Environment($loader, [
        'cache' => '/path/to/compilation_cache',
         'auto_reload' => true,
        ]);
        $template = $twig->load('exAlunosOper.html');
        
   
        
        $exaluno = new CadFaturas();
        
        $lista = $exaluno->exalunos();
      
       $parametros['exaluno'] = $lista; 
       
       $parametros['name_user'] = $_SESSION['usr']['name_user'];
       return $template->render($parametros); 
    }
    
    public function telaHistorico($parsId) {
        $id = $parsId['id'];
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        $template = $twig->load('historico.html');
        $usuario = new User();
        $lista1 = $usuario->buscarId($id);
        $historico = new Caixa();
        $lista2 = $historico->listarHistorico($id);
        $historico2 =  new CadFaturas();
        $lista3 = $historico2->lista_Historico($id);
        $lista5 = $historico2->todosPlanos($id);
        $lista6 = $historico2->valoresPagos($id);
        $lista7 = $historico->modalidadesFim($id);
        
        
         $parametro['modal'] = $lista7;
        $parametro['valores'] = $lista6;
        $parametro['planos'] = $lista5;
        $parametro['historicoPag'] = $lista3;
        $parametro['historicoCaixa'] = $lista2;
        $parametro['nome'] = $lista1->getNomeUsuario();
        $parametro['name_user'] = $_SESSION['usr']['name_user'];
        $parametro['tipoUsuario'] = $_SESSION['usr']['tipoUsuario'];
        $parametro['id_user'] = $_SESSION['usr']['idusuario'];
        return $template->render($parametro);
    }
        public function finalizarMatricula() {
        $finalizar = new Caixa();
        try {
            //$usuario->editaFunc($_POST);
            echo '<script>alert("Matricula finalizada com sucesso!");</script>';
            echo '<script>location.href="http://localhost/SisAcad2.0/dashboard/telaCaixa"</script>';
        } catch (Exception $ex) {
            echo '<script>alert("' . $ex->getMessage() . '");</script>';
        }
    }
}
