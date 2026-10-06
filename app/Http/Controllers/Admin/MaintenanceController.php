<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Throwable;

/**
 * Admin-only server tools for hosting without SSH: clear Laravel's caches and connect public/storage.
 */
class MaintenanceController extends Controller
{
    /** Commands run by "Clear all caches", in order. optimize:clear already covers most; each is repeated so one failure cannot hide the rest. */
    private const CLEAR_COMMANDS = ['optimize:clear', 'view:clear', 'cache:clear', 'route:clear', 'config:clear'];

    public function index(): View
    {
        return view('admin.maintenance.index', [
            'storage' => $this->storageStatus(),
        ]);
    }

    public function clearCache(): RedirectResponse
    {
        $log = [];
        $failed = false;

        foreach (self::CLEAR_COMMANDS as $command) {
            try {
                Artisan::call($command);
                $log[] = ['ok' => true, 'text' => $command . ' - done'];
            } catch (Throwable $exception) {
                $failed = true;
                $log[] = ['ok' => false, 'text' => $command . ' - failed: ' . $exception->getMessage()];
            }
        }

        return redirect()->route('admin.maintenance.index')
            ->with('maintenance_log', $log)
            ->with('maintenance_title', 'Clear caches')
            ->with($failed ? 'maintenance_error' : 'status', $failed ? 'Some cache commands failed. See the details below.' : 'All caches cleared (optimize, view, cache, route and config).');
    }

    public function storageLink(): RedirectResponse
    {
        $status = $this->storageStatus();

        if ($status['state'] === 'connected') {
            return $this->linkResult(true, 'The storage link is already connected.', [['ok' => true, 'text' => 'public/storage already points to storage/app/public']]);
        }

        if ($status['state'] === 'blocked') {
            return $this->linkResult(false, $status['message'], [['ok' => false, 'text' => $status['message']]]);
        }

        try {
            File::ensureDirectoryExists(storage_path('app/public'));
            Artisan::call('storage:link');
            $output = trim(Artisan::output());
        } catch (Throwable $exception) {
            $message = 'Could not create the link: ' . $exception->getMessage() . ' Your hosting may block symbolic links; ask support to enable symlink() or create the link for you.';

            return $this->linkResult(false, $message, [['ok' => false, 'text' => $message]]);
        }

        $after = $this->storageStatus();

        return $after['state'] === 'connected'
            ? $this->linkResult(true, 'Storage link connected.', [['ok' => true, 'text' => $output !== '' ? $output : 'public/storage now points to storage/app/public']])
            : $this->linkResult(false, 'The link command ran but the link was not created. Your hosting may block symbolic links.', [['ok' => false, 'text' => $output]]);
    }

    /** @return array{state: string, message: string} */
    private function storageStatus(): array
    {
        $link = public_path('storage');
        $target = storage_path('app/public');

        // A link, junction or similar that already resolves to the right folder counts as connected.
        if (file_exists($link) && realpath($link) !== false && realpath($link) === realpath($target)) {
            return ['state' => 'connected', 'message' => 'public/storage points to storage/app/public.'];
        }

        if (is_link($link)) {
            $points = @readlink($link);

            return realpath($link) !== false && realpath($link) === realpath($target)
                ? ['state' => 'connected', 'message' => 'public/storage points to storage/app/public.']
                : ['state' => 'broken', 'message' => 'public/storage exists but points to ' . ($points ?: 'an unknown place') . '.'];
        }

        if (file_exists($link)) {
            return ['state' => 'blocked', 'message' => 'public/storage already exists as a normal folder or file, so a link cannot be created. Rename or remove it first.'];
        }

        return ['state' => 'missing', 'message' => 'public/storage does not exist yet.'];
    }

    private function linkResult(bool $ok, string $message, array $log): RedirectResponse
    {
        return redirect()->route('admin.maintenance.index')
            ->with('maintenance_log', $log)
            ->with('maintenance_title', 'Storage link')
            ->with($ok ? 'status' : 'maintenance_error', $message);
    }
}
