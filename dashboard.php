<?php
 require_once("templates/header.php");
 //verifica se o usuario ja esta autenticado
 require_once("models/User.php");
 require_once("dao/UserDao.php");

 $user = new User();
 $userDao = new UserDao($conn, $BASE_URL);
 $userData = $userDao->verifyToken(true);

?>
  <div id="main-container" class="conteiner-fluid">
    <h2 class="section-title">Dashboard</h2>
    <p class="section-description">Adicione ou atualize as informaçoes dos filmes que você enviou</p>
    <div class="col-md-12" id="add-movie-container">
      <a href="<?=$BASE_URL?>newmovie.php" class="btn card-btn">
        <i class="fas fa-plus"></i>Adicionar Filme
      </a>
    </div>
    <div class="col-md-12" id="movies-dashboard">
      <table class="table">
        <thead>
          <th scope="col">#</th>
          <th scope="col">Titulo</th>
          <th scope="col">Nota</th>
          <th scope="col" class="actions-colunm">Ações</th>
        </thead>      
      <tbody>
        <tr>
          <td scope = row>1</td>
          <td><a href="#" class="table-movie-title">Titulo</a></td>
          <td><i class="fas fa-star"></i>9</td>
          <td class="actions-column">
            <a href="#" class="edit-btn">
              <i class="far fa-edit"></i>Editar
            </a>
            <form action="">
              <button type="submit" class="delete-btn">
                <i class="fas a-times"></i>Deletar
              </button>
            </form>
          </td>
        </tr>
      </tbody>
    </table>
    </div>
  </div>

<?php
  require_once("templates/footer.php");
?>