<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use Carbon\Carbon;

class GenerateEvents extends Command
{
    protected $signature = 'events:generate {--days=30}';
    protected $description = 'Genera eventos recurrentes (daily, weekly, monthly, anniversary) con antelación';

    public function handle()
    {
        $days = (int) $this->option('days');
        $limitDate = Carbon::now()->addDays($days);

        $this->info("Generando eventos recurrentes hasta {$limitDate->toDateString()}...");

        $events = Event::whereIn('recurrence_type', ['daily', 'weekly', 'monthly', 'anniversary'])
                       ->whereNull('parent_id') // solo eventos base
                       ->get();

        foreach ($events as $event) {
            switch ($event->recurrence_type) {
                case 'daily':
                    $this->generateDaily($event, $limitDate);
                    break;
                case 'weekly':
                    $this->generateWeekly($event, $limitDate);
                    break;
                case 'monthly':
                    $this->generateMonthly($event, $limitDate);
                    break;
                case 'anniversary':
                    $this->generateAnniversary($event, $limitDate);
                    break;
            }
        }

        $this->info('Eventos recurrentes generados correctamente.');
    }

    private function generateDaily(Event $event, Carbon $limitDate)
    {
        $last = $this->getLastChild($event);
        $nextDate = $last->start->copy()->addDay();

        while ($nextDate <= $limitDate) {
            $this->cloneEvent($event, $nextDate);
            $nextDate->addDay();
        }
    }

    private function generateWeekly(Event $event, Carbon $limitDate)
    {
        $last = $this->getLastChild($event);
        $nextDate = $last->start->copy()->addWeek();

        while ($nextDate <= $limitDate) {
            $this->cloneEvent($event, $nextDate);
            $nextDate->addWeek();
        }
    }

    private function generateMonthly(Event $event, Carbon $limitDate)
    {
        $last = $this->getLastChild($event);
        $nextDate = $last->start->copy()->addMonth();

        while ($nextDate <= $limitDate) {
            $this->cloneEvent($event, $nextDate);
            $nextDate->addMonth();
        }
    }

    private function generateAnniversary(Event $event, Carbon $limitDate)
    {
        $last = $this->getLastChild($event);
        $nextDate = $last->start->copy()->addYear();

        while ($nextDate <= $limitDate) {
            $this->cloneEvent($event, $nextDate);
            $nextDate->addYear();
        }
    }

    private function getLastChild(Event $event)
    {
        return Event::where('parent_id', $event->id)
                    ->orderBy('start', 'desc')
                    ->first() ?? $event;
    }

    private function cloneEvent(Event $event, Carbon $newDate)
    {
        Event::create([
            'title'            => $event->title,
            'start'            => $newDate,
            'event_type_id'    => $event->event_type_id,
            'recurrence_type'  => 'none', // instancias no vuelven a repetirse
            'parent_id'        => $event->id,
            'event_template_id'=> $event->event_template_id,
        ]);

        $this->line(" → Generado {$event->title} para {$newDate->toDateString()}");
    }
}
