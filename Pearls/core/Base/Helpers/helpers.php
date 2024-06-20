<?php

if (!function_exists('array_hashids_encode')) {
    function array_hashids_encode($array, $idKey = 'id')
    {
        return array_map(function ($element) use ($idKey) {
            $element[$idKey] = hashids_encode($element[$idKey]);

            return $element;
        }, $array);
    }
}

if (!function_exists('hashids_encode')) {
    /**
     * Encode the given id.
     * @param $id
     * @return mixed
     */
    function hashids_encode($id)
    {
        if (config('app.hashed_id_disabled', false)) {
            return $id;
        }

        return \Pearls\Base\Facades\Hashids::encode($id);
    }

}

if (!function_exists('hashids_decode')) {
    /**
     * Decode the given value.
     * @param $value
     * @return null
     */
    function hashids_decode($value)
    {
        if (config('app.hashed_id_disabled', false)) {
            return $value;
        }

        $decoded_value = \Pearls\Base\Facades\Hashids::decode($value);

        if (empty($decoded_value)) {
            return null;
        }

        if (count($decoded_value) == 1) {
            return $decoded_value[0];
        }

        return $decoded_value;
    }
}

if (!function_exists('removeEmptyArrayElement')) {
    function removeEmptyArrayElement($attribute)
    {
        // check for empty strings and null values
        // 0 excluded for cases such as min=0 in input attributes

        if ($attribute === 0 || $attribute === false) {
            return true;
        }

        return !empty($attribute);
    }
}

if (!function_exists('format_date')) {
    /**
     * Format date
     * @param $date
     * @param string $format
     * @return false|null|string
     */
    function format_date($date, $format = 'd M, Y')
    {
        if (empty($date)) return null;

        if ($date instanceof \Carbon\Carbon) {
            return $date->format($format);
        }

        return date($format, strtotime($date));
    }

}

if (!function_exists('format_date_time')) {
    /**
     * Format datetime
     * @param $datetime
     * @param string $format
     * @return false|null|string
     */
    function format_date_time($datetime, $format = 'd M, Y h:i A')
    {
        if (empty($datetime)) return null;

        if ($datetime instanceof \Carbon\Carbon) {
            return $datetime->format($format);
        }

        return date($format, strtotime($datetime));
    }

}

if (!function_exists('format_time')) {
    /**
     * Format time.
     * @param $time
     * @param string $format
     * @return false|null|string
     */
    function format_time($time, $format = 'h:i A')
    {
        if (empty($time)) return null;

        return date($format, strtotime($time));
    }

}

if (!function_exists('splitCamelCaseString')) {
function splitCamelCaseString($camelCaseString, $split = ' ')
{
    $re = '/(?<=[a-z])(?=[A-Z])/x';
    $a = preg_split($re, $camelCaseString);
    return join($split, $a);
}
}
if (!function_exists('formatStatusResponse')) {
    function formatStatusResponse($status)
    {
        $class = '';
        switch ($status) {
            case 'active':
                $class = 'label-success';
                break;
            case 'pending':
                $class = 'label-warning';
                break;
            case 'disabled':
                $class = 'label-danger';
                break;
        }

        $response = '<span class="label ' . $class . '">' . trans('Pearls::common.status.' . $status) . '</span>&nbsp;';

        return $response;
    }
}

if (!function_exists('log_exception')) {
    function log_exception(\Exception $exception = null, $object = null, $action = null, $message = null, $echo_message = false)
    {
        if ($exception) {
            app(\App\Exceptions\Handler::class)->report($exception);
            $message = $exception->getMessage() . '. ' . ($message ?? '');
        } else {
            $message = ($message ?? '');
        }


        $activity = activity()
            ->inLog('exception')
            ->withProperties(['attributes' => ['action' => $action, 'object' => $object, 'message' => $message]]);

        if (user()) {
            $activity = $activity->causedBy(user());
        }

        $activity = $activity->log(str_limit($message, 180));
        if (request()->ajax()) {
            $message = ['level' => 'error', 'message' => $message];
            request()->session()->flash('notification', $message);
            if ($echo_message) {

                $return_message = ['notification' => $message];
                echo json_encode($return_message);
                die();

            } else {
                if (request()->wantsJson()) {
                    if ($echo_message) {
                        $return_message = ['notification' => $message];
                        echo response()->json($return_message);
                    }

                } else {
                    $return_message = ['notification' => $message];
                    echo json_encode($return_message);
                    die();
                }
            }

        } else {
            flash($message, 'error');
        }

    }
}
if (!function_exists('generatePopover')) {
    /**
     * @param $content
     * @param string $text
     * @param string $icon
     * @param string $placement
     * @param null $trigger
     * @return string
     */
    function generatePopover($content, $text = '', $icon = 'fa fa-sticky-note', $placement = 'bottom', $trigger = null)
    {
        if (empty($content)) {
            return '-';
        }

        $content = iconv(mb_detect_encoding($content, mb_detect_order(), true), "UTF-8", $content);

        $content = htmlspecialchars($content, ENT_COMPAT, 'UTF-8');

        return '<a href="#" onclick="event.preventDefault();" data-toggle="popover" data-placement="' . $placement . '" data-html="true" ' . (!is_null($trigger) ? ('data-trigger="' . $trigger . '"') : '') . '" data-content="' . $content . '"><i class="' . $icon . '"></i> ' . $text . '</a>';
    }
}

