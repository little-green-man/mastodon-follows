<?php

namespace App\Library;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ExportView
{
    public static function view()
    {
        return <<<'EOT'
<!doctype html>
        <html>
        <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <script src="https://cdn.tailwindcss.com"></script>
          <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        </head>
        <body>

          <h1 class="p-3 text-3xl font-bold">
            The follower list!
          </h1>

        <div class="p-3 space-y-3">
            @foreach($items as $item)
            @if($item->hasData)
                <div x-data="{open:false}" class="border border-slate-200 rounded">
                    <div class="flex items-center space-x-3 p-1">
                        <img src="{{ $item->getIcon() }}" class="h-12 w-auto rounded-full" />
                        <div>
                            <a href="{{ $item->getUrl() }}">
                                {{ $item->getName() }} ({{ $item->getType() }})
                            </a>
                        </div>
                        <div class="flex-1 text-sm">{!! $item->getSummary() !!}</div>
                        @if($personalInstance)
                            <a href="{{ $personalInstance }}{{ $item->tryToCalculateHandle() }}" class="block bg-slate-300 hover:bg-slate-400 text-slate-900 px-1 rounded" target="_blank">Look up</a>
                        @endif
                            <button @click="open=!open" class="bg-slate-700 hover:bg-slate-900 text-white px-1 rounded">Expand</button>
                    </div>
                    <div x-show="open" x-transition>
                        <img src="{{ $item->getImage() }}" class="w-3/4 mx-auto p-2" />
                        <div class="flex items-center space-x-2">
                            <div>ID: {{ $item->getId() }}</div>
                            <div>Preferred Username: {{ $item->getPreferredUsername() }}</div>
                            <div>
                                @if($item->getManuallyApprovesFollowers())
                                    Manually approves followers
                                @else
                                    Automatically approves followers
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="border border-slate-200 rounded">
                    <div class="flex items-center space-x-3 p-1">
                        <div>
                            <a href="{{ $item->url }}">
                                {{ $item->url }}
                            </a>
                        </div>

                        @if($personalInstance)
                            <a href="{{ $personalInstance }}{{ $item->tryToCalculateHandle() }}" class="block bg-slate-300 hover:bg-slate-400 text-slate-900 px-1 rounded" target="_blank">Look up</a>
                        @endif
                            <button @click="open=!open" class="bg-slate-700 hover:bg-slate-900 text-white px-1 rounded">Expand</button>
                    </div>
                </div>
            @endif
            @endforeach
        </div>
        </body>
        </html>

EOT;
    }
}
