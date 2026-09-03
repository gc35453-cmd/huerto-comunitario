<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Huerto Comunitario
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h1 class="mb-4">Sistema de Gesti&oacute;n de Huerto Comunitario</h1>

<?php if (session()->getFlashdata('mensaje')): ?>
    <div class="alert alert-success">
        <?= esc(session()->getFlashdata('mensaje')) ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= esc(session()->getFlashdata('error')) ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card mb-4">
    <div class="card-header">
        Nuevo cultivo
    </div>

    <div class="card-body">
        <form action="<?= site_url('cultivos') ?>" method="post">
            <?= csrf_field() ?>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nombre de la planta</label>
                    <input type="text" name="nombre_planta" class="form-control" value="<?= old('nombre_planta') ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Variedad</label>
                    <input type="text" name="variedad" class="form-control" value="<?= old('variedad') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Fecha de siembra</label>
                    <input type="date" name="fecha_siembra" class="form-control" value="<?= old('fecha_siembra') ?>" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">D&iacute;as estimados para cosecha</label>
                    <input type="number" name="dias_cosecha_estimados" class="form-control" value="<?= old('dias_cosecha_estimados') ?>" min="1" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Frecuencia de riego en d&iacute;as</label>
                    <input type="number" name="frecuencia_riego_dias" class="form-control" value="<?= old('frecuencia_riego_dias') ?>" min="1" required>
                </div>
            </div>

            <button class="btn btn-primary mt-3" type="submit">
                Agregar cultivo
            </button>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card border-warning h-100">
            <div class="card-header bg-warning text-dark">
                Requiere riego hoy
            </div>

            <div class="card-body">
                <?php if (empty($requierenRiego)): ?>
                    <p class="mb-0">No hay cultivos pendientes de riego.</p>
                <?php else: ?>
                    <ul class="mb-0">
                        <?php foreach ($requierenRiego as $cultivo): ?>
                            <li>
                                <span class="badge text-bg-warning">
                                    <?= esc($cultivo['nombre_planta']) ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-success h-100">
            <div class="card-header bg-success text-white">
                Listo para cosecha
            </div>

            <div class="card-body">
                <?php if (empty($listosParaCosechar)): ?>
                    <p class="mb-0">No hay cultivos listos para cosechar.</p>
                <?php else: ?>
                    <ul class="mb-0">
                        <?php foreach ($listosParaCosechar as $cultivo): ?>
                            <li>
                                <span class="badge text-bg-success">
                                    <?= esc($cultivo['nombre_planta']) ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        Cultivos registrados
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead>
                    <tr>
                        <th>Planta</th>
                        <th>Variedad</th>
                        <th>Fecha siembra</th>
                        <th>&Uacute;ltimo riego</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($cultivos as $cultivo): ?>
                        <tr>
                            <td><?= esc($cultivo['nombre_planta']) ?></td>
                            <td><?= esc($cultivo['variedad']) ?></td>
                            <td><?= esc($cultivo['fecha_siembra']) ?></td>
                            <td><?= esc($cultivo['ultimo_riego']) ?></td>
                            <td>
                                <?php if ($cultivo['estado'] === 'Cosechado'): ?>
                                    <span class="badge text-bg-secondary">Cosechado</span>
                                <?php elseif ($cultivo['estado'] === 'Listo para Cosechar'): ?>
                                    <span class="badge text-bg-success">Listo para Cosechar</span>
                                <?php else: ?>
                                    <span class="badge text-bg-primary">En Crecimiento</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form action="<?= site_url('cultivos/' . $cultivo['id'] . '/riego') ?>" method="post" class="d-inline">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-sm btn-warning" type="submit">
                                        Registrar riego
                                    </button>
                                </form>

                                <form action="<?= site_url('cultivos/' . $cultivo['id'] . '/estado') ?>" method="post" class="d-inline">
                                    <?= csrf_field() ?>
                                    <select name="estado" class="form-select form-select-sm d-inline w-auto">
                                        <option value="En Crecimiento" <?= $cultivo['estado'] === 'En Crecimiento' ? 'selected' : '' ?>>En Crecimiento</option>
                                        <option value="Listo para Cosechar" <?= $cultivo['estado'] === 'Listo para Cosechar' ? 'selected' : '' ?>>Listo para Cosechar</option>
                                        <option value="Cosechado" <?= $cultivo['estado'] === 'Cosechado' ? 'selected' : '' ?>>Cosechado</option>
                                    </select>

                                    <button class="btn btn-sm btn-secondary" type="submit">
                                        Cambiar
                                    </button>
                                </form>

                                <form action="<?= site_url('cultivos/' . $cultivo['id'] . '/eliminar') ?>" method="post" class="d-inline">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-sm btn-danger" type="submit">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (empty($cultivos)): ?>
                        <tr>
                            <td colspan="6" class="text-center">
                                No hay cultivos registrados.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
