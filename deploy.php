<?php
$hostname = 'kopkarlia.com';
$dataFolder = 'data/';
$templateFolder = 'templates/';

echo "Memeriksa folder data: $dataFolder\n";

// Array untuk menyimpan URL untuk sitemap
$sitemapUrls = [];

// Fungsi untuk membaca template PHP dari folder templates
function loadTemplates($templateFolder) {
    $templates = [];
    $files = glob($templateFolder . '*.php'); // Ganti ekstensi ke .php
    foreach ($files as $file) {
        $templateName = basename($file, '.php'); // Gunakan .php template
        $templates[$templateName] = file_get_contents($file);
    }
    return $templates;
}

// Memuat template PHP dari folder templates
$htmlTemplates = loadTemplates($templateFolder);

// Membaca file data.txt untuk mendapatkan daftar keyword
$filePath = 'data.txt';
$keywords = file($filePath, FILE_IGNORE_NEW_LINES);

// Array untuk menyimpan tautan internal
$internalLinksArray = [];

// Fungsi untuk mengganti spasi dengan tanda hubung dan menghapus karakter yang tidak diinginkan
function sanitizeName($name) {
    return preg_replace('/[^a-z0-9-]+/', '-', strtolower(trim($name)));
}

// Fungsi sederhana untuk mendeteksi user-agent (untuk cloaking)
function isBot() {
    $bots = ['Googlebot', 'Bingbot', 'Slurp', 'DuckDuckBot', 'Baiduspider'];
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    
    foreach ($bots as $bot) {
        if (strpos($userAgent, $bot) !== false) {
            return true;
        }
    }
    return false;
}

// Fungsi untuk memeriksa IP pengunjung (dapat ditambah dengan range IP tertentu)
function isBotIP() {
    $botIps = ['66.249.', '207.46.', '66.220.'];
    $userIP = $_SERVER['REMOTE_ADDR'];

    foreach ($botIps as $botIp) {
        if (strpos($userIP, $botIp) === 0) {
            return true;
        }
    }
    return false;
}

// Fungsi untuk memuat gambar acak dari daftar gambar dengan hostname
function getRandomImage($imageSources, $hostname) {
    $randomIndex = array_rand($imageSources);
    return "https://$hostname/" . $imageSources[$randomIndex];
}

// Daftar gambar tanpa hostname
$imageSources = [
    'slot1.png',
    'slot2.png',
    'slot3.png',
    'slot4.png',
    'slot5.png'
];

// Loop pertama untuk mengumpulkan semua URL yang akan dibuat
foreach ($keywords as $keyword) {
    $keyword = sanitizeName(trim($keyword));
    echo "Memproses kata kunci: $keyword\n";

    // Membaca konten dari file yang sesuai dengan keyword
    $contentFile = $dataFolder . $keyword . '.txt';

    if (file_exists($contentFile)) {
        $content = file_get_contents($contentFile);
        echo "Membaca file konten: $contentFile\n";

        // Memisahkan konten menjadi judul dan deskripsi
        $sections = explode("\n\n", $content);

        foreach ($sections as $index => $section) {
            preg_match('/^Title:\s*(.+)$/m', $section, $titleMatches);
            preg_match('/^Description:\s*(.+)$/m', $section, $descMatches);

            if ($titleMatches && $descMatches) {
                $title = trim($titleMatches[1]);
                $description = trim($descMatches[1]);

                // Format judul tanpa tanda '-'
                $formattedTitle = ucwords(strtolower(str_replace('-', ' ', $title)));

                // Nama folder dan path
                $folderName = $keyword;
                if ($index > 0) {
                    $folderName .= '-' . ($index + 1);
                }

                // Tambahkan URL ke array internalLinksArray untuk digunakan di tautan internal
                $internalLinksArray[$keyword][] = [
                    'folderName' => $folderName,
                    'formattedTitle' => $formattedTitle,
                    'description' => $description
                ];
            }
        }
    }
}

/// Fungsi untuk membuat paragraf secara acak dari title dan description dengan anchor berupa nama folder
function generateRandomParagraph($linksArray) {
    $selectedLinks = [];
    
    // Pilih tautan dari keyword yang berbeda
    $usedKeywords = [];
    
    foreach ($linksArray as $link) {
        if (!in_array($link['folderName'], $usedKeywords)) {
            $selectedLinks[] = $link;
            $usedKeywords[] = $link['folderName'];
        }
        
        // Hentikan jika sudah mendapatkan 3 tautan
        if (count($selectedLinks) >= 3) {
            break;
        }
    }
    
    // Jika kurang dari 3 tautan unik, tambahkan tautan dari keyword yang sama jika perlu
    if (count($selectedLinks) < 3) {
        foreach ($linksArray as $link) {
            if (count($selectedLinks) >= 3) {
                break;
            }
            
            if (!in_array($link['folderName'], $usedKeywords)) {
                $selectedLinks[] = $link;
                $usedKeywords[] = $link['folderName'];
            }
        }
    }
    
    // Acak urutan tautan
    shuffle($selectedLinks);

    // Buat paragraf dengan tautan acak
    $paragraphs = [];
    foreach ($selectedLinks as $link) {
        // Menggunakan folderName sebagai teks link
        $paragraphs[] = '<p><a href="/' . $link['folderName'] . '/">' . str_replace('-', ' ', $link['folderName']) . '</a> ' . $link['formattedTitle'] . '  ' . $link['description'] . '.</p>';
    }

    return implode(' ', $paragraphs);
}



