@extends('layouts.app')
@section('title'){{ $subModule->title }}@endsection
@section('content')
<div class="container-fluid">
    <h1>(Siswa) Tampilan Forum: {{ $subModule->title }}</h1>
    <p>Di sini akan tampil ruang debat Pro/Kontra.</p>
    <a href="{{ route('student.class.show', $kelas->id) }}">Kembali ke Kurikulum</a>
</div>
@endsection
