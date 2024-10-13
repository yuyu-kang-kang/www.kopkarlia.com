<?php

// Pastikan fungsi hanya dideklarasikan jika belum ada
if (!function_exists('formatTopicForFile')) {
    function formatTopicForFile($topic) {
        return str_replace(' ', '-', strtolower($topic));
    }
}

if (!function_exists('formatTopicForDisplay')) {
    function formatTopicForDisplay($topic) {
        return ucwords(str_replace('-', ' ', $topic));
    }
}

function generateRandomTitleAndDescription($topic) {
    $emojis = [
        '🚀',
        '🌌',
        '🍀',
        '⚡',
        '🔥',
        '🌟',
        '✨',
        '🎯',
        '🎉',
        '🏆'
    ];

    $actions = [
        'pulsa tanpa potongan',
        'slot deposit 5000',
        'deposit 5000',
        'slot pulsa',
        'Akses Cepat',
        'Pusat Pengalaman',
        'Pintu Masuk',
        'Layanan Premium',
        'Pintu Utama',
        'Area Eksklusif',
    ];

    $descriptors = [
        'Slot Megah',
        'Slot Beruntung',
        'Slot Fantastis',
        'Slot Menakjubkan',
        'Slot Mengasyikkan',
        'Slot Luar Angkasa',
        'Slot Kosmik',
        'Slot Terhebat',
        'Slot Spektakuler',
        'Slot Epik',
        'Slot Dahsyat',
        'Slot Sensasional',
        'Slot Megahit',
        'Slot Angkasa',
        'Slot Elite',
        'Slot Terfavorit',
        'Slot Paling Dicari',
        'Slot Nomor Satu',
        'Slot Super',
        'Slot Eksklusif'
    ];
    
    $extras = [
        'Dengan RTP Galaksi Tinggi',
        'Peluang Menang Spektakuler',
        'Deposit Via E-Wallet Tanpa Potongan',
        'Jackpot Megah Setiap Hari',
        'Slot Baru Menggelegar',
        'Paling Gacor Sepanjang Masa',
        'Keamanan Super Ketat',
        'Bonus Kosmik Besar',
        'Transaksi Kilat',
        'Banyak Hadiah Menanti',
        'Terdaftar Resmi dan Aman',
        'Pembayaran Pasti Cepat',
        'Jackpot Terus Menerus',
        'Menguntungkan Sepanjang Hari',
        'Gameplay Mudah dan Seru',
        'Jackpot Progresif Tanpa Batas',
        'Fitur Terbaru dan Terbaik',
        'Banyak Kejutan Menanti',
        'Bonus Berlimpah Setiap Hari',
        'Layanan Dukungan Nonstop',
        'Transaksi Lancar dan Cepat',
        'Kemenangan Setiap Hari',
        'Dijamin Aman 100%',
        'Permainan Paling Populer'
    ];

    $connectors = [
        'serta',
        'dan',
        'dengan',
        'untuk',
        'menghadirkan'
    ];

    $adjectives = [
        'Fantastis',
        'Epik',
        'Luar Biasa',
        'Megah',
        'Menakjubkan',
        'Spektakuler',
        'Fenomenal',
        'Elite',
        'Istimewa',
        'Dahsyat'
    ];

    $templates = [
        "Bergabunglah dengan {topic}, {descriptor} {connector} {extra}. Dapatkan kesempatan memenangkan hadiah besar dan menikmati permainan yang menarik dan seru. Rasakan sensasi bermain {topic} dengan fitur-fitur terbaik yang kami tawarkan.",
        "Temukan {topic}, tempat {descriptor} berkumpul {connector} {extra}. Dapatkan pengalaman bermain yang luar biasa dengan berbagai macam bonus dan layanan unggulan yang kami sediakan. Bergabunglah sekarang {topic} dan nikmati permainan yang menguntungkan.",
        "{topic} menyediakan {descriptor} {connector} {extra}. Nikmati permainan terbaik dengan peluang menang tinggi. Daftar sekarang dan jadilah bagian dari komunitas kami yang selalu menang.",
        "{topic} adalah pilihan terbaik untuk {descriptor} {connector} {extra}. Kami menawarkan permainan dengan RTP tinggi dan banyak {topic} bonus menarik. Jangan lewatkan kesempatan ini untuk menang besar.",
        "Mainkan {topic}, {descriptor} {connector} {extra}. Dengan dukungan 24/7 dan transaksi mudah, kami menjamin pengalaman bermain yang aman dan nyaman.",
        "{topic} hadir dengan {descriptor} {connector} {extra}. Dapatkan akses ke permainan terbaru dan fitur canggih yang kami tawarkan. Daftar sekarang dan nikmati kemenangan konsisten.",
        "{topic}, tempat {descriptor} bertemu {connector} {extra}. Kami menawarkan layanan premium dan bonus besar untuk setiap anggota. Bergabunglah sekarang dan rasakan keuntungan bermain bersama kami.",
        "Dengan {topic}, {descriptor} {connector} {extra} menjadi lebih mudah. Kami menawarkan permainan dengan RTP tinggi dan banyak jackpot menanti Anda.",
        "Bergabunglah di {topic} dan nikmati {descriptor} {connector} {extra}. Kami menyediakan berbagai macam permainan seru dengan peluang menang tinggi. Daftar sekarang dan raih kemenangan besar.",
        "{topic} menghadirkan {descriptor} {connector} {extra}. Dengan banyak fitur menarik dan bonus besar, pengalaman bermain Anda akan menjadi lebih menyenangkan dan menguntungkan.",
        "{topic} adalah pusat {descriptor} {connector} {extra}. Kami menawarkan berbagai macam permainan dengan fitur terbaik dan bonus besar. Jangan lewatkan kesempatan untuk menang besar bersama kami.",
        "Mainkan {topic} dan nikmati {descriptor} {connector} {extra}. Kami menjamin pengalaman bermain yang luar biasa dengan banyak kemenangan dan fitur menarik.",
        "Temukan {topic}, tempat {descriptor} {connector} {extra}. Daftar sekarang dan dapatkan akses ke permainan dengan RTP tinggi dan banyak bonus menarik.",
        "{topic} menawarkan {descriptor} {connector} {extra}. Bergabunglah sekarang dan nikmati permainan dengan peluang menang tinggi dan banyak bonus menanti Anda."
    ];

    // Acak kata kunci dan pilih beberapa
    $emoji = $emojis[array_rand($emojis)];
    $action = $actions[array_rand($actions)];
    $descriptor = $descriptors[array_rand($descriptors)];
    $extra = $extras[array_rand($extras)];
    $connector = $connectors[array_rand($connectors)];
    $adjective = $adjectives[array_rand($adjectives)];

    // Format topik
    $formattedTopicForFile = formatTopicForFile($topic);
    $formattedTopicForDisplay = formatTopicForDisplay($topic);

    // Bangun judul dan deskripsi
    $title = "$formattedTopicForDisplay $emoji $action $adjective $descriptor";
    $description = str_replace(
        ["{descriptor}", "{connector}", "{extra}", "{topic}"],
        [$descriptor, $connector, $extra, $formattedTopicForDisplay],
        $templates[array_rand($templates)]
    );

    return ['title' => $title, 'description' => $description, 'formattedTopicForFile' => $formattedTopicForFile];
}

// Baca data dari file data.txt
$filePath = 'data.txt';
$names = file($filePath, FILE_IGNORE_NEW_LINES);

// Looping untuk setiap nama dan batasi jumlah yang dihasilkan
foreach ($names as $index => $name) {
    // Pastikan folder data sudah ada atau buat jika belum ada
    if (!file_exists('data')) {
        mkdir('data', 0777, true);
    }

    // Variabel untuk menyimpan semua judul dan deskripsi
    $content = "";

    // Hasilkan 2 judul dan deskripsi
    for ($i = 0; $i < 5; $i++) {
        $data = generateRandomTitleAndDescription($name);
        $title = $data['title'];
        $description = $data['description'];

        // Tambahkan judul dan deskripsi ke konten
        $content .= "Title: $title\nDescription: $description\n\n";
    }

    // Buat nama file sesuai dengan nama dari data.txt tanpa angka
    $fileName = "data/{$data['formattedTopicForFile']}.txt";
    file_put_contents($fileName, $content);

    echo "Judul dan deskripsi berhasil dibuat dan disimpan di $fileName\n";
}
?>
