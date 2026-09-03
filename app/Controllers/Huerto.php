<?php

namespace App\Controllers;

use App\Models\CultivoModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\I18n\Time;

class Huerto extends BaseController
{
    protected CultivoModel $cultivoModel;

    public function __construct()
    {
        $this->cultivoModel = new CultivoModel();
    }

    public function index()
    {
        $cultivos = $this->cultivoModel
            ->orderBy('id', 'DESC')
            ->findAll();

        $hoy = Time::today('America/Argentina/Buenos_Aires');
        $requierenRiego = [];
        $listosParaCosechar = [];

        foreach ($cultivos as $cultivo) {
            if ($cultivo['estado'] === 'Cosechado') {
                continue;
            }

            $ultimoRiego = ! empty($cultivo['ultimo_riego'])
                ? Time::parse($cultivo['ultimo_riego'], 'America/Argentina/Buenos_Aires')
                : Time::parse($cultivo['fecha_siembra'], 'America/Argentina/Buenos_Aires');

            $proximoRiego = $ultimoRiego->addDays(
                (int) $cultivo['frecuencia_riego_dias']
            );

            if ($proximoRiego <= $hoy) {
                $requierenRiego[] = $cultivo;
            }

            $fechaCosecha = Time::parse(
                $cultivo['fecha_siembra'],
                'America/Argentina/Buenos_Aires'
            )->addDays((int) $cultivo['dias_cosecha_estimados']);

            if ($fechaCosecha <= $hoy) {
                $listosParaCosechar[] = $cultivo;
            }
        }

        return view('huerto/index', [
            'cultivos' => $cultivos,
            'requierenRiego' => $requierenRiego,
            'listosParaCosechar' => $listosParaCosechar,
        ]);
    }

    public function crear()
    {
        $reglas = [
            'nombre_planta' => 'required|max_length[100]',
            'variedad' => 'permit_empty|max_length[100]',
            'fecha_siembra' => 'required|valid_date[Y-m-d]',
            'dias_cosecha_estimados' => 'required|integer|greater_than[0]',
            'frecuencia_riego_dias' => 'required|integer|greater_than[0]',
        ];

        if (! $this->validateData($this->request->getPost(), $reglas)) {
            return redirect()->to('/')
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->cultivoModel->save([
            'nombre_planta' => $this->request->getPost('nombre_planta'),
            'variedad' => $this->request->getPost('variedad'),
            'fecha_siembra' => $this->request->getPost('fecha_siembra'),
            'dias_cosecha_estimados' => $this->request->getPost('dias_cosecha_estimados'),
            'frecuencia_riego_dias' => $this->request->getPost('frecuencia_riego_dias'),
            'ultimo_riego' => Time::now('America/Argentina/Buenos_Aires')
                ->toDateTimeString(),
            'estado' => 'En Crecimiento',
        ]);

        return redirect()->to('/')
            ->with('mensaje', 'Cultivo creado correctamente.');
    }

    public function registrarRiego(int $id)
    {
        if ($this->cultivoModel->find($id) === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        $this->cultivoModel->update($id, [
            'ultimo_riego' => Time::now('America/Argentina/Buenos_Aires')
                ->toDateTimeString(),
        ]);

        return redirect()->to('/')
            ->with('mensaje', 'Riego registrado correctamente.');
    }

    public function cambiarEstado(int $id)
    {
        if ($this->cultivoModel->find($id) === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        $estado = $this->request->getPost('estado');
        $estadosPermitidos = [
            'En Crecimiento',
            'Listo para Cosechar',
            'Cosechado',
        ];

        if (! in_array($estado, $estadosPermitidos, true)) {
            return redirect()->to('/')
                ->with('error', 'El estado seleccionado no es válido.');
        }

        $this->cultivoModel->update($id, ['estado' => $estado]);

        return redirect()->to('/')
            ->with('mensaje', 'Estado actualizado correctamente.');
    }

    public function eliminar(int $id)
    {
        if ($this->cultivoModel->find($id) === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        $this->cultivoModel->delete($id);

        return redirect()->to('/')
            ->with('mensaje', 'Cultivo eliminado correctamente.');
    }
}