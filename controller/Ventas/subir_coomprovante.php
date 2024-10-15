<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Configurar la carpeta de subida
    $uploadDir = '../view/ventas/comprobantes_uploads';
    $uploadFile = $uploadDir . basename($_FILES['comprovante']['name']);

    // Verificar si el archivo es una imagen válida
    $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
    $check = getimagesize($_FILES['comprovante']['tmp_name']);
    if ($check === false) {
        echo json_encode(['status' => 'error', 'message' => 'El archivo no es una imagen válida.']);
        exit();
    }

    // Verificar el tamaño del archivo (en bytes)
    $maxSize = 1024 * 1024; // 1MB
    if ($_FILES['comprovante']['size'] > $maxSize) {
        // Optimizar la imagen si es demasiado grande
        $source_image = imagecreatefromjpeg($_FILES['comprovante']['tmp_name']);
        $imageWidth = imagesx($source_image);
        $imageHeight = imagesy($source_image);

        // Ajustar el tamaño de la imagen para reducirla
        $newWidth = $imageWidth * 0.5; // Reducir a la mitad (puedes ajustar el valor)
        $newHeight = $imageHeight * 0.5;
        $compressedImage = imagecreatetruecolor($newWidth, $newHeight);

        // Copiar y redimensionar
        imagecopyresampled($compressedImage, $source_image, 0, 0, 0, 0, $newWidth, $newHeight, $imageWidth, $imageHeight);

        // Guardar la imagen optimizada
        $optimizedFile = $uploadDir . 'optimized_' . basename($_FILES['comprovante']['name']);
        imagejpeg($compressedImage, $optimizedFile, 75); // 75 es la calidad de compresión
        imagedestroy($source_image);
        imagedestroy($compressedImage);

        // Establecer la ruta del archivo optimizado
        $filePath = $optimizedFile;
    } else {
        // Si la imagen ya es lo suficientemente pequeña, simplemente se mueve a la carpeta de subida
        if (move_uploaded_file($_FILES['comprovante']['tmp_name'], $uploadFile)) {
            $filePath = $uploadFile;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al mover el archivo.']);
            exit();
        }
    }

    // Aquí puedes agregar la lógica para guardar la ruta del archivo en la base de datos, si es necesario:
    // Por ejemplo: $this->base_datos->update("ventas", ["comprovante_venta" => $filePath], ["idventa" => $idVenta]);

    // Devolver respuesta exitosa en formato JSON
    echo json_encode(['status' => 'success', 'message' => 'Comprobante subido correctamente', 'filePath' => $filePath]);
} else {
    // Si no es un método POST, devolver error
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
}
?>
