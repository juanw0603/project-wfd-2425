@if ($errors->any())
    <script>
        let message = '';
        @foreach ($errors->all() as $error)
            message += `{{ $error }}\n`;
        @endforeach

        Swal.fire({
            icon: 'error',
            title: 'Gagal Menyimpan',
            text: message,
        });
    </script>
@endif