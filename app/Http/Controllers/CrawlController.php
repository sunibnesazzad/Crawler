<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use Embed\Embed;
use App\FrequencyTable;
use App\FrequencyLinks;

class CrawlController extends Controller
{


    public function frequencylink()
    {
        $url = "https://www.lotteryinformation.us/state-lottery.php?state=FL&tb_state=&tb_links=&tb_country=US&tb_lang=0&adsurl=&tbsite=&d=.";

        $client = new Client();

        $crawler = $client->request('GET', $url);

        /*$links_count =   $crawler->selectLink('Frequency Chart')->count();*/
        $links_count =$crawler->filter('span[class="draw_date red1 fs15"]')->count();

        $all_links = [];

        if($links_count > 0){

            $links = $crawler->selectLink('Frequency Chart')->links();

            foreach ($links as $link)
            {
                $exact_link= $link->getURI();
                $exact_link= "https://www.lotteryinformation.us".substr($exact_link, 21, -3);
                $all_links[] = $exact_link;
                $frequency= new FrequencyLinks();
                $frequency->name = $exact_link;
                $frequency->save();
            }

            $all_links = array_unique($all_links);
           /* echo "All Avialble Links From this page $url Page<pre>"; print_r($all_links);echo "</pre>";*/
        } else {
            echo "No Links Found";
        }
        return $all_links;
    }

    public function call()
    {
        $links = FrequencyLinks::all();
        foreach ($links as $link)
        {
            $pageUrl= $link->name;
            $this->numbers($pageUrl);
        }
        return "done";
    }

    public function numbers($pageUrl)
    {
        $url = $pageUrl;

        $client = new Client();


        $crawler = $client->request('GET', $url);

        $tr_elements = $crawler->filterXPath('//body/center/table/tr/td');
        // iterate over filter results
        foreach ($tr_elements as  $content)
        {
            $tds = array();

            // create crawler instance for result
            $crawler = new Crawler($content);
            //iterate again
            /*$links_count =$crawler->filter('tr[valign="middle"]')->count();
            return $links_count;*/
            foreach ($crawler->filter('tr[valign="middle"]') as  $node) {
                $crawler = new Crawler($node);
                foreach ($crawler->filter('td[class="td0"]')->eq(0) as  $node)
                {
                    $rank = $crawler->filter('td[class="td0"]')->eq(1)->text();
                    $tds[] = $rank;
                    $hit = $crawler->filter('td[class="td0"]')->eq(2)->text();
                    $tds[] = $hit;

                    $frequency_table = new FrequencyTable;
                    $frequency_table->rank = $rank;
                    $frequency_table->hit = $hit;
                    $frequency_table->link_name = $url;
                    $frequency_table->save();

                  /* $tds[] = $node->nodeValue;*/
                }
            }
           /* dd($tds);*/
        }

    }

    public function dates()
    {
        $url = "https://www.lotteryinformation.us/apps/freq-chart.php?state=FL&game=FLEVE2";

        $client = new Client();


        $crawler = $client->request('GET', $url);

        $tr_elements = $crawler->filterXPath('//body/center/table/tr/td');
        // iterate over filter results
        foreach ($tr_elements as  $content)
        {
            $tds = array();

            // create crawler instance for result
            $crawler = new Crawler($content);
            //iterate again
            /*$links_count =$crawler->filter('tr[valign="middle"]')->count();
            return $links_count;*/
            foreach ($crawler->filter('tr[valign="middle"]') as  $node) {
                $crawler = new Crawler($node);
                foreach ($crawler->filter('td[class="td0"]')->eq(0) as  $node)
                {
                    $rank = $crawler->filter('td[class="td0"]')->eq(1)->text();
                    $tds[] = $rank;
                    $hit = $crawler->filter('td[class="td0"]')->eq(2)->text();
                    $tds[] = $hit;

                    $pp =  FrequencyLinks::where('name',$url)->get();
                    dd($pp);

                    $frequency_table = new FrequencyTable;
                    $frequency_table->rank = $rank;
                    $frequency_table->hit = $hit;
                    $frequency_table->link_name = $url;
                    $frequency_table->save();

                    /* $tds[] = $node->nodeValue;*/
                }
            }
            /* dd($tds);*/
        }

    }



}

