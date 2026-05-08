<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">



        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>
<body class="font-sans antialiased text-black bg-[#F9FAFB]">
        <div class="min-h-screen flex h-screen overflow-hidden">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content -->
            <div class="flex-1 flex flex-col relative w-full overflow-hidden">
                <!-- Topbar -->
                @include('layouts.navigation')

                <!-- Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-[#F9FAFB]">
                    <div class="p-6 sm:p-10 max-w-7xl mx-auto">
                        @isset($header)
                            <div class="mb-8">
                                {{ $header }}
                            </div>
                        @endisset
                        
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <script>
            function confirmDelete(id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esta acción!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#C12026',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('delete-form-' + id);
                        if (form) form.submit();
                    }
                });
            }

            function confirmUpdate(id) {
                Swal.fire({
                    title: '¿Confirmar cambios?',
                    text: "¿Deseas guardar los cambios realizados?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3B82F6',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Sí, actualizar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('edit-form-' + id);
                        if (form) form.submit();
                    }
                });
            }

            // Global session message handlers
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Operación Exitosa!',
                    text: "{{ session('success') }}",
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end',
                    background: '#fff',
                    color: '#000',
                    iconColor: '#22c55e'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error detectado',
                    text: "{{ session('error') }}",
                    timer: 5000,
                    timerProgressBar: true,
                    showConfirmButton: true,
                    confirmButtonColor: '#C12026',
                    background: '#fff',
                    color: '#000'
                });
            @endif
        </script>

        @stack('scripts')
    </body>
</html>

