<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\EventTemplate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GenerateRecurringEvents extends Command
{
    protected $signature = 'events:generate {--days=30}';
    protected $description = 'Genera eventos recurrentes y aniversarios a futuro';

    public function handle()
    {
        $daysAhead = (int)$this->option('days');
        $limitDate = Carbon::now()->addDays($daysAhead);

        $this->info("Generando eventos hasta: {$limitDate->toDateString()}");

        // Buscar eventos base (padres) que tengan recurrencia
        $parents = Event::where('recurrence_type', '!=', 'none')
            ->whereNull('parent_id')
            ->get();

        foreach ($parents as $event) {
            switch ($event->recurrence_type) {
                case 'daily': $this->generateDaily($event, $limitDate); break;
                case 'weekly': $this->generateWeekly($event, $limitDate); break;
                case 'monthly': $this->generateMonthly($event, $limitDate); break;
                case 'yearly': $this->generateYearly($event, $limitDate); break;
                case 'anniversary': $this->generateAnniversary($event); break;
            }
        }

        $this->info('Eventos generados correctamente ✅');
    }

    private function generateDaily(Event $event, Carbon $limitDate)
    {
        $this->generateByInterval($event, $limitDate, 'days');
    }

    private function generateWeekly(Event $event, Carbon $limitDate)
    {
        $this->generateByInterval($event, $limitDate, 'weeks');
    }

    private function generateMonthly(Event $event, Carbon $limitDate)
    {
        $this->generateByInterval($event, $limitDate, 'months');
    }

    private function generateYearly(Event $event, Carbon $limitDate)
    {
        $this->generateByInterval($event, $limitDate, 'years');
    }

    private function generateAnniversary(Event $event)
    {
        $last = Event::where('parent_id', $event->id)
            ->orderBy('start', 'desc')
            ->first();

        $baseDate = $last ? $last->start : $event->start;
        $nextDate = $baseDate->copy()->addYear();

        // Solo crear si no existe ya
        $exists = Event::where('parent_id', $event->id)
            ->whereDate('start', $nextDate->toDateString())
            ->exists();

        if (!$exists) {
            Event::create([
                'title' => $event->title,
                'start' => $nextDate,
                'event_type_id' => $event->event_type_id,
                'parent_id' => $event->id,
                'recurrence_type' => 'none',
                'description' => $event->description,
                'visible' => $event->visible,
            ]);
            $this->line("✔ Aniversario generado para {$nextDate->toDateString()}");
        }
    }

    private function generateByInterval(Event $event, Carbon $limitDate, string $unit)
    {
        $interval = $event->recurrence_interval ?? 1;

        $last = Event::where('parent_id', $event->id)
            ->orderBy('start', 'desc')
            ->first();

        $baseDate = $last ? $last->start : $event->start;

        while ($baseDate->lt($limitDate)) {
            $baseDate->add($unit, $interval);

            $exists = Event::where('parent_id', $event->id)
                ->whereDate('start', $baseDate->toDateString())
                ->exists();

            if (!$exists && $baseDate->lte($limitDate)) {
                Event::create([
                    'title' => $event->title,
                    'start' => $baseDate,
                    'event_type_id' => $event->event_type_id,
                    'parent_id' => $event->id,
                    'recurrence_type' => 'none',
                    'description' => $event->description,
                    'visible' => $event->visible,
                ]);
                $this->line("✔ Evento generado {$event->title} en {$baseDate->toDateString()}");
            }
        }
    }
}
