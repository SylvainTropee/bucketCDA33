<?php

namespace App\Services;

use App\Form\Model\SearchEvent;

class EventApiService
{

    private readonly string $BASE_URL;

    public function __construct()
    {
        $this->BASE_URL = "https://public.opendatasoft.com/api/records/1.0/search/?dataset=evenements-publics-openagenda";
    }

    public function search(SearchEvent $searchEvent)
    {

        $url = $this->BASE_URL;

        if ($searchEvent->getCity()) {
            $url .= "&refine.location_city=" . $searchEvent->getCity();
        }

        if ($searchEvent->getDate()) {
            $url .= "&refine.firstdate_begin=" . $searchEvent->getDate()->format('Y-m-d');
        }

        $content = file_get_contents($url);

        return json_decode($content, true);

    }


}