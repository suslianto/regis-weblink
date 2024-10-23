<?php
function CreateThumbnail($pic, $thumb, $thumbwidth, $quality = 90)
{
    // Dapatkan informasi tipe gambar (MIME type)
    $info = @getimagesize($pic);
    $mime = $info['mime'];

    // Tentukan fungsi untuk membuat gambar berdasarkan tipe MIME
    switch ($mime) {
        case 'image/jpeg':
            $im1 = @ImageCreateFromJPEG($pic);
            break;
        case 'image/png':
            $im1 = @ImageCreateFromPNG($pic);
            break;
        default:
            die("Error: Unsupported image format.");
    }

    // Memperhitungkan orientasi hanya untuk gambar JPEG
    if ($mime === 'image/jpeg' && function_exists('exif_read_data')) {
        $exif = @exif_read_data($pic);
        if (!empty($exif['Orientation'])) {
            switch ($exif['Orientation']) {
                case 8:
                    $im1 = imagerotate($im1, 90, 0);
                    break;
                case 3:
                    $im1 = imagerotate($im1, 180, 0);
                    break;
                case 6:
                    $im1 = imagerotate($im1, -90, 0);
                    break;
            }
        }
    }

    // Dapatkan ukuran gambar asli
    $w2 = ImageSx($im1);
    $h2 = ImageSy($im1);
    $w1 = ($thumbwidth <= $w2) ? $thumbwidth : $w2;
    $h1 = floor($h2 * ($w1 / $w2));
    
    // Buat gambar thumbnail
    $im2 = imagecreatetruecolor($w1, $h1);
    imagecopyresampled($im2, $im1, 0, 0, 0, 0, $w1, $h1, $w2, $h2);

    // Simpan gambar thumbnail sesuai format gambar
    switch ($mime) {
        case 'image/jpeg':
            ImageJPEG($im2, $thumb, $quality);
            break;
        case 'image/png':
            ImagePNG($im2, $thumb); // PNG tidak mendukung tingkat kualitas
            break;
    }

    // Bersihkan memori
    ImageDestroy($im1);
    ImageDestroy($im2);
}
?>
