<?php
 require_once("templates/header.php");
 //verifica se o usuario ja esta autenticado
 require_once("models/User.php");
 require_once("dao/UserDao.php");

 $user = new User();
 $userDao = new UserDao($conn, $BASE_URL);
 $userData = $userDao->verifyToken(true);

?>
    <div id="main-container" class="container-fluid">
        <div class="offset-md-4 col-md-4 new-movie-container">
            <h1 class="page-title">Adicionar Filme</h1>
            <p class="page-description">Adicione sua critica e compartilhe com o mundo!</p>
            <form action="<?=$BASE_URL?>movie_process.php" id="add-movie-form" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="type" value="create">
                <div class="form-group">
                    <label for="title">Titulo:</label>
                    <input type="text" class="form-control" name="title" id="title" placeholder="Digite o titulo do seu filme">
                </div>
                <div class="form-group">
                    <label for="image">Imagem:</label>
                    <input type="file" class="form-control-file" name="image" id="image" >
                </div>
                <div class="form-group">
                    <label for="title">Duração:</label>
                    <input type="text" class="form-control" name="length" id="length" placeholder="Digite a duração do filme">
                </div>
                <div class="form-group">
                    <label for="title">Categoria:</label>
                    <select  class="form-control" name="category" id="category">
                        <option value="">Selecione</option>
                        <option value="Ação">Ação</option>
                        <option value="Drama">Drama</option>
                        <option value="Comédia">Comédia</option>
                        <option value="Fantasia/Ficção">Fantasia/Ficção</option>
                        <option value="Romance">Romance</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="title">Trailer:</label>
                    <input type="text" class="form-control" name="trailer" id="trailer" placeholder="Insira o link do trailer.">
                </div>
                <div class="form-group">
                    <label for="title">Descrição:</label>
                    <textarea name="description" id="description"  rows="5" class="form-control" placeholder="Descreva o filme..."></textarea>
                </div>
                <input type="submit" class="btn card-btn" value="Adicionar Filme">
            </form>
        </div>
    </div>

<?php
 require_once("templates/footer.php");
?>  