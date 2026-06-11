Add-Type -AssemblyName System.Drawing
$srcPath = "C:\xampp\htdocs\sistem-magang\public\template\Sertifikat_Botania_empty.jpg"
$destPath = "C:\xampp\htdocs\sistem-magang\public\template\sertifikat_botania_small.jpg"
[System.Drawing.Bitmap]$img = New-Object System.Drawing.Bitmap($srcPath)
[System.Drawing.Bitmap]$newImg = New-Object System.Drawing.Bitmap($img, 1240, 874)
$newImg.Save($destPath, [System.Drawing.Imaging.ImageFormat]::Jpeg)
$img.Dispose()
$newImg.Dispose()
Write-Host "Success"
