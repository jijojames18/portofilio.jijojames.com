<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';
require 'classes/sendemail.php';
require 'classes/videos.php';
require 'classes/blog.php';
require 'classes/gallery.php';

$app = new \Slim\App;
$app->post('/v1/contact', function (Request $request, Response $response, array $args) {
    $parsedBody = $request->getParsedBody();
    $name = isset($parsedBody['name']) ? $parsedBody['name'] : 'Name';
    $email = isset($parsedBody['email']) ? $parsedBody['email'] : 'Email';
    $message = isset($parsedBody['message']) ? $parsedBody['message'] : 'Message';
    $sendEmail = new SendEmail(($name), escapeRequestData($email), escapeRequestData($message));
    $sendEmail->send();
    return $response;
});

$app->get('/v1/videos', function (Request $request, Response $response, array $args) {
    $videos = new Videos();
    return $this->response->withJson($videos->fetchAll());
});

$app->get('/v1/blog', function (Request $request, Response $response, array $args) {
    $blog = new Blog();
    return $this->response->withJson($blog->fetchAll());
});

$app->get('/v1/gallery', function (Request $request, Response $response, array $args) {
    $gallery = new Gallery();
    return $this->response->withJson($gallery->fetchAll());
});

$app->run();

function escapeRequestData ($data) {
    $data = stripslashes($data);
    $data = htmlentities($data);
    $data = strip_tags($data);
    return $data;
}