$source = "C:\xampp\htdocs\sistem-magang"
$dest = "C:\xampp\htdocs\sistem-magang-clean"
$zipPath = "C:\xampp\htdocs\sistem-magang-clean.zip"

Write-Host "Copying files to temporary directory..."
robocopy $source $dest /MIR /XD .git vendor node_modules .phpunit.cache "storage\framework\views" "storage\framework\cache" "storage\logs" /XF .env .DS_Store
# robocopy returns 1 if files were copied successfully, 0 if no change, 2 or higher if errors. We can ignore the exit code as long as it's < 8.

Write-Host "Compressing to ZIP..."
if (Test-Path $zipPath) {
    Remove-Item $zipPath -Force
}
Compress-Archive -Path "$dest\*" -DestinationPath $zipPath -Force

Write-Host "Cleaning up temporary directory..."
Remove-Item -Recurse -Force $dest

Write-Host "Done! File saved to $zipPath"
