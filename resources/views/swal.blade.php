
@if (session('success'))
<script>
    Swal.fire({
        title: '{{__("admin.swal.success.title")}}',
        text: '{{ session('success') }}',
        icon: 'success'
    });
</script>
@endif
@if (session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: '{{session('error')}}',
    })
</script>
@endif
@if ($errors->any())
@php
    $message='';
    foreach ($errors->all() as $error){
        $message .= $error;
    }
@endphp
<script>
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: '{{$message}}',
    })
</script>
@endif
