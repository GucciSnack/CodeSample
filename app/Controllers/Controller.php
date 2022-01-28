<?php
declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Twig\Extra\Intl\IntlExtension;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class Controller
{
    /**
     * Returns a twig file under a Response Interface.
     *
     * @param string $twigFile
     * @param array $twigFileVariables
     * @return ResponseInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    protected function render(string $twigFile, array $twigFileVariables): ResponseInterface
    {
        $twigLoader = new FilesystemLoader(dirname(__DIR__) . '/../resources/views');
        $twig = new Environment($twigLoader);
        $twig->addExtension(new IntlExtension());

        $response = new \Laminas\Diactoros\Response;
        $response->getBody()->write($twig->render($twigFile, $twigFileVariables));

        return $response;
    }
}