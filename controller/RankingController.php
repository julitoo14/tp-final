<?php

class RankingController
{

    private $presenter;
    private $usersModel;

    public function __construct($presenter, $usersModel)
    {
        $this->presenter = $presenter;
        $this->usersModel = $usersModel;
    }

    public function getRanking()
    {
        $username = isset($_SESSION['user']) ? $_SESSION['user'][0]['username'] : null;
        $userId = isset($_SESSION['user']) ? $_SESSION['user'][0]['_id'] : null;
        $maxScore = $this->usersModel->getMaxScore($userId);
        $topUsers = $this->usersModel->getTopUsers();
        $this->presenter->render("view/RankingView.mustache", [
            'username' => $username,
            'maxScore' => $maxScore,
            'topUsers' => $topUsers
        ]);
    }
}