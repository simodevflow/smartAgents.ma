<?php

$repositories = [
    ['owner' => '1', 'repo' => 'http://davidmaj.huce0783.odns.fr'],
    ['owner' => 'smartagentsma-int', 'repo' => 'Institut_Jasmin2'],
    // ['owner' => 'smartagentsma-int', 'repo' => 'smartagents.int'],
    ['owner' => 'smartagentsma-int', 'repo' => 'Institut_Jasmin'],
    ['owner' => 'smartagentsma-int', 'repo' => 'KriAuto.ma'],
    ['owner' => 'smartagentsma-int', 'repo' => 'jetsahara.ma'],
    ['owner' => 'smartagentsma-int', 'repo' => 'mimi_world_website'],
    ['owner' => 'smartagentsma-int', 'repo' => 'penzion.avalanche'],
    ['owner' => 'smartagentsma-int', 'repo' => 'domaci-pece'],
    ['owner' => 'smartagentsma-int', 'repo' => 'JH-OBKLADY'],
    

    ['owner' => 'simodevflow', 'repo' => 'smartAgents.ma'],
    ['owner' => 'simodevflow', 'repo' => 'airbnb-activation'],
    ['owner' => '1', 'repo' => 'http://davidmaj.huce0783.odns.fr'],

];

function getWebsiteData($url)
{
    $data = [
        'title' => parse_url($url, PHP_URL_HOST),
        'description' => 'No description available.'
    ];

    $context = stream_context_create([
        'http' => [
            'timeout' => 5,
            'header' => "User-Agent: Mozilla/5.0\r\n"
        ]
    ]);

    $html = @file_get_contents($url, false, $context);

    if (!$html) {
        return $data;
    }

    if (preg_match('/<title>(.*?)<\/title>/is', $html, $m)) {
        $data['title'] = trim(strip_tags($m[1]));
    }

    if (preg_match('/<meta[^>]+name=["\']description["\'][^>]+content=["\'](.*?)["\']/is', $html, $m)) {
        $data['description'] = trim(strip_tags($m[1]));
    }

    return $data;
}

function getScreenshot($repoName, $siteUrl)
{
    $imgDir = __DIR__ . '/img';

    if (!is_dir($imgDir)) {
        mkdir($imgDir, 0755, true);
    }

    $fileName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $repoName) . '.jpg';

    $localFile = $imgDir . '/' . $fileName;
    $localUrl  = 'img/' . $fileName;

    if (!file_exists($localFile)) {

        $thumbUrl = 'https://image.thum.io/get/width/1200/crop/800/' . $siteUrl;

        $image = @file_get_contents($thumbUrl);

        if ($image !== false) {
            file_put_contents($localFile, $image);
        }
    }

    if (file_exists($localFile)) {
        return $localUrl;
    }

    return 'https://placehold.co/1200x800?text=Preview+Unavailable';
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Portfolio Gallery</title>

<style>
body{
    font-family:Arial,sans-serif;
    background:#0f172a;
    color:#fff;
    margin:0;
    padding:40px;
}
.gallery{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(350px,1fr));
    gap:24px;
}
.card{
    background:#1e293b;
    border-radius:18px;
    overflow:hidden;
}
.card img{
    width:100%;
    height:240px;
    object-fit:cover;
}
.content{
    padding:20px;
}
.btn{
    display:inline-block;
    padding:12px 18px;
    margin-top:15px;
    background:#2563eb;
    color:#fff;
    text-decoration:none;
    border-radius:8px;
}
</style>

</head>
<body>

<h1>Portfolio Gallery</h1>

<div class="gallery">

<?php foreach ($repositories as $repo): ?>

<?php



$site =
    "https://{$repo['owner']}.github.io/{$repo['repo']}/";
    
if ($repo['owner']=="1"){
    $site = $repo['repo'];
} 

$info = getWebsiteData($site);

$screenshot = getScreenshot(
    $repo['repo'],
    $site
);



?>

<div class="card">

    <img
        src="<?= htmlspecialchars($screenshot) ?>"
        alt="<?= htmlspecialchars($info['title']) ?>"
        loading="lazy"
    >

    <div class="content">

        <h2><?= htmlspecialchars($info['title']) ?></h2>

        <p><?= htmlspecialchars($info['description']) ?></p>

        <a
            class="btn"
            href="<?= htmlspecialchars($site) ?>"
            target="_blank"
        >
            Visit Website
        </a>

    </div>

</div>

<?php endforeach; ?>

</div>

</body>
</html>

