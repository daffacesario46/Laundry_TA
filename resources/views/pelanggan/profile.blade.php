<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <title>Profile - Laundry App</title>
    <link rel="icon" href="{{ asset('admins') }}/imgs/theme/washwes.png" />
    <link href="{{ asset('admins') }}/css/main.css?v=1.1" rel="stylesheet" type="text/css" />
</head>
<body>
    <h1>Profile Saya</h1>
    
    <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div>
            <label>Nama</label>
            <input type="text" name="nama" value="{{ $user->nama }}" required>
        </div>
        
        <div>
            <label>Email</label>
            <input type="email" name="email" value="{{ $user->email }}" required>
        </div>
        
        <div>
            <label>No Telepon</label>
            <input type="text" name="no_telp" value="{{ $user->no_telp }}">
        </div>
        
        <div>
            <label>WhatsApp</label>
            <input type="text" name="no_wa" value="{{ $user->no_wa }}">
        </div>
        
        <div>
            <label>Alamat</label>
            <textarea name="alamat">{{ $user->alamat }}</textarea>
        </div>
        
        <div>
            <label>Foto Profile</label>
            <input type="file" name="img" accept="image/*">
        </div>
        
        <button type="submit">Update Profile</button>
    </form>
    
    <a href="{{ route('user.index') }}">Kembali</a>
</body>
</html>