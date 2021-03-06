<?php
/**
 * webtrees: online genealogy
 * Copyright (C) 2019 webtrees development team
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
declare(strict_types=1);

namespace Fisharebest\Webtrees\Http\Middleware;

use Closure;
use Exception;
use Fisharebest\Webtrees\Site;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Cached\CachedAdapter;
use League\Flysystem\Cached\Storage\Memory;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to set the data storage area.
 */
class UseFilesystem implements MiddlewareInterface
{
    /**
     * @param Request $request
     * @param Closure $next
     *
     * @return Response
     * @throws Exception
     */
    public function handle(Request $request, Closure $next): Response
    {
        define('WT_DATA_DIR', realpath(Site::getPreference('INDEX_DIRECTORY', 'data/')) . DIRECTORY_SEPARATOR);

        $filesystem = new Filesystem(new CachedAdapter(new Local(WT_DATA_DIR), new Memory()));
        app()->instance(FilesystemInterface::class, $filesystem);

        return $next($request);
    }
}