// Loop kedua untuk membuat file PHP dengan tautan internal dan cloaking
foreach ($keywords as $keyword) {
    $keyword = sanitizeName(trim($keyword));
    echo "Memproses kata kunci: $keyword\n";

    // Membaca konten dari file yang sesuai dengan keyword
    $contentFile = $dataFolder . $keyword . '.txt';

    if (file_exists($contentFile)) {
        $content = file_get_contents($contentFile);
        echo "Membaca file konten: $contentFile\n";

        // Memisahkan konten menjadi judul dan deskripsi
        $sections = explode("\n\n", $content);

        // Menghasilkan landing page untuk setiap bagian
        foreach ($sections as $index => $section) {
            preg_match('/^Title:\s*(.+)$/m', $section, $titleMatches);
            preg_match('/^Description:\s*(.+)$/m', $section, $descMatches);

            if ($titleMatches && $descMatches) {
                $title = trim($titleMatches[1]);
                $description = trim($descMatches[1]);

                // Format judul tanpa tanda '-'
                $formattedTitle = ucwords(strtolower(str_replace('-', ' ', $title)));

                // Nama folder dan path
                $folderName = $keyword;
                if ($index > 0) {
                    $folderName .= '-' . ($index + 1);
                }
                $folderPath = './' . $folderName . '/';

                // Buat folder jika belum ada
                if (!file_exists($folderPath)) {
                    mkdir($folderPath, 0777, true);
                    echo "Folder dibuat: $folderPath\n";
                } else {
                    echo "Folder sudah ada: $folderPath\n";
                }

                // Placeholder untuk paragraf acak
                $randomParagraph = generateRandomParagraph($internalLinksArray[$keyword]);

                // Pilih gambar acak dengan hostname
                $randomImage = getRandomImage($imageSources, $hostname);

                // Pilih template berdasarkan indeks
                $templateKeys = array_keys($htmlTemplates);
                $templateKey = $templateKeys[$index % count($templateKeys)];
                $template = $htmlTemplates[$templateKey];

                // Hilangkan tanda hubung dari brand
                $brand = str_replace('-', ' ', $keyword);

                // Ganti placeholder dengan nilai aktual
                $phpContent = str_replace(['{{brand}}', '{{title}}', '{{description}}', '{{folderName}}', '{{internalLinks}}', '{{image}}', '{{canonical}}'], [$brand, $formattedTitle, $description, $folderName, $randomParagraph, $randomImage, "https://$hostname/$folderName/"], 
                $template);

                // Ganti {$hostname} juga
                $phpContent = str_replace('{$hostname}', $hostname, $phpContent);
                
                // Simpan konten PHP ke file
                $fileName = 'index.php';
                file_put_contents($folderPath . $fileName, $phpContent);
                echo "Dihasilkan: " . $folderPath . $fileName . PHP_EOL;

                // Menyusun URL lengkap untuk sitemap
                $protocol = "https";
                $baseUrl = "$protocol://$hostname"; // Menggunakan $hostname dari konfigurasi

                // Buat path relatif dari current URL tanpa 'index.php'
                $sitemapUrl = $baseUrl . '/' . trim($folderName, '/') . '/';
                // Menghapus duplikat '/' di URL
                $sitemapUrl = preg_replace('#/+#', '/', $sitemapUrl);

                // Memastikan URL memiliki dua garis miring setelah protokol
                $sitemapUrl = str_replace('https:/', 'https://', $sitemapUrl);

                $sitemapUrls[] = $sitemapUrl;
            }
        }
    }
}

// Membuat sitemap.xml dan sitemap_index.xml
$sitemapIndexContent = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
$sitemapIndexContent .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

$sitemapChunkSize = 20000;
$sitemapFileIndex = 1;
for ($i = 0; $i < count($sitemapUrls); $i += $sitemapChunkSize) {
    $sitemapChunk = array_slice($sitemapUrls, $i, $sitemapChunkSize);

    $sitemapFile = './sitemap' . $sitemapFileIndex . '.xml';
    $sitemapContent = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
    $sitemapContent .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

    foreach ($sitemapChunk as $url) {
        $sitemapContent .= '<url>' . PHP_EOL;
        $sitemapContent .= '<loc>' . htmlspecialchars($url) . '</loc>' . PHP_EOL;
        $sitemapContent .= '</url>' . PHP_EOL;
    }

    $sitemapContent .= '</urlset>';

    file_put_contents($sitemapFile, $sitemapContent);
    echo "Sitemap dibuat: $sitemapFile\n";

    $sitemapIndexContent .= '<sitemap>' . PHP_EOL;
    $sitemapIndexContent .= '<loc>' . htmlspecialchars($baseUrl . '/sitemap' . $sitemapFileIndex . '.xml') . '</loc>' . PHP_EOL;
    $sitemapIndexContent .= '</sitemap>' . PHP_EOL;

    $sitemapFileIndex++;
}

$sitemapIndexContent .= '</sitemapindex>';
file_put_contents('./sitemap_index.xml', $sitemapIndexContent);
echo "Sitemap index dibuat: ./sitemap_index.xml\n";

// Menyusun isi robots.txt
$robotsContent = "User-agent: *" . PHP_EOL;

// Tambahkan sitemap index ke dalam robots.txt
$robotsContent .= "Sitemap: $baseUrl/sitemap_index.xml" . PHP_EOL;

// Menambahkan setiap sitemap ke dalam robots.txt
for ($j = 1; $j < $sitemapFileIndex; $j++) {
    $robotsContent .= "Sitemap: $baseUrl/sitemap$j.xml" . PHP_EOL;
}

file_put_contents('./robots.txt', $robotsContent);
echo "Dihasilkan robots.txt: ./robots.txt" . PHP_EOL;
?>
