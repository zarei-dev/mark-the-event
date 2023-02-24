<?php

declare(strict_types=1);

namespace ZareiDev\MarkTheEvent;

class ACFJSON {

    public function Directory( $path )
    {
        return MARK_THE_EVENT_DIR . 'admin/acf-json';
    }

    public function Save( $path )
    {
        return apply_filters("acf/json_directory", NULL);
    }

    public function Load( $paths )
    {
        return [
            apply_filters("acf/json_directory", NULL)
        ];
    }

}
