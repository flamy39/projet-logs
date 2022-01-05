<?php

// Autoloading
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

require "./vendor/autoload.php";

// Création d'un logger associé à un channel
$appLogger = new Logger("app");
// Création d'un handler : gérer la destinatin du logs
$handler = new StreamHandler(__DIR__.'/logs/dev.log',Logger::ERROR);
// Associer le logger avec le handler
$appLogger->pushHandler($handler);
// Création d'un handler : destination console
$consoleHandler = new StreamHandler("php://stdout",Logger::DEBUG);
// Associer le handler avec le logger $appLogger
$appLogger->pushHandler($consoleHandler);

// Création d'un logger associé à un channel security
$securityLogger = new Logger("security");
// Associer le logger avec le handler $handler
$securityLogger->pushHandler($handler);

// Création d'un handler de type RotatingFile
$rotatingHandler = new RotatingFileHandler(__DIR__.'/logs/dev.log',5,Logger::DEBUG);
// Associer le handler au logger appLogger
$appLogger->pushHandler($rotatingHandler);

// Ecrire un message de log
$appLogger->info("Ceci est un message d'info !");
$appLogger->error("Ceci est un message d'erreur !");
$appLogger->critical("Ceci est un message critique");

$securityLogger->error("Message erreur sécurité");

// Ecrire un message d'info en utilisant le contexte
$appLogger->info("Message d'info utilisant le contexte",["ip" => "240.45.12.3","user" => "DUPOND"]);

// Associer un processor au logger appLogger
$appLogger->pushProcessor(function ($record) {
   $record['extra']['ip'] = "240.45.12.3";
   $record['extra']['user'] = "DUPOND";
   return $record;
});

$appLogger->info("Message utilisant extra");
$appLogger->error("Message utilisant extra");
$appLogger->critical("Message utilisant extra");
$appLogger->alert("Message utilisant extra");

// Création d'un formatter
$dateFormat = "d/m/Y H:i:s";
$formatLine = "[%datetime%] %channel% %level_name% %extra% %message% %context%";
$formatter = new LineFormatter($formatLine,$dateFormat);
// Associer le formatter à un handler
$rotatingHandler->setFormatter($formatter);

$appLogger->info("Message utilisant un formatter !");


