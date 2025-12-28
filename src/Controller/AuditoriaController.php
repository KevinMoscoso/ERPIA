<?php

declare(strict_types=1);

namespace Erpia\Controller;

use Erpia\Core\Controller;
use Erpia\Core\Auth;
use Erpia\Model\Auditoria;
use Erpia\Model\Usuario;

class AuditoriaController extends Controller
{
    public function index(): void
    {
        $isAdmin = Auth::can('roles.gestionar', false) || Auth::can('usuarios.gestionar', false);
        if (!$isAdmin) {
            Auth::can('roles.gestionar');
        }

        $usuarioIdRaw = trim((string)($_GET['usuario_id'] ?? ''));
        $usuarioId = $usuarioIdRaw !== '' ? (int)$usuarioIdRaw : null;

        $desde = trim((string)($_GET['desde'] ?? ''));
        $hasta = trim((string)($_GET['hasta'] ?? ''));

        if ($desde === '') {
            $desde = date('Y-m-01');
        }
        if ($hasta === '') {
            $hasta = date('Y-m-d');
        }

        $errors = [];

        $d1 = \DateTime::createFromFormat('Y-m-d', $desde);
        $d2 = \DateTime::createFromFormat('Y-m-d', $hasta);

        if (!$d1 || $d1->format('Y-m-d') !== $desde) {
            $errors[] = 'Fecha "desde" inválida.';
        }
        if (!$d2 || $d2->format('Y-m-d') !== $hasta) {
            $errors[] = 'Fecha "hasta" inválida.';
        }

        $usuarios = Usuario::getAll(null);

        $rows = [];
        if (empty($errors)) {
            try {
                $rows = Auditoria::getByFiltros($usuarioId, $desde, $hasta);
            } catch (\Throwable $e) {
                $errors[] = 'No se pudo cargar la auditoría.';
                $rows = [];
            }
        }

        $this->render('auditoria/index', [
            'usuarios' => $usuarios,
            'rows' => $rows,
            'usuario_id' => $usuarioId,
            'desde' => $desde,
            'hasta' => $hasta,
            'errors' => $errors,
        ]);
    }
}