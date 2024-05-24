<?php
include_once ("controller/UsersController.php");
include_once ("controller/HomeController.php");

include_once ("model/UsersModel.php");

include_once ("helper/Database.php");
include_once ("helper/Router.php");
include_once ("helper/Presenter.php");
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
        return new HomeController(self::getPresenter());
    }


    // MODELS
    private static function getUsersModel()
    {
        return new UsersModel(self::getDatabase());
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