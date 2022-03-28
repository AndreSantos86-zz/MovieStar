<?php
 require_once("globals.php");
 require_once("db.php");
 require_once("models/User.php");
 require_once("models/Message.php");
 require_once("dao/UserDao.php");

 $message = new Message($BASE_URL);
 $userDao = new UserDao($conn, $BASE_URL);

 // Resgata o tipo do formulario
 $type = filter_input(INPUT_POST, "type");
 // Atualizar usuario
 if($type === "update"){
     //resgata dadso do usuario
     $userData = $userDao->verifyToken();
     
     //receber dados do post

     $name = filter_input(INPUT_POST, "name");
     $lastname = filter_input(INPUT_POST, "lastname");
     $email = filter_input(INPUT_POST, "email");
     $bio = filter_input(INPUT_POST, "bio");

     //Criar novo objeto de usuario
     $user = new User();

     //Preencher os dados do usuario
     $userData->name = $name;
     $userData->lastname = $lastname;
     $userData->email = $email;
     $userData->bio = $bio;
     // Upload da imagem
     if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])){
         $image = $_FILES["image"];
         $imageTypes = ["image.jpeg","image.jpg","image.png"];
         $jpgArray=["image.jpeg","image.jpg"];

         // checagem do tipo de imagem
         if(in_array($image["type"], $imageTypes)){
             //checar se e jpg
             if(in_array($image, $jpgArray)){
                 $imageFile = imagecreatefrompng($image["tmp_name"]);
                 //imagem e png
             }else{
                 $imageFile = imagecreatefromjpeg($image["tmp_name"]);
             }
             $imageName = $user->imageGenerateName();
             imagejpeg($imageFile,"./img/users/" . $imageName,100);
             $userData->image = $imageName;
         }else{
             $message -> setMessage("imagem invalida, adicione .png ou .jpg!","error","back");
         }
     }

     $userDao->update($userData);

     //atualizar senha do usuario
 }else if($type === "changepassword"){

     //receber dados do post
     $password = filter_input(INPUT_POST, "password");
     $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

     // Resgata dados usuario
     $userData = $userDao->verifyToken();
     $id = $userData->id;

     if($password == $confirmpassword){
         //criar um novo objeto de usuario
         $user = new User();
         $finalPassword = $user->generatePassword($password);
         $user->password = $finalPassword;
         $user->id = $id;

         $userDao->changePassword($user);

     }else{
         $message->setMessage("As senhas não são iguais!","error", "back");
     }

 }else {
     $message->setMessage("informações invalidas!","error", "index.php");
 }
?>