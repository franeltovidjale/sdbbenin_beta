<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>
    <title>Gestion des codes d'invitation</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        @include('apk.components.topbar')

        <!-- /.navbar -->
        <!-- Main Sidebar Container -->
        @include('apk.components.navbar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Codes d'invitation</h1>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <!-- Main content -->
            <section class="content">
                <form id="invitationForm" action="{{ route('invitation-codes.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-8 m-auto">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Générer un nouveau code d'invitation</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="role">Rôle</label>
                                        <select name="role" id="role" class="form-control">
                                            <option value="admin">Administrateur</option>
                                            {{-- <option value="user">Utilisateur standard</option> --}}
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="expiration_days">Durée de validité (jours)</label>
                                        <input type="number" name="expiration_days" id="expiration_days" min="1" max="365" value="30" class="form-control">
                                    </div>
                                    <br>
                                    <button type="submit" class="btn btn-primary" id="submitButton">Générer un code</button>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                </form>

                <br>

                <section class="content">
                    <div class="row">
                        <div class="col-md-8 m-auto">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Liste des codes d'invitation</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th style="width: 10px">#</th>
                                                <th>Code</th>
                                                <th>Rôle</th>
                                                <th>Statut</th>
                                                <th>Expire le</th>
                                                <th style="width: 40px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($invitationCodes as $code)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>
                                                    <span class="code-text">{{ $code->code }}</span>
                                                    <button type="button" class="btn btn-sm btn-info copy-code" data-code="{{ $code->code }}">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </td>
                                                <td>{{ $code->role === 'admin' ? 'Administrateur' : 'Utilisateur' }}</td>
                                                <td>
                                                    @if($code->is_used)
                                                        <span class="badge bg-danger">Utilisé</span>
                                                    @elseif(now()->isAfter($code->expires_at))
                                                        <span class="badge bg-warning">Expiré</span>
                                                    @else
                                                        <span class="badge bg-success">Valide</span>
                                                    @endif
                                                </td>
                                                <td>{{ $code->expires_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm delete-code" 
                                                            data-id="{{ $code->id }}"
                                                            data-url="{{ route('invitation-codes.destroy', $code->id) }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                </section>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        @include('apk.components.footer')
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('invitationForm');

            // Gérer la soumission du formulaire
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                try {
                    const formData = new FormData(this);
                    const response = await fetch(this.action, {
                        method: this.method,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Afficher le code généré
                        Swal.fire({
                            icon: 'success',
                            title: 'Code généré avec succès!',
                            html: `
                                <p>Voici le code d'invitation: <br>
                                <strong style="font-size: 18px; color: #3085d6;">${data.code.code}</strong></p>
                                <p>Ce code expire le: ${new Date(data.code.expires_at).toLocaleDateString()}</p>
                            `,
                            confirmButtonText: 'Copier le code',
                            showCancelButton: true,
                            cancelButtonText: 'Fermer'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                navigator.clipboard.writeText(data.code.code).then(() => {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Code copié!',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                });
                            }
                            window.location.reload();
                        });
                    } else {
                        throw new Error(data.message || 'Une erreur est survenue');
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: error.message
                    });
                }
            });

            // Gérer les boutons de suppression
            document.querySelectorAll('.delete-code').forEach(button => {
                button.addEventListener('click', async function(e) {
                    e.preventDefault();

                    const result = await Swal.fire({
                        title: 'Êtes-vous sûr?',
                        text: "Cette action est irréversible!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Oui, supprimer!',
                        cancelButtonText: 'Annuler'
                    });

                    if (result.isConfirmed) {
                        try {
                            const url = this.getAttribute('data-url');
                            const response = await fetch(url, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                }
                            });

                            const data = await response.json();

                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Supprimé!',
                                    text: data.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                throw new Error(data.message);
                            }
                        } catch (error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur',
                                text: error.message || 'Une erreur est survenue'
                            });
                        }
                    }
                });
            });

            // Gérer les boutons de copie
            document.querySelectorAll('.copy-code').forEach(button => {
                button.addEventListener('click', function() {
                    const code = this.getAttribute('data-code');
                    navigator.clipboard.writeText(code).then(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Code copié!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    });
                });
            });
        });
    </script>
</body>
</html>