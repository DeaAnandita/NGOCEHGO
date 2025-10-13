
<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Edit User</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="p-4">
<h1>Edit User</h1><a href="{{ route('users.index') }}" class="btn btn-secondary mb-3">Kembali</a>
<form action="{{ route('users.update',$user->id) }}" method="POST">@csrf @method('PUT')
<div class="mb-3"><label>Nama</label><input name="name" class="form-control" value="{{ $user->name }}" required></div>
<div class="mb-3"><label>Username</label><input name="username" class="form-control" value="{{ $user->username }}" required></div>
<div class="mb-3"><label>Email</label><input name="email" class="form-control" value="{{ $user->email }}"></div>
<div class="mb-3"><label>Password (kosongkan jika tidak diubah)</label><input type="password" name="password" class="form-control"></div>
<div class="mb-3"><label>Role</label><select name="role_id" class="form-control">@foreach($roles as $r)<option value="{{ $r->id }}" {{ $user->role_id==$r->id?'selected':'' }}>{{ $r->name }}</option>@endforeach</select></div>
<button class="btn btn-primary">Update</button></form></body></html>
