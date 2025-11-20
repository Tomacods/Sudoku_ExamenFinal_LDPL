<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Clase BaseController
 *
 * BaseController proporciona un lugar conveniente para cargar componentes
 * y realizar funciones que son necesarias para todos tus controladores.
 * Extiende esta clase en cualquier nuevo controlador:
 *     class Home extends BaseController
 *
 * Por seguridad, asegúrate de declarar cualquier método nuevo como protegido o privado.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     *
     * Instancia del objeto Request principal.
     */
    protected $request;

    /**
     * Un array de helpers que se cargarán automáticamente al
     * instanciar la clase. Estos helpers estarán disponibles
     * para todos los demás controladores que extiendan BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Asegúrate de declarar las propiedades para cualquier obtención de propiedad que hayas inicializado.
     * La creación de propiedades dinámicas está obsoleta en PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // No editar esta línea
        parent::initController($request, $response, $logger);

        // Precarga aquí cualquier modelo, librería, etc.
        // E.g.: $this->session = \Config\Services::session();
    }
}
