<?php
  require_once("globals.php");
  require_once("db.php");
  require_once("models/Movie.php");
  require_once("models/Message.php");
  require_once("dao/UserDao.php");
  require_once("dao/MovieDao.php");

  $message = new Message($BASE_URL);
  $userDao = new UserDAO($conn, $BASE_URL);
  $movieDao = new MovieDAO($conn, $BASE_URL);

  // Resgata o tipo do formulario
  $type = filter_input(INPUT_POST, "type");
  //resgata dadso do usuario
  $userData = $userDao->verifyToken();

  if ($type === "create") {
    //receber dados dos inputs
    $title = filter_input(INPUT_POST, "title");
    $description = filter_input(INPUT_POST, "description");
    $trailer = filter_input(INPUT_POST, "trailer");
    $category = filter_input(INPUT_POST, "category");
    $length = filter_input(INPUT_POST, "length");
    $movie = new Movie();
    // validação minima de dados
    if (!empty($title) && !empty($description) && !empty($category)) {
      $movie->title = $title;
      $movie->description = $description;
      //$movie->trailer = $trailer;
      $movie->category = $category;
      $movie->length = $length;
      $movie->users_id = $userData->id;
      //upload de imagem do filme
      
      // Alterar link do video 
      $editLinkTrailer = getYoutubeEmbedUrl($trailer);
      $movie->trailer = $editLinkTrailer;
      

      if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {
        $image = $_FILES["image"];
        $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
        $jpgArray = ["image/jpeg", "image/jpg"];

        // checagem do tipo de imagem
        if (in_array($image["type"], $imageTypes)) {
          //checar se e jpg
          if (in_array($image["type"], $jpgArray)) {
            $imageFile = imagecreatefromjpeg($image["tmp_name"]);
            //imagem e png
          } else {
            $imageFile = imagecreatefrompng($image["tmp_name"]);
          }
          // Gerando o nome da imagem
          $imageName = $movie->imageGenerateName();
          imagejpeg($imageFile, "./img/movies/" . $imageName, 100);
          $movie->image = $imageName;
        } else {
          $message->setMessage("imagem invalida, adicione .png ou .jpg!", "error", "back");
        }
      }
      
      
      $movieDao->create($movie);
    } else {
      $message->setMessage("Você precisa adicionar pelo menos: titulo, descrição e categoria!", "error", "back");
    }
  }elseif($type === "delete"){
    //Recebe os dados do form
    $id = filter_input(INPUT_POST,"id");
    $movie = $movieDao->findById($id);
    if($movie){
      //Verificar se o filme e do usuario
      if($movie->users_id === $userData->id){
        $movieDao->destroy($movie->id);
      }else{
        $message->setMessage("informações invalidas!", "error", "index.php");
      }
    }else{
      $message->setMessage("informações invalidas!", "error", "index.php");
    }
  }else if($type === "update"){
    // Receber os dados dos inputs
    $title = filter_input(INPUT_POST, "title");
    $description = filter_input(INPUT_POST, "description");
    $trailer = filter_input(INPUT_POST, "trailer");
    $category = filter_input(INPUT_POST, "category");
    $length = filter_input(INPUT_POST, "length");
    $id = filter_input(INPUT_POST, "id");

    $movieData = $movieDao->findById($id);

    // Verifica se encontrou o filme
    if($movieData) {

      // Verificar se o filme é do usuário
      if($movieData->users_id === $userData->id) {

        // Validação mínima de dados
        if(!empty($title) && !empty($description) && !empty($category)) {

          // Edição do filme
          $movieData->title = $title;
          $movieData->description = $description;          
          $movieData->category = $category;
          $movieData->length = $length;
          // Alterar link do video 
          $editLinkTrailer = getYoutubeEmbedUrl($trailer);
          $movieData->trailer = $editLinkTrailer;

          // Upload de imagem do filme
          if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

            $image = $_FILES["image"];
            $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
            $jpgArray = ["image/jpeg", "image/jpg"];

            // Checando tipo da imagem
            if(in_array($image["type"], $imageTypes)) {

              // Checa se imagem é jpg
              if(in_array($image["type"], $jpgArray)) {
                $imageFile = imagecreatefromjpeg($image["tmp_name"]);
              } else {
                $imageFile = imagecreatefrompng($image["tmp_name"]);
              }

              // Gerando o nome da imagem
              $movie = new Movie();

              $imageName = $movie->imageGenerateName();

              imagejpeg($imageFile, "./img/movies/" . $imageName, 100);

              $movieData->image = $imageName;

            } else {

              $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");

            }

          }

          $movieDao->update($movieData);

        } else {

          $message->setMessage("Você precisa adicionar pelo menos: título, descrição e categoria!", "error", "back");

        }

      } else {

        $message->setMessage("Informações inválidas!", "error", "index.php");

      }

    } else {

      $message->setMessage("Informações inválidas!", "error", "index.php");

    }

  
  } else {
    $message->setMessage("informações invalidas!", "error", "index.php");
  }

  

   function getYoutubeEmbedUrl($url){
     $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_-]+)\??/i';
     $longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))([a-zA-Z0-9_-]+)/i';

    if (preg_match($longUrlRegex, $url, $matches)) {
        $youtube_id = $matches[count($matches) - 1];
    }

    if (preg_match($shortUrlRegex, $url, $matches)) {
        $youtube_id = $matches[count($matches) - 1];
    }
    return 'https://www.youtube.com/embed/' . $youtube_id ;
}
  
  