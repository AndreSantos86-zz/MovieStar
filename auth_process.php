<?php
  require_once("globals.php");
  require_once("db.php");
  require_once("models/User.php");
  require_once("models/Message.php");
  require_once("dao/UserDAO.php");
  
  $message = new Message($BASE_URL);
  $userDao = new UserDAO($conn,$BASE_URL);

  // Resgata tipo do formulario
  $type = filter_input(INPUT_POST,"type");

  // Verificação do tipo de formulario
  if($type === "register"){
      $name = filter_input(INPUT_POST, "name");
      $lastname = filter_input(INPUT_POST, "lastname");
      $email = filter_input(INPUT_POST, "email");
      $password = filter_input(INPUT_POST, "password");
      $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

      // Verificação de dados minimos

      if($name && $lastname && $email && $password){
        // Verificar senhas
        if($password === $confirmpassword){
          //verificar se o email esta no sistema
          if($userDao->findByEmail($email)=== false){
            $user = new User();
            // criação e token e senha
            $userToken = $user->generateToken();
            $finalPassword = $user->generatePassword($password);

              $user->name = $name ;
              $user->lastname = $lastname;
              $user->email = $email;
              $user->password = $finalPassword;
              $user->token = $userToken;
              $auth = true;
              $userDao->create($user, $auth);

          }else{
            // email ja cadastrado
            $message->setMessage("Email ja cadastrado!","error", "back");
          }
        }else{
          $message->setMessage("As senhas não são iguais", "error", "back");
        }
      }else{
        // enviar mensagem de erro, dados faltantes
        $message->setMessage("Por favor, preencha todos os campos.", "error", "back");
      }



  } else if($type === "login"){
    $email = filter_input(INPUT_POST, "email");
    $password = filter_input(INPUT_POST,"password");
    // Tentar autenticar o usuario
    if($userDao->authenticateUser($email,$password)){
      $message->setMessage("Seja bem-vindo", "success", "editprofile.php");
      //Redireciona caso nao conseguir autenticar.
    }else{
      $message->setMessage("Usuario e/ou senha invalidos","error","back");
    }
  }else{
    $message->setMessage("Informações invalidas!","error","index.php");
  }
?>