<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vender</title>
</head>
<body>
    
    <form action="{{ route("products.update")  }}" method="POST">
        @csrf
        @method('PUT')
        
        
        <button type="submit">Update Stock</button>
    </form>
</body>
</html>
