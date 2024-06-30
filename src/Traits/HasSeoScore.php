<?php

namespace Vormkracht10\Seo\Traits;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\DB;
use Vormkracht10\Seo\Facades\Seo;
use Vormkracht10\Seo\Models\SeoScore as SeoScoreModel;
use Vormkracht10\Seo\SeoScore;

trait HasSeoScore
{
    public function seoScore(): SeoScore
    {
        $seo = Seo::check(url: $this->url);
        $this->saveScoreToDatabase($seo,$this->url , $this);
        return $seo;
    }

    public function seoScores(): MorphMany
    {
        return $this->morphMany(SeoScoreModel::class, 'model');
    }

    public function scopeWithSeoScores(Builder $query): Builder
    {
        return $query->whereHas('seoScores')->with('seoScores');
    }

    public function getCurrentScore(): int
    {
        return $this->seoScore()->getScore();
    }

    public function getCurrentScoreDetails(): array
    {
        return $this->seoScore()->getScoreDetails();
    }
    private function saveScoreToDatabase(SeoScore $seo, string $url, ?object $model = null): void
    {
        $score = $seo->getScore();

        // Get the failed checks of each score so we can store them in the scan table.
        $failedChecks = $seo->getFailedChecks()->map(function ($check) {
            return get_class($check);
        })->toArray();


        DB::table('seo_scores')
            ->insert([
                'seo_scan_id' => $this->scan->id,
                'url' => $url,
                'model_type' => $model ? $model->getMorphClass() : null,
                'model_id' => $model ? $model->id : null,
                'score' => $score,
                'checks' => json_encode([
                    'failed' => $seo->getFailedChecks(),
                    'successful' => $seo->getSuccessfulChecks(),
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    }
}