if (!function_exists('keyValuePresentation')) {
    function keyValuePresentation($pairs, $label = ['key' => 'Key', 'value' => 'Value'])
    {
        $presentation = '<table class="table table-responsive">';
        $presentation .= "<thead><tr><th>{$label['key']}</th><th>{$label['value']}</th></tr></thead><tbody>";

        if (empty($pairs)) {
            $presentation .= "<tr><td colspan='2'>No Records Found</td></tr>";
        } else {
            foreach ($pairs as $key => $value) {
                $presentation .= "<tr><td>{$key}</td><td>{$value}</td></tr>";
            }
        }

        $presentation .= '</tbody></table>';

        return $presentation;
    }
}

if (!function_exists('formatArrayAsLabels')) {
    function formatArrayAsLabels($array, $level = 'default', $icon = '')
    {
        $response = '';

        if (!$array) {
            return '-';
        }

        foreach ($array as $item) {
            $response .= "<span class=\"label label-{$level}\">{$icon} {$item}</span>&nbsp;";
        }

        if (empty($response)) {
            return '-';
        }

        return $response;
    }
}

if (!function_exists('redirectTo')) {
    /**
     * Get an instance of the redirector.
     * @param null $to
     * @param int $status
     * @param array $headers
     * @param null $secure
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|mixed
     */
    function redirectTo($to = null, $status = 302, $headers = [], $secure = null)
    {
        $request = request();
        if ($request->wantsJson()) {
            $result = ['status' => 'success', 'action' => 'redirectTo', 'url' => url($to)];
            return response()->json($result);
        }
        if (is_null($to)) {
            return app('redirect');
        }

        return app('redirect')->to($to, $status, $headers, $secure);
    }
}

if (!function_exists('maxUploadFileSize')) {
    function maxUploadFileSize($unit = 'KB')
    {
        $size = config('medialibrary.max_file_size');

        switch ($unit) {
            case 'B':
                break;
            case 'KB':
                $size = $size / 1024;
                break;
            case 'MB':
                $size = $size / (1024 * 1024);
                break;
        }

        return $size;
    }
}

if (!function_exists('computeDistanceUsingLatLong')) {
    function computeDistanceUsingLatLong($first, $second)
    {
        $lat1 = $first['lat'];
        $long1 = $first['long'];

        $lat2 = $second['lat'];
        $long2 = $second['long'];
        $theta = $long1 - $long2;

        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            cos(deg2rad($theta));

        $dist = acos($dist);

        $dist = rad2deg($dist);

        $miles = $dist * 60 * 1.1515;

        return $miles;
    }
}

if (!function_exists('decimalFormat')) {
    function decimalFormat($number)
    {
        return number_format($number, 2, '.', '');
    }
}

if (!function_exists('getQueryWithParameters')) {
    function getQueryWithParameters($query)
    {
        $sql = $query->toSql();
        $parameters = $query->getBindings();
        $result = "";

        $sql_chunks = explode('?', $sql);
        foreach ($sql_chunks as $key => $sql_chunk) {
            if (isset($parameters[$key])) {
                $result .= $sql_chunk . '"' . $parameters[$key] . '"';
            }
        }

        return $result;
    }
}

if (!function_exists('generateCopyToClipBoard')) {
    function generateCopyToClipBoard($key, $text)
    {
        $selector = 'shortcode_' . cleanSpecialCharacters($key);

        return '<b id="' . $selector . '">' . $text . '</b> 
                <a href="#" onclick="event.preventDefault();" class="copy-button"
                data-clipboard-target="#' . $selector . '"><i class="fa fa-clipboard"></i></a>';
    }
}

