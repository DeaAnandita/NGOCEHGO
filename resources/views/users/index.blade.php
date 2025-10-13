
<!DOCTYPE html>
<html lang="id">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Users</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="p-4">
<h1>Daftar Users</h1>
<a href="{{ route('users.create') }}" class="btn btn-primary mb-3">+ Tambah User</a>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<table class="table table-bordered"><thead><tr><th>ID</th><th>Nama</th><th>Username</th><th>Email</th><th>Role</th><th>Aksi</th></tr></thead><tbody>
@foreach($users as $u)
<tr><td>{{ $u->id }}</td><td>{{ $u->name }}</td><td>{{ $u->username }}</td><td>{{ $u->email }}</td><td>{{ $u->role->name ?? '-' }}</td>
<td>
<a class="btn btn-info btn-sm" href="{{ route('users.show',$u->id) }}">Detail</a>
<a class="btn btn-warning btn-sm" href="{{ route('users.edit',$u->id) }}">Edit</a>
<form action="{{ route('users.destroy',$u->id) }}" method="POST" style="display:inline-block">@csrf @method('DELETE')<button class="btn btn-danger btn-sm" onclick="return confirm('Hapus?')">Hapus</button></form>
</td></tr>
@endforeach
</tbody></table>
</body></html>
