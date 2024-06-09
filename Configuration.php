<?php
include_once ("controller/UsersController.php");
include_once ("controller/HomeController.php");
include_once ("controller/GameController.php");

include_once ("model/UsersModel.php");
include_once ("model/PreguntasModel.php");
include_once ("model/PartidasModel.php");

include_once ("helper/Database.php");
include_once ("helper/Router.php");
include_once ("helper/MustachePresenter.php");

include_once('vendor/mustache/src/Mustache/Autoloader.php');

class Configuration
{
    // CONTROLLERS
    public static function getUsersController()
    {
        return new UsersController(self::getUsersModel(), self::getPresenter());
    }

    public static function getHomeController()
    {
        return new HomeController(self::getPresenter(), self::getPartidasModel());
    }

    public static function getGameController()
    {
        return new GameController(self::getPresenter(), self::getPreguntasModel(), self::getPartidasModel());
    }

    // MODELS
    private static function getUsersModel()
    {
        return new UsersModel(self::getDatabase());
    }

    private static function getPreguntasModel()
    {
        return new PreguntasModel(self::getDatabase());
    }

    private static function getPartidasModel()
    {
        return new PartidasModel(self::getDatabase());
    }

    // HELPERS
    private static function getConfig()
    {
        return parse_ini_file("config/config.ini");
    }

    public static function getDatabase()
    {
        $config = self::getConfig();
        return new Database($config["servername"], $config["username"], $config["password"], $config["dbname"]);
    }

    public static function getRouter()
    {
        return new Router("getHomeController", "get");
    }

    private static function getPresenter()
    {
        return new MustachePresenter("view/template");
    }
}
