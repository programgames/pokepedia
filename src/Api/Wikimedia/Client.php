<?php

namespace App\Api\Wikimedia;

use App\Exception\InvalidResponse;
use Exception;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Translation\Exception\InvalidResourceException;

class Client
{
    /** @noinspection PhpUnhandledExceptionInspection */
    public function parse($url): array
    {
        $client = HttpClient::create();
        try {
            $content =  $client->request('GET', $url)->toArray();
            if (!array_key_exists('parse', $content)) {
                throw new InvalidResponse(sprintf('Invalid response from url %s, parse information is missing', $url));
            }
        } catch (Exception $exception) {
            throw new InvalidResourceException(sprintf('Invalid response from url %s', $url), $exception->getCode(), $exception);
        }

        return $content;
    }

    /**
     * @param string $endpoint
     * @param $parameters
     */
    public function edit(string $endpoint,$parameters)
    {
        $ch = curl_init();

        $params = http_build_query($parameters);
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");
        curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");

        $output = curl_exec($ch);
        curl_close($ch);
        $content = json_decode($output,true);
        if(!(isset($content['edit']['result']) && $content['edit']['result'] === 'Success')) {
            throw new InvalidResponse($content['error']['info']);
        }
    }

    public function formatSectionsByUrl(string $url)
    {
        $content = self::parse($url);

        $sections = [];
        $levelCursor = 2;
        $sectionTitle = '';
        foreach ($content['parse']['sections'] as $section) {
            $level = (int)$section['level'];

            $line = trim(str_replace(['<i>','</i>'],[' ',' '],$section['line']));
            if(empty($sectionTitle)) {
                $sectionTitle = $line;
            }
            if($levelCursor === $level) {

                $sectionTitle = substr($sectionTitle, 0, strrpos( $sectionTitle, '//'));
                if(empty($sectionTitle)) {
                    $sectionTitle = $line;
                } else {
                    $sectionTitle .= '//' . $line;
                }
                $sections[$sectionTitle] = $section['index'];
            } elseif ($levelCursor < $level) {
                $sectionTitle .= '//' . $line;
                $levelCursor = $level;
                $sections[$sectionTitle] = $section['index'];
            } else {
                for($i = $level ;$i < $levelCursor+1;$i ++) {
                    $sectionTitle = substr($sectionTitle, 0, strrpos( $sectionTitle, '//'));

                }
                if(empty($sectionTitle)) {
                    $sectionTitle = $line;
                } else {
                    $sectionTitle .= '//' . $line;
                }
                $levelCursor = $level;
                $sections[$sectionTitle] = $section['index'];
            }
        };

        return $sections;
    }
}
