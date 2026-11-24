<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Masuk – Kasir Mini</title>
<link rel="stylesheet" href="{{ asset('css/pos.css') }}">
</head>
<body>
<form class="card login" method="POST" action="{{ url('/login') }}">
  @csrf
  <h1>🛒 Mini Kasir</h1>
  <div class="frm">
    <label>Email</label>
    <input type="email" name="email" value="{{ old('email') }}" required autofocus>
    <label>Kata sandi</label>
    <input type="password" name="password" required>
    <label style="display:flex;gap:6px;align-items:center"><input type="checkbox" name="remember" value="1" style="width:auto"> Ingat saya</label>
    @error('email')<div class="err">{{ $message }}</div>@enderror
    <button class="btn" type="submit">Masuk</button>
  </div>
</form>
</body>
</html>
