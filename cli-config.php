<?php

require_once 'bootstrap.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Helper\TableHelper;

$dm = include('doctrine.php');

return $helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    new FormatterHelper(),
    new DialogHelper(),
    new ProgressHelper(),
    new TableHelper(),
    new QuestionHelper()
));
