<?php
// Array containing portfolio website URLs.
$portfolioSites = [
    'https://stripe.com',
    'https://openai.com',
    'https://www.airbnb.com',
    'https://notion.so',
    'https://vercel.com'
];

// Function to fetch page metadata.
function getWebsiteData($url)
{
    // Create default fallback values.
    $data = [
        'title' => parse_url($url, PHP_URL_HOST),
        'description' => 'No description available.'
    ];

    // Create stream context with timeout and browser user-agent.
    $context = stream_context_create([
        'http' => [
            'timeout' => 5,
            'header' => "User-Agent: Mozilla/5.0\r\n"
        ]
    ]);

    // Attempt to fetch HTML content.
    $html = @file_get_contents($url, false, $context);

    // Return fallback if request failed.
    if (!$html) {
        return $data;
    }

    // Extract page title.
    if (preg_match('/<title>(.*?)<\/title>/is', $html, $matches)) {
        $data['title'] = trim(strip_tags($matches[1]));
    }

    // Extract meta description.
    if (preg_match('/<meta[^>]+name=["\']description["\'][^>]+content=["\'](.*?)["\']/is', $html, $matches)) {
        $data['description'] = trim(strip_tags($matches[1]));
    }

    // Return parsed data.
    return $data;
}
?><!DOCTYPE html><html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Portfolio Gallery</title>
<style>
body{
font-family:Arial,sans-serif;
background:#0f172a;
margin:0;
padding:40px;
color:white;
}
h1{
text-align:center;
margin-bottom:40px;
}
.gallery{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(320px,1fr));
gap:25px;
}
.card{
background:#1e293b;
border-radius:20px;
overflow:hidden;
transition:.35s;
box-shadow:0 10px 30px rgba(0,0,0,.4);
}
.card:hover{
transform:translateY(-8px);
}
.card img{
width:100%;
height:220px;
object-fit:cover;
}
.content{
padding:20px;
}
.content h2{
font-size:20px;
margin:0 0 10px;
}
.content p{
opacity:.8;
line-height:1.5;
height:60px;
overflow:hidden;
}
.btn{
display:inline-block;
padding:10px 15px;
background:#3b82f6;
color:white;
text-decoration:none;
border-radius:10px;
margin-top:15px;
}
</style>
</head>
<body>
<h1>Portfolio Gallery</h1>
<div class="gallery">
<?php
// Loop through each portfolio site.
foreach($portfolioSites as $site):// Fetch metadata. $info=getWebsiteData($site);

// Generate screenshot using a public screenshot endpoint. $screenshot='https://image.thum.io/get/width/1200/crop/800/'.$site; ?>

<div class="card">
<img src="<?=htmlspecialchars($screenshot)?>" loading="lazy">
<div class="content">
<h2><?=htmlspecialchars($info['title'])?></h2>
<p><?=htmlspecialchars($info['description'])?></p>
<a class="btn" href="<?=htmlspecialchars($site)?>" target="_blank">Visit Website</a>
</div>
</div>
<?php endforeach;?>
</div>
</body>
</html>