<?php

require_once '../vendor/autoload.php';
$loader = new Twig_Loader_Filesystem('../templates');
$twig = new Twig_Environment($loader);
$twig->addExtension(new SensioLabs\Twig\CoreExtension());
echo $twig->render('index.html.twig', array());

