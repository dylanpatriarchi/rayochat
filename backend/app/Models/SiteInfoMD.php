<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use League\CommonMark\CommonMarkConverter;

class SiteInfoMD extends Model
{
    protected $table = 'site_info_m_d_s';
    
    protected $fillable = [
        'site_id',
        'markdown_content',
        'html_content',
    ];

    protected static function boot()
    {
        parent::boot();

        // Auto-convert markdown to HTML when saving
        static::saving(function ($siteInfo) {
            if ($siteInfo->markdown_content) {
                $converter = new CommonMarkConverter([
                    'html_input' => 'strip',
                    'allow_unsafe_links' => false,
                    'renderer' => [
                        'block_separator' => "\n",
                        'inner_separator' => "\n",
                        'soft_break' => "\n",
                    ],
                ]);
                $siteInfo->html_content = $converter->convert($siteInfo->markdown_content)->getContent();
            }
        });
    }

    /**
     * Get the site that owns this info
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }
}
