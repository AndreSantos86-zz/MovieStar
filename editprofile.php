<?php
 require_once("templates/header.php");
 require_once("dao/UserDao.php");
 require_once("models/User.php");

 $userDao = new UserDao($conn, $BASE_URL);
 $userData = $userDao->verifyToken();
 $user = new User();
 $fullName = $user->getFullName($userData);

 if($userData->image == ""){
     $userData->image = "user.png";
 }


?>
    <div id="main-container" class="container-fluid edit-profile-page">
        <div class="col-md-12">
            <form action="<?=$BASE_URL?>user_process.php" method="POST" enctype = "multipart/form-data">
              <input type="hidden" name="type" value="update">
              <div class="row">
                  <div class="col-md-4">
                      <h1><?=$fullName?></h1>
                      <p class="page-description">Altere seus dados no formulario abaixo:</p>
                      <div class="form-group">
                          <label for="name">Nome:</label>
                          <input type="text" name="name" id="name" class="form-control" placeholder="Digite seu nome" value="<?=$userData->name?>">
                      </div>
                      <div class="form-group">
                          <label for="lastname">Sobrenome:</label>
                          <input type="text" name="lastname" id="lastname" class="form-control" placeholder="Digite seu nome" value="<?=$userData->lastname?>">
                      </div>
                      <div class="form-group">
                          <label for="email">E-mail:</label>
                          <input type="text" readonly name="email" id="email" class="form-control disabled" placeholder="Digite seu e-mail" value="<?=$userData->email?>">
                      </div>
                      <input type="submit" value="Alterar" class="btn card-btn">
                  </div>
                  <div class="col-md-4">
                      <div id="profile-image-container" style="background-image: url('<?= $BASE_URL?>img/users/<?= $userData->image?>')"></div>
                    <div class="form-group">
                        <label for="image">Foto:</label>
                        <input type="file" class="form-control-file" name="image">
                    </div>
                    <div class="form-group">
                        <label for="bio">Sobre você:</label>
                        <textarea class="form-control" name="bio" id="bio" rows="5" placeholder= "Conte quem você é, oque faz e onde trabalha..."><?= $userData->bio?></textarea>
                    </div>  
                  </div>
              </div>
            </form>
            <div class="row" id="change-password-container">
                <div class="col-md-4">
                    <h2>Alterar senha:</h2>
                    <p class="page-desccription">Digite a nova senha e confirme, para alterar:</p>
                    <form action="<?=$BASE_URL?>user_process.php" method="POST">
                       <input type="hidden" name="type" value="changepassword">
                       
                       <div class="form-group">
                           <label for="password">Senha:</label>
                           <input type="password" class="form-control" name="password" id="password" placeholder = "digite a sua nova senha">
                       </div>
                       <div class="form-group">
                           <label for="confirmpassword">Cofirmação de senha:</label>
                           <input type="password" class="form-control" name="confirmpassword" id="confirmpassword" placeholder = "Confirme a sua nova senha">
                       </div>
                       <input type="submit" class="btn card-btn" value="Alterar Senha">
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php
 require_once("templates/footer.php");
?> 