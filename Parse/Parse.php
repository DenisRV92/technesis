<?php

class Parse
{
    private string $url;

    public int|float $allSize;

    public function __construct(string $url)
    {
        $this->url = $url;
        $this->allSize = 0;
    }

    /**
     * Парсим сайт
     */
    public function parseUrl(): array
    {
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $domain = $this->getDomain();
        return $this->getImages($response, $domain);
    }

    /**
     * Получаем изображения
     * @param string $responce
     * @param string $domain
     * @return array
     */
    public function getImages(string $responce, string $domain)
    {

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($responce);
        $arr = [];
        $images = $dom->getElementsByTagName('img');
        foreach ($images as $image) {
            $src = $image->getAttribute('src');
            if ($src != '' && preg_match('/\.(jpg|jpeg|png|gif)$/i', $src)) {
                $arr[] = $this->checkUrlImage($src, $domain);
            }
        }
        $this->calculateSizeImageInMb();
        return $arr;
    }

    /**
     * Получаем домен
     * @return string
     */
    public function getDomain(): string
    {
        $parsedUrl = parse_url($this->url);
        return $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
    }

    /**
     * Проверяем рабочее ли изображение
     * @param string $src
     * @param string $domain
     * @return false|string
     */
    public function checkUrlImage(string $src, string $domain)
    {
        $parsedSrc = parse_url($src);
        $path = !isset($parsedSrc['host']) ? $domain . '//' . $src : $src;
        $path = $this->checkIsProtocolOrNoImage($path);
        $image_data = file_get_contents($path);
        $image_size = strlen($image_data);
        if ($image_data !== false && (int)$image_size > 0) {
            $this->allSize += (int)$image_size;
            return $path;
        }
        return false;
    }

    /**
     * Переводим байты в мегабайты
     * @return void
     */
    public function calculateSizeImageInMb()
    {
        $this->allSize = round($this->allSize / (1024 * 1024), 2);
    }

    /** Проверяем начинаются ли сссылки с '||'
     * @param string $path
     * @return string
     */
    public function checkIsProtocolOrNoImage(string $path): string
    {
        if (strpos($path, "//") === 0) {
            return "https:" . $path;
        }
        return $path;
    }
}