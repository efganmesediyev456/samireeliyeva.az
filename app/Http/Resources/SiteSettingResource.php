<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SiteSettingResource extends JsonResource
{
    
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'header_logo' => url('/storage/'.$this->header_logo),
            'footer_logo' => url('/storage/'.$this->footer_logo),
            'favicon' => url('/storage/'.$this->favicon),
            'service_whatsapp_number' => $this->service_whatsapp_number,
            'header_site_icon1' => url('/storage/'.$this->header_site_icon1),
            'header_site_icon2' => url('/storage/'.$this->header_site_icon2),
            'header_site_icon3' => url('/storage/'.$this->header_site_icon3),

            'header_site_url1' => $this->header_site_url1,
            'header_site_url2' => $this->header_site_url2,
            'header_site_url3' => $this->header_site_url3,
        ];
    }
}