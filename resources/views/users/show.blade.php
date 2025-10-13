
<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Detail User</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="p-4"><h1>Detail User</h1><a href="{{ route('users.index') }}" class="btn btn-secondary mb-3">Kembali</a>
<table class="table table-bordered"><tr><th>ID</th><td>{{ $user->id }}</td></tr><tr><th>Nama</th><td>{{ $user->name }}</td></tr><tr><th>Username</th><td>{{ $user->username }}</td></tr><tr><th>Email</th><td>{{ $user->email }}</td></tr><tr><th>Role</th><td>{{ $user->role->name ?? '-' }}</td></tr></table></body></html>
