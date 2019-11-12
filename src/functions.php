<?php

/**
 * @param string $line
 *
 * @return void
 */
function writeln(string $line)
{
    echo $line . PHP_EOL;
}

/**
 * @param array $items
 *
 * @return array
 */
function array_flatten($items): array
{
    if (!is_array($items)) {
        return [$items];
    }

    return array_reduce($items, function ($carry, $item) {
        return array_merge($carry, array_flatten($item));
    }, []);
}

/**
 * @param array $data
 *
 * @return array
 */
function extractWebsiteEntries(array $data): array
{
    return array_flatten(array_filter(
        array_map(function ($item) {
            return array_map(function ($row) {
                return trim($row['u'] ?? '');
            }, $item['overview']['URLs'] ?? []);
        }, $data), function ($row) {
        return is_array($row) || empty($row);
    }));
}

/**
 * @return array
 */
function getDataFromOpCli(): array
{
    return json_decode(shell_exec("op list items"), true) ?: [];
}

/**
 * @param string $url
 * @param int    $index
 * @param int    $max
 *
 * @return bool
 * @throws \GuzzleHttp\Exception\GuzzleException
 */
function checkUrl(string $url, int $index = 0, int $max): bool
{
    try {
        echo $index + 1 . "/{$max}\tChecking URL {$url}...";
        (new GuzzleHttp\Client())->request('GET', $url, ['timeout' => 20]);
        echo " -> OK!\n";

        return true;
    } catch (\Exception $e) {
        writeln("\n !!  -> " . trim($e->getMessage()));

        return false;
    }
}

/**
 * @param array $urls
 *
 * @return array
 * @throws \GuzzleHttp\Exception\GuzzleException
 */
function filterFailingUrls(array $urls): array
{
    $count = count($urls) + 1;

    foreach ($urls as $row => $url) {
        checkUrl($url, $row, $count) ? $failed[] = $url : null;
    }

    return $failed ?? [];
}
