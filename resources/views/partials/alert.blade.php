@if (session()->has('error'))
    <script>
        Swal.fire({
            heightAuto: false,
            icon: 'error',
            title: 'Oops...',
            text: '{{ session('error') }}',
            confirmButtonColor: "#3085d6",
        })
    </script>
@endif

@if (session()->has('success'))
    <script>
        Swal.fire({
            heightAuto: false,
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
            confirmButtonColor: "#3085d6",
        })
    </script>
@endif
