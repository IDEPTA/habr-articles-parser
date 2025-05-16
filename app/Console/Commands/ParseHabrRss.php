<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Article;
use Illuminate\Console\Command;

class ParseHabrRss extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rss:habr';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Парсит статьи с Хабра через RSS';
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $url = 'https://habr.com/ru/rss/all/all/';
        $xml = simplexml_load_file($url);

        foreach ($xml->channel->item as $item) {
            // Парсим данные из XML
            $title = (string)$item->title;
            $content = strip_tags((string)$item->description); // Очищаем от HTML
            $link = (string)$item->link;
            $publishedAt = Carbon::parse((string)$item->pubDate);
            $category = $item->category[0] ?? null;

            // Собираем все категории как массив тегов
            $tags = [];
            foreach ($item->category as $cat) {
                $tags[] = (string)$cat;
            }

            // Уникальность по URL
            Article::updateOrCreate(
                [
                    'url' => $link,
                    'title' => $title,
                    'content' => $content,
                    'source' => 'habr',
                    'published_at' => $publishedAt,
                    'category' => $category,
                ]
            );
        }

        $this->info('Статьи с Хабра загружены.');
    }
}
