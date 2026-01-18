param(
  [string]$ProjectRoot = "C:\xampp\htdocs\mardini",
  [string]$FreezeRoot  = "D:\Gamarky_Freezes"
)

$stamp = (Get-Date).ToString("yyyy-MM-dd_HH-mm")
$freezeDir   = Join-Path $FreezeRoot ("freeze_" + $stamp)
$manifestDir = Join-Path $freezeDir "_manifest"
New-Item -ItemType Directory -Path $freezeDir, $manifestDir -Force | Out-Null

$items = @(
  "app\Http\Livewire",
  "resources\views",
  "routes",
  "public\build",
  "vite.config.js",
  "package.json","package-lock.json",
  "composer.json","composer.lock",
  ".env"
)
foreach ($item in $items) {
  $src = Join-Path $ProjectRoot $item
  if (Test-Path $src) {
    New-Item -ItemType Directory -Path (Split-Path (Join-Path $freezeDir $item)) -Force | Out-Null
    Copy-Item $src -Destination (Join-Path $freezeDir $item) -Recurse -Force
  }
}

$pathsForHash = @("resources\views","app\Http\Livewire","routes","public\build")
$hashList = @()
foreach ($rel in $pathsForHash) {
  $p = Join-Path $freezeDir $rel
  if (Test-Path $p) {
    Get-ChildItem -Recurse $p -File | ForEach-Object {
      $h = Get-FileHash $_.FullName -Algorithm SHA256
      $hashList += [pscustomobject]@{
        RelativePath = $_.FullName.Replace($freezeDir+"\","")
        Sha256       = $h.Hash
        Size         = $_.Length
        LastWrite    = $_.LastWriteTime
      }
    }
  }
}
$hashCsv = Join-Path $manifestDir "manifest-$stamp.csv"
$hashList | Sort-Object RelativePath | Export-Csv -NoTypeInformation -Encoding UTF8 $hashCsv

Add-Type -AssemblyName System.IO.Compression.FileSystem
$zipPath = Join-Path $FreezeRoot ("freeze_" + $stamp + ".zip")
[System.IO.Compression.ZipFile]::CreateFromDirectory($freezeDir, $zipPath)

Write-Host "Freeze saved to: $zipPath"
Write-Host "Manifest: $hashCsv"
