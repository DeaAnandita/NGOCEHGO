
<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Tambah User</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="p-4">
<h1>Tambah User</h1><a href="{{ route('users.index') }}" class="btn btn-secondary mb-3">Kembali</a>
@if($errors->any())<div class="alert alert-danger"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<form action="{{ route('users.store') }}" method="POST">@csrf
<div class="mb-3"><label>Nama</label><input name="name" class="form-control" value="{{ old('name') }}" required></div>
<div class="mb-3"><label>Username</label><input name="username" class="form-control" value="{{ old('username') }}" required></div>
<div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" value="{{ old('email') }}"></div>
<div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
<div class="mb-3"><label>Role</label><select name="role_id" class="form-control">@foreach($roles as $r)<option value="{{ $r->id }}">{{ $r->name }}</option>@endforeach</select></div>
<button class="btn btn-success">Simpan</button></form></body></html>