if (!function_exists('cleanSpecialCharacters')) {
    function cleanSpecialCharacters($string)
    {
        // Replaces all spaces with hyphens.
        $string = str_replace(' ', '-', $string);

        // Removes special chars.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);

        // Replaces multiple hyphens with single one.
        return preg_replace('/-+/', '-', $string);
    }
}

if (!function_exists('schemaHasTable')) {
    function schemaHasTable($table)
    {
        return \Cache::remember('schema_has_' . $table, 1440, function () use ($table) {
            try {
                return \Schema::hasTable($table);
            } catch (\Exception $exception) {
                return false;

            }
        });
    }
}

if (!function_exists('stringDecimalToDecimal')) {
    function stringDecimalToDecimal($strDecimal)
    {
        return floatval(str_replace(',', '', $strDecimal));
    }
}
if (!function_exists('randomCode')) {
    function randomCode($prefix = '', $length = 6)
    {
        //append dash if prefix exists
        $prefix .= !empty($prefix) ? '-' : '';

        return strtoupper($prefix . str_random($length));
    }
}

if (!function_exists('getColsInRows')) {
    function getColsInRows($fieldClass)
    {
        switch ($fieldClass) {
            case 'col-md-1':
                $fieldsInRow = 12;
                break;
            case 'col-md-2':
                $fieldsInRow = 6;
                break;
            case 'col-md-3':
                $fieldsInRow = 4;
                break;
            case 'col-md-4':
                $fieldsInRow = 3;
                break;
            case 'col-md-5':
            case 'col-md-6':
                $fieldsInRow = 2;
                break;
            case 'col-md-7':
            case 'col-md-8':
            case 'col-md-9':
            case 'col-md-10':
            case 'col-md-11':
            case 'col-md-12':
                $fieldsInRow = 1;
                break;
            default:
                $fieldsInRow = 3;
        }

        return $fieldsInRow;
    }
}

if (!function_exists('renderContentInBSRows')) {
    function renderContentInBSRows($content, $colClass = 'col-md-12')
    {
        $j = 0;

        $colsInRow = getColsInRows($colClass);

        $output = '';

        if (!is_array($content)) {
            $content = [$content];
        }

        foreach ($content as $columnContent) {
            if ($j == 0) {
                $output .= '<div class="row">';
            }

            $output .= '<div class="' . $colClass . '">';

            $output .= $columnContent;

            $output .= '</div>';

            if (++$j == $colsInRow) {
                $output .= '</div>';
                $j = 0;
            }
        }

        if ($j > 0) {
            $output .= '</div>';
        }

        return $output;
    }
}

if (!function_exists('get_key_translation')) {
    function get_key_translation($key)
    {
        return trans($key);
    }
}

if (!function_exists('get_array_key_translation')) {
    function get_array_key_translation($array)
    {
        return array_map('get_key_translation', $array);
    }
}

if (!function_exists('getKeyValuePairs')) {
    /**
     * @param $pairs
     * @return array
     */
    function getKeyValuePairs($pairs)
    {
        if (empty($pairs)) {
            return [];
        }

        if (!is_array($pairs)) {
            $pairs = json_decode($pairs, true) ?? [];
        }

        $response = [];
        foreach ($pairs as $pair) {
            $response[current($pair)] = next($pair);
        }

        return $response;
    }
}

if (!function_exists('urlWithParameters')) {
    function urlWithParameters($urlString, $params = [])
    {
        $url = url($urlString);
        if (!empty($params)) {
            $url = $url . '?' . http_build_query($params);
        }
        return $url;
    }
}

if (!function_exists('stringPlaceholdersFormatter')) {
    /**
     * @param $description
     * @param $parameters
     * @return string
     * @throws Exception
     */
    function stringPlaceholdersFormatter($description, $parameters)
    {
        return \Fleshgrinder\Core\Formatter::format($description ?? '', $parameters);
    }
}

if (!function_exists('yesNoFormatter')) {
    function yesNoFormatter($value)
    {
        return $value ? 'Yes' : 'No';
    }
}

if (!function_exists('cleanJSONFileContent')) {
    function cleanJSONFileContent($content)
    {
        // remove comments
        $content = preg_replace('!/\*.*?\*/!s', '', $content);

        // remove empty lines that can create errors
        $content = preg_replace('/\n\s*\n/', "\n", $content);

        return $content;
    }
}

