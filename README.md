# Generate PDF from URL

### Requirements

- WKHTMLTOPDF

### Usage

```php
use Metawesome\PdfFromUrl\Pdf;

$pdf = new Pdf();
$pdf->fromUrl('https://www.google.com');
$pdf->saveAs(storage_path('google.pdf'));
```