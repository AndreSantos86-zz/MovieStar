<?php

  class Review {

    public $id;
    public $rating;
    public $review;
    public $users_id;
    public $movies_id;

  }

  interface ReviewDAOInterface {

    public function buildReview($data); // criando objetos arrey com dados
    public function create(Review $review);// recebe review
    public function getMoviesReview($id);// saber notas por id
    public function hasAlreadyReviewed($id, $userId);// saber se o usuario ja fez review
    public function getRatings($id);// receebe as notas

  }