<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product Stock</title>
</head>
<body>
    <h1>Edit Product Stock</h1>
    <form action="{{ route("update.products", $product->id)  }}" method="POST">
        @csrf
        @method('PUT')

        <label for="id_from_request">Id To sell:</label>
        <input type="number" id="id_from_request" name="id_from_request" value="{{ $product->id }}" required>

        <button type="submit">Update Stock</button>
    </form>
</body>
</html>