if (!function_exists('statusColoring')) {
    function statusColoring($status)
    {
        switch ($status) {
            case 'draft':
                $color = "warning";
                break;
            case 'confirmed':
                $color = "success";
                break;
            case 'active':
                $color = "primary";
                break;
            case 'completed':
                $color = "default";
                break;
            case 'cancelled':
                $color = "danger";
                break;
            case 'paid':
                $color = "success";
                break;
            case 'pending':
                $color = "primary";
                break;
            case 'failed':
                $color = "danger";
                break;
            default:
                $color = "default";
        }

        return '<strong><span class="label label-' . $color . '">' . str_replace('_', ' ', title_case($status)) . '</span></strong>';
    }
}

if (!function_exists('cloneObject')) {
    function cloneObject($oldObject, $except = [], $relations = [])
    {

        if (!is_array($except)) {
            $except = [$except];
        }

        if (!is_array($relations)) {
            $relations = [$relations];
        }

        $cloned = $oldObject->replicate(array_merge(['created_by', 'updated_by'], $except));

        $cloned->save();

        //Get Object Relations
        $oldObject->relations = [];
        $oldObject->setRelations([]);
        $oldObject->load($relations);
        $relations = $oldObject->getRelations();
        $foreign_key = $oldObject->getForeignKey();

        //Insert Relations into the clone object
        foreach ($relations as $key => $relation) {
            foreach ($relation as $relationItem) {
                $newItem = $relationItem->replicate();
                $newItem->{$foreign_key} = $cloned->id;
                $cloned->{$key}[] = $newItem;
            }
        }

        $cloned->push();

        return $cloned;
    }
}

if (!function_exists('formatProperties')) {
    function formatProperties($properties)
    {

        $formattedResponse = '';

        appendDetails($formattedResponse, $properties);

        if (!empty($formattedResponse)) {
            $formattedResponse = '<table class="popover-details-table">' . $formattedResponse . '</table>';
        }

        if (empty($formattedResponse)) {
            $formattedResponse = '-';
        }

        return $formattedResponse;
    }
}

if (!function_exists('appendDetails')) {
    function appendDetails(&$formattedResponse, $detailsArray)
    {
        if (is_array($detailsArray)) {
            foreach ($detailsArray as $key => $value) {

                $keyTitle = str_replace('_', ' ', title_case($key));

                if (strlen($key) < 3) {
                    $keyTitle = strtoupper($keyTitle);
                }

                if (is_array($value)) {
                    $formattedResponse .= "<tr><td colspan='2'>{$keyTitle}</td>";

                    appendDetails($formattedResponse, $value);
                } else {
                    $formattedResponse .= "<tr><td>{$keyTitle}</td>";

                    if (empty($value)) {
                        $value = '-';
                    }

                    $formattedResponse .= "<td><b>{$value}</b></td>";
                }
            }
        }
    }
}

if (!function_exists('getMicroTimeDurationDuration')) {
    function getMicroTimeDurationDuration($startTime, $endTime, $seconds = false)
    {
        $duration = $endTime - $startTime;

        if ($seconds) {
            $hours = (int)($duration / 60 / 60);
            $minutes = (int)($duration / 60) - ($hours * 60);

            $result = (int)$duration - ($hours * 60 * 60) - ($minutes * 60);
        } else {
            $result = $duration;
        }

        return $result;
    }
}

if (!function_exists('is_class_uses_trait')) {
    function is_class_uses_trait($class, $trait)
    {
        $classUses = class_uses($class);

        return in_array($trait, $classUses);
    }
}

if (!function_exists('addSecondsToDate')) {
    function addSecondsToDate($date)
    {
        if (!empty($date) && preg_match('/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2})$/', $date)) {
            $date .= ':00';
        }

        return $date;
    }
}

if (!function_exists('pearlsLogger')) {
    function pearlsLogger($message)
    {
        if (!config('app.enable_pearls_logger')) {
            return;
        }

        logger($message);
    }
}

if (!function_exists('getMediaUrl')) {
    function getMediaUrl($media, $download = false)
    {
        $hashed_id = hashids_encode($media->id);
        $url = url('media/' . $hashed_id);

        if ($download) {
            $url .= '/download';
        }

        return $url;
    }
}

if (!function_exists('odometerReadingUnit')) {
    function odometerReadingUnit($reading)
    {
        return $reading . ' Miles';
    }
}
if (!function_exists('getTimeAsFloat')) {
    function getTimeAsFloat($value)
    {
        $parts = explode(':', $value);

        $result = $parts[0] + floor(($parts[1] / 60) * 100) / 100;

        return $result;
    }
}
if (!function_exists('getFloatAsTime')) {
    function getFloatAsTime($value)
    {
        return sprintf('%02d:%02d', (int)$value, round(fmod($value, 1) * 60));
    }
}