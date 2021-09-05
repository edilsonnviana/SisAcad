<?php
	session_start();

	require_once 'app/core/Core.php';

	require_once 'lib/Database/Connection.php';

	require_once 'app/controller/LoginController.php';
	require_once 'app/controller/DashboardController.php';
        require_once 'app/controller/AdminController.php';
        
	require_once 'app/model/User.php';
        require_once 'app/model/TipoUsuario.php';
        require_once 'app/model/Modalidades.php';
        require_once 'app/model/Plano.php';
        require_once 'app/model/Caixa.php';
        require_once 'app/model/Ficha.php';
        require_once 'app/model/CadFaturas.php';
	require_once 'vendor/autoload.php';
       

	$core = new Core;
	echo $core->start($_GET);