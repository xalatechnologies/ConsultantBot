<?php

namespace XalaTechnologies\ConsultantBot\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['title_en', 'title_nb', 'description_en', 'description_nb'];

    // Get title based on locale
    public function getTitleByLocale($locale)
    {
        return $this->{'title_' . $locale};
    }

    // Get description based on locale
    public function getDescriptionByLocale($locale)
    {
        return $this->{'description_' . $locale};
    }
}
